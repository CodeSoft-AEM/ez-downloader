(function ($) {
    'use strict';

    var fieldIndex = 1;
    var isInstalling = false;
    var repositoryBusy = false;
    var packageBusy = false;
    var pluginSearchBusy = false;
    var themeSearchBusy = false;

    function normalizeUrl(value) {
        return $.trim(value || '');
    }

    function isValidHttpUrl(value) {
        try {
            var parsed = new URL(value);
            return parsed.protocol === 'http:' || parsed.protocol === 'https:';
        } catch (error) {
            return false;
        }
    }

    function getAjaxError(xhr, fallback) {
        if (xhr && xhr.responseJSON && xhr.responseJSON.data) {
            if (xhr.responseJSON.data.message) {
                return xhr.responseJSON.data.message;
            }
            if (typeof xhr.responseJSON.data === 'string') {
                return xhr.responseJSON.data;
            }
        }
        return fallback || ezdAdmin.i18n.networkError;
    }

    function isAnyActionBusy() {
        return isInstalling || repositoryBusy || packageBusy || pluginSearchBusy || themeSearchBusy;
    }

    function updateRemoveButtons() {
        var $rows = $('#ezd-plugin-fields .ezd-url-row');
        $rows.find('.ezd-remove-button').prop('disabled', $rows.length === 1 || isAnyActionBusy());
    }

    function updateControlStates() {
        var busy = isAnyActionBusy();

        $('#ezd-install-all, #ezd-add-plugin, #ezd-import-package, #ezd-export-package, .ezd-input').prop('disabled', busy);
        $('#ezd-plugin-repository-search-button').prop('disabled', busy);
        $('#ezd-theme-repository-search-button').prop('disabled', busy);
        $('#ezd-plugin-repository-results .ezd-repository-action, #ezd-plugin-repository-results .ezd-repository-add').prop('disabled', busy);
        $('#ezd-theme-repository-results .ezd-repository-action, #ezd-theme-repository-results .ezd-repository-add').prop('disabled', busy);

        $('#ezd-install-all').toggleClass('is-busy', isInstalling);
        $('#ezd-install-all .ezd-button-text').text(isInstalling ? ezdAdmin.i18n.installingAll : ezdAdmin.i18n.installAll);
        updateRemoveButtons();
    }

    function createPluginRow(url, focus) {
        fieldIndex += 1;
        var inputId = 'ezd-plugin-url-' + fieldIndex;
        var $row = $('<div>', { 'class': 'ezd-url-row' });
        var $label = $('<label>', {
            'class': 'screen-reader-text',
            'for': inputId,
            'text': ezdAdmin.i18n.pluginLabel + ' ' + fieldIndex
        });
        var $input = $('<input>', {
            type: 'url',
            id: inputId,
            'class': 'ezd-input ezd-plugin-url',
            form: 'ezd-installer-form',
            inputmode: 'url',
            dir: 'ltr',
            placeholder: ezdAdmin.i18n.pluginPlaceholder,
            value: url || ''
        });
        var $remove = $('<button>', {
            type: 'button',
            'class': 'ezd-remove-button',
            'aria-label': ezdAdmin.i18n.deletePlugin,
            title: ezdAdmin.i18n.delete
        }).append($('<span>', {
            'class': 'dashicons dashicons-trash',
            'aria-hidden': 'true'
        }));

        $row.append($label, $input, $remove);
        $('#ezd-plugin-fields').append($row);

        if (focus) {
            $row.hide().slideDown(140, function () {
                $input.trigger('focus');
            });
        }

        return $row;
    }

    function addPluginField() {
        if (isAnyActionBusy()) {
            return;
        }
        createPluginRow('', true);
        updateRemoveButtons();
    }

    function renderPluginFields(urls) {
        var safeUrls = Array.isArray(urls) && urls.length ? urls : [''];
        var $container = $('#ezd-plugin-fields').empty();
        fieldIndex = 0;

        safeUrls.forEach(function (url) {
            createPluginRow(url, false);
        });

        if (!$container.children().length) {
            createPluginRow('', false);
        }
        updateRemoveButtons();
    }

    function collectPluginUrls() {
        var urls = [];
        var seen = {};

        $('.ezd-plugin-url').each(function () {
            var url = normalizeUrl($(this).val());
            if (url && !seen[url]) {
                urls.push(url);
                seen[url] = true;
            }
        });

        return urls;
    }

    function collectQueue() {
        var queue = [];

        collectPluginUrls().forEach(function (url) {
            queue.push({ type: 'plugin', url: url, label: ezdAdmin.i18n.pluginLabel });
        });

        var themeUrl = normalizeUrl($('#ezd-theme-url').val());
        if (themeUrl) {
            queue.push({ type: 'theme', url: themeUrl, label: ezdAdmin.i18n.themeLabel });
        }

        return queue;
    }

    function createResultRows(queue) {
        var $results = $('#ezd-install-results').empty();

        queue.forEach(function (item, index) {
            var $row = $('<div>', {
                'class': 'ezd-result-row is-waiting',
                'data-result-index': index
            });
            var $status = $('<span>', {
                'class': 'ezd-result-status',
                'aria-hidden': 'true'
            }).append($('<span>', { 'class': 'dashicons dashicons-clock' }));
            var $content = $('<span>', { 'class': 'ezd-result-content' })
                .append($('<strong>', { text: item.label + ' ' + (index + 1) }))
                .append($('<small>', { text: item.url, dir: 'ltr' }));
            var $message = $('<span>', {
                'class': 'ezd-result-message',
                text: ezdAdmin.i18n.waiting
            });

            $row.append($status, $content, $message);
            $results.append($row);
        });
    }

    function setProgress(completed, total) {
        var percent = total ? Math.round((completed / total) * 100) : 0;
        $('#ezd-progress-bar').css('width', percent + '%');
        $('#ezd-progress-summary').text(completed + ' / ' + total);
    }

    function setRowState(index, state, message) {
        var $row = $('[data-result-index="' + index + '"]');
        var iconClass = 'dashicons-clock';

        $row.removeClass('is-waiting is-installing is-success is-error').addClass('is-' + state);

        if (state === 'installing') {
            iconClass = 'dashicons-update ezd-spin';
        } else if (state === 'success') {
            iconClass = 'dashicons-yes-alt';
        } else if (state === 'error') {
            iconClass = 'dashicons-warning';
        }

        $row.find('.ezd-result-status .dashicons').attr('class', 'dashicons ' + iconClass);
        $row.find('.ezd-result-message').text(message);
    }

    function installItem(item, index) {
        setRowState(index, 'installing', ezdAdmin.i18n.installing);

        return $.ajax({
            url: ezdAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            timeout: 360000,
            data: {
                action: 'ezd_install_package',
                nonce: ezdAdmin.nonce,
                package_type: item.type,
                package_url: item.url
            }
        }).then(function (response) {
            if (!response || !response.success) {
                var errorMessage = response && response.data && response.data.message
                    ? response.data.message
                    : ezdAdmin.i18n.networkError;
                return $.Deferred().reject({ message: errorMessage }).promise();
            }

            setRowState(index, 'success', response.data.message || ezdAdmin.i18n.success);
            return response;
        }, function (xhr) {
            return $.Deferred().reject({ message: getAjaxError(xhr) }).promise();
        });
    }

    function processQueue(queue) {
        var completed = 0;
        var chain = $.Deferred().resolve().promise();

        queue.forEach(function (item, index) {
            chain = chain.then(function () {
                return installItem(item, index).then(
                    function () {
                        return true;
                    },
                    function (error) {
                        setRowState(index, 'error', error.message || ezdAdmin.i18n.failed);
                        return false;
                    }
                ).always(function () {
                    completed += 1;
                    setProgress(completed, queue.length);
                });
            });
        });

        return chain;
    }

    function showInlineStatus(selector, message, type) {
        $(selector)
            .removeClass('is-success is-error is-loading')
            .addClass('is-' + (type || 'success'))
            .text(message)
            .prop('hidden', false);
    }

    function hideInlineStatus(selector) {
        $(selector).prop('hidden', true).text('').removeClass('is-success is-error is-loading');
    }

    function downloadPackageFile(filename, content) {
        var blob = new Blob([content], { type: 'application/json;charset=utf-8' });
        var url = window.URL.createObjectURL(blob);
        var link = document.createElement('a');
        link.href = url;
        link.download = filename || 'ez-downloader-package.json';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    function exportPackage() {
        if (isAnyActionBusy()) {
            return;
        }

        var pluginUrls = collectPluginUrls();
        var themeUrl = normalizeUrl($('#ezd-theme-url').val());
        var allUrls = pluginUrls.concat(themeUrl ? [themeUrl] : []);

        if (!allUrls.length) {
            showInlineStatus('#ezd-package-status', ezdAdmin.i18n.nothingSelected, 'error');
            return;
        }

        if (allUrls.some(function (url) { return !isValidHttpUrl(url); })) {
            showInlineStatus('#ezd-package-status', ezdAdmin.i18n.invalidUrl, 'error');
            return;
        }

        packageBusy = true;
        updateControlStates();
        showInlineStatus('#ezd-package-status', ezdAdmin.i18n.exporting, 'loading');

        $.ajax({
            url: ezdAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'ezd_export_package',
                nonce: ezdAdmin.nonce,
                plugin_urls: pluginUrls,
                theme_url: themeUrl
            }
        }).done(function (response) {
            if (!response || !response.success || !response.data || !response.data.content) {
                showInlineStatus('#ezd-package-status', ezdAdmin.i18n.invalidPackage, 'error');
                return;
            }
            downloadPackageFile(response.data.filename, response.data.content);
            showInlineStatus('#ezd-package-status', response.data.message || ezdAdmin.i18n.packageExported, 'success');
        }).fail(function (xhr) {
            showInlineStatus('#ezd-package-status', getAjaxError(xhr), 'error');
        }).always(function () {
            packageBusy = false;
            updateControlStates();
        });
    }

    function importPackage(file) {
        if (isAnyActionBusy() || !file) {
            return;
        }

        var formData = new FormData();
        formData.append('action', 'ezd_import_package');
        formData.append('nonce', ezdAdmin.nonce);
        formData.append('package_file', file);

        packageBusy = true;
        updateControlStates();
        showInlineStatus('#ezd-package-status', ezdAdmin.i18n.importing, 'loading');

        $.ajax({
            url: ezdAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 120000
        }).done(function (response) {
            if (!response || !response.success || !response.data) {
                showInlineStatus('#ezd-package-status', ezdAdmin.i18n.invalidPackage, 'error');
                return;
            }

            renderPluginFields(response.data.plugin_urls || []);
            $('#ezd-theme-url').val(response.data.theme_url || '');
            showInlineStatus('#ezd-package-status', response.data.message || ezdAdmin.i18n.packageImported, 'success');
        }).fail(function (xhr) {
            showInlineStatus('#ezd-package-status', getAjaxError(xhr, ezdAdmin.i18n.invalidPackage), 'error');
        }).always(function () {
            packageBusy = false;
            $('#ezd-package-file').val('');
            updateControlStates();
        });
    }

    function formatNumber(number) {
        try {
            return Number(number || 0).toLocaleString();
        } catch (error) {
            return String(number || 0);
        }
    }

    function repositoryButtonLabel(item) {
        if (item.update_available) {
            return ezdAdmin.i18n.update;
        }
        if (item.installed) {
            return ezdAdmin.i18n.reinstall;
        }
        return ezdAdmin.i18n.install;
    }

    function buildRepositoryMeta(item, includeInstalls) {
        var metaText = 'v' + (item.version || '—');
        if (includeInstalls) {
            metaText += ' · ' + formatNumber(item.active_installs);
        }

        var $meta = $('<div>', { 'class': 'ezd-repository-plugin__meta' })
            .append($('<span>', { text: metaText, dir: 'ltr' }))
            .append($('<span>', { text: '★ ' + Math.round((item.rating || 0) / 20) + '/5', dir: 'ltr' }));

        if (item.installed) {
            $meta.append($('<span>', {
                'class': item.update_available ? 'is-update' : 'is-installed',
                text: ezdAdmin.i18n.installed + ': ' + (item.installed_version || '—'),
                dir: 'ltr'
            }));
        }

        return $meta;
    }

    function renderPluginRepositoryResults(plugins) {
        var $results = $('#ezd-plugin-repository-results').empty();

        if (!plugins || !plugins.length) {
            showInlineStatus('#ezd-plugin-repository-status', ezdAdmin.i18n.noPluginResults, 'error');
            return;
        }

        hideInlineStatus('#ezd-plugin-repository-status');

        plugins.forEach(function (plugin) {
            var $card = $('<article>', {
                'class': 'ezd-repository-plugin',
                'data-plugin-slug': plugin.slug
            });
            var $top = $('<div>', { 'class': 'ezd-repository-plugin__top' });
            var $icon = plugin.icon
                ? $('<img>', { src: plugin.icon, alt: '', loading: 'lazy' })
                : $('<span>', { 'class': 'dashicons dashicons-admin-plugins ezd-repository-plugin__fallback', 'aria-hidden': 'true' });
            var $titleWrap = $('<div>', { 'class': 'ezd-repository-plugin__title' })
                .append($('<h3>', { text: plugin.name }))
                .append($('<code>', { text: plugin.slug, dir: 'ltr' }));
            var $description = $('<p>', { text: plugin.description || '' });
            var $footer = $('<footer>', { 'class': 'ezd-repository-plugin__footer' });
            var $message = $('<span>', { 'class': 'ezd-repository-plugin__message', 'aria-live': 'polite' });
            var $actions = $('<div>', { 'class': 'ezd-repository-plugin__actions' });
            var $addButton = $('<button>', {
                type: 'button',
                'class': 'button button-secondary ezd-repository-add ezd-plugin-repository-add',
                'data-download-url': plugin.download_url || '',
                'data-slug': plugin.slug,
                text: ezdAdmin.i18n.addToPluginList
            });
            var $button = $('<button>', {
                type: 'button',
                'class': 'button button-primary ezd-repository-action ezd-plugin-repository-action',
                'data-slug': plugin.slug,
                'data-installed': plugin.installed ? '1' : '0',
                'data-update': plugin.update_available ? '1' : '0',
                text: repositoryButtonLabel(plugin)
            });

            $top.append($icon, $titleWrap);
            $actions.append($addButton, $button);
            $footer.append($message, $actions);
            $card.append($top, buildRepositoryMeta(plugin, true), $description, $footer);
            $results.append($card);
        });

        updateControlStates();
    }

    function renderThemeRepositoryResults(themes) {
        var $results = $('#ezd-theme-repository-results').empty();

        if (!themes || !themes.length) {
            showInlineStatus('#ezd-theme-repository-status', ezdAdmin.i18n.noThemeResults, 'error');
            return;
        }

        hideInlineStatus('#ezd-theme-repository-status');

        themes.forEach(function (theme) {
            var $card = $('<article>', {
                'class': 'ezd-repository-plugin ezd-repository-theme',
                'data-theme-slug': theme.slug
            });
            var $top = $('<div>', { 'class': 'ezd-repository-plugin__top' });
            var $image = theme.screenshot
                ? $('<img>', { src: theme.screenshot, alt: '', loading: 'lazy' })
                : $('<span>', { 'class': 'dashicons dashicons-admin-appearance ezd-repository-plugin__fallback', 'aria-hidden': 'true' });
            var $titleWrap = $('<div>', { 'class': 'ezd-repository-plugin__title' })
                .append($('<h3>', { text: theme.name }))
                .append($('<code>', { text: theme.slug, dir: 'ltr' }));
            var $description = $('<p>', { text: theme.description || '' });
            var $footer = $('<footer>', { 'class': 'ezd-repository-plugin__footer' });
            var $message = $('<span>', { 'class': 'ezd-repository-plugin__message', 'aria-live': 'polite' });
            var $actions = $('<div>', { 'class': 'ezd-repository-plugin__actions' });
            var $addButton = $('<button>', {
                type: 'button',
                'class': 'button button-secondary ezd-repository-add ezd-theme-repository-add',
                'data-download-url': theme.download_url || '',
                'data-slug': theme.slug,
                text: ezdAdmin.i18n.addToThemeField
            });
            var $button = $('<button>', {
                type: 'button',
                'class': 'button button-primary ezd-repository-action ezd-theme-repository-action',
                'data-slug': theme.slug,
                'data-installed': theme.installed ? '1' : '0',
                'data-update': theme.update_available ? '1' : '0',
                text: repositoryButtonLabel(theme)
            });

            $top.append($image, $titleWrap);
            $actions.append($addButton, $button);
            $footer.append($message, $actions);
            $card.append($top, buildRepositoryMeta(theme, false), $description, $footer);
            $results.append($card);
        });

        updateControlStates();
    }

    function putRepositoryUrlInPluginList(downloadUrl, $message) {
        var duplicate = false;

        $('.ezd-plugin-url').each(function () {
            if (normalizeUrl($(this).val()) === downloadUrl) {
                duplicate = true;
                $(this).trigger('focus');
                return false;
            }
        });

        if (duplicate) {
            $message.addClass('is-error').text(ezdAdmin.i18n.alreadyInList);
            return;
        }

        var $emptyInput = $('.ezd-plugin-url').filter(function () {
            return !normalizeUrl($(this).val());
        }).first();

        if ($emptyInput.length) {
            $emptyInput.val(downloadUrl).trigger('focus');
        } else {
            createPluginRow(downloadUrl, false);
            updateRemoveButtons();
        }

        $message.addClass('is-success').text(ezdAdmin.i18n.addedToPluginList);
        $('html, body').animate({
            scrollTop: Math.max(0, $('#ezd-installer-form').offset().top - 70)
        }, 260);
    }

    function putRepositoryUrlInThemeField(downloadUrl, $message) {
        var $themeInput = $('#ezd-theme-url');
        var currentUrl = normalizeUrl($themeInput.val());

        if (currentUrl === downloadUrl) {
            $themeInput.trigger('focus');
            $message.addClass('is-error').text(ezdAdmin.i18n.themeAlreadySelected);
            return;
        }

        $themeInput.val(downloadUrl).trigger('focus');
        $message.addClass('is-success').text(ezdAdmin.i18n.addedToThemeField);
        $('html, body').animate({
            scrollTop: Math.max(0, $('#ezd-installer-form').offset().top - 70)
        }, 260);
    }

    function addRepositoryPluginToList($button) {
        if (isAnyActionBusy()) {
            return;
        }

        var downloadUrl = normalizeUrl($button.attr('data-download-url'));
        var slug = String($button.data('slug') || '');
        var $card = $button.closest('.ezd-repository-plugin');
        var $message = $card.find('.ezd-repository-plugin__message');

        $message.removeClass('is-success is-error').text('');

        if (downloadUrl && isValidHttpUrl(downloadUrl)) {
            putRepositoryUrlInPluginList(downloadUrl, $message);
            return;
        }

        repositoryBusy = true;
        $button.addClass('is-busy').text(ezdAdmin.i18n.fetchingRepoUrl);
        $message.text(ezdAdmin.i18n.fetchingRepoUrl);
        updateControlStates();

        $.ajax({
            url: ezdAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            timeout: 60000,
            data: {
                action: 'ezd_get_repository_plugin_url',
                nonce: ezdAdmin.nonce,
                slug: slug
            }
        }).done(function (response) {
            if (!response || !response.success || !response.data || !response.data.download_url) {
                $message.addClass('is-error').text(ezdAdmin.i18n.missingRepoUrl);
                return;
            }

            downloadUrl = normalizeUrl(response.data.download_url);
            if (!isValidHttpUrl(downloadUrl)) {
                $message.addClass('is-error').text(ezdAdmin.i18n.missingRepoUrl);
                return;
            }

            $button.attr('data-download-url', downloadUrl);
            putRepositoryUrlInPluginList(downloadUrl, $message);
        }).fail(function (xhr) {
            $message.addClass('is-error').text(getAjaxError(xhr, ezdAdmin.i18n.missingRepoUrl));
        }).always(function () {
            repositoryBusy = false;
            $button.removeClass('is-busy').text(ezdAdmin.i18n.addToPluginList);
            updateControlStates();
        });
    }

    function addRepositoryThemeToField($button) {
        if (isAnyActionBusy()) {
            return;
        }

        var downloadUrl = normalizeUrl($button.attr('data-download-url'));
        var slug = String($button.data('slug') || '');
        var $card = $button.closest('.ezd-repository-plugin');
        var $message = $card.find('.ezd-repository-plugin__message');

        $message.removeClass('is-success is-error').text('');

        if (downloadUrl && isValidHttpUrl(downloadUrl)) {
            putRepositoryUrlInThemeField(downloadUrl, $message);
            return;
        }

        repositoryBusy = true;
        $button.addClass('is-busy').text(ezdAdmin.i18n.fetchingRepoUrl);
        $message.text(ezdAdmin.i18n.fetchingRepoUrl);
        updateControlStates();

        $.ajax({
            url: ezdAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            timeout: 60000,
            data: {
                action: 'ezd_get_repository_theme_url',
                nonce: ezdAdmin.nonce,
                slug: slug
            }
        }).done(function (response) {
            if (!response || !response.success || !response.data || !response.data.download_url) {
                $message.addClass('is-error').text(ezdAdmin.i18n.missingRepoUrl);
                return;
            }

            downloadUrl = normalizeUrl(response.data.download_url);
            if (!isValidHttpUrl(downloadUrl)) {
                $message.addClass('is-error').text(ezdAdmin.i18n.missingRepoUrl);
                return;
            }

            $button.attr('data-download-url', downloadUrl);
            putRepositoryUrlInThemeField(downloadUrl, $message);
        }).fail(function (xhr) {
            $message.addClass('is-error').text(getAjaxError(xhr, ezdAdmin.i18n.missingRepoUrl));
        }).always(function () {
            repositoryBusy = false;
            $button.removeClass('is-busy').text(ezdAdmin.i18n.addToThemeField);
            updateControlStates();
        });
    }

    function searchPluginRepository() {
        var search = $.trim($('#ezd-plugin-repository-search').val());
        if (search.length < 2) {
            showInlineStatus('#ezd-plugin-repository-status', ezdAdmin.i18n.pluginSearchRequired, 'error');
            return;
        }

        if (isAnyActionBusy()) {
            return;
        }

        pluginSearchBusy = true;
        $('#ezd-plugin-repository-search-button span:last').text(ezdAdmin.i18n.searching);
        $('#ezd-plugin-repository-results').empty();
        showInlineStatus('#ezd-plugin-repository-status', ezdAdmin.i18n.searching, 'loading');
        updateControlStates();

        $.ajax({
            url: ezdAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            timeout: 60000,
            data: {
                action: 'ezd_search_repository_plugins',
                nonce: ezdAdmin.nonce,
                search: search,
                page: 1
            }
        }).done(function (response) {
            if (!response || !response.success || !response.data) {
                showInlineStatus('#ezd-plugin-repository-status', ezdAdmin.i18n.repositoryError, 'error');
                return;
            }
            renderPluginRepositoryResults(response.data.plugins || []);
        }).fail(function (xhr) {
            showInlineStatus('#ezd-plugin-repository-status', getAjaxError(xhr, ezdAdmin.i18n.repositoryError), 'error');
        }).always(function () {
            pluginSearchBusy = false;
            $('#ezd-plugin-repository-search-button span:last').text(ezdAdmin.i18n.pluginSearchButton);
            updateControlStates();
        });
    }

    function searchThemeRepository() {
        var search = $.trim($('#ezd-theme-repository-search').val());
        if (search.length < 2) {
            showInlineStatus('#ezd-theme-repository-status', ezdAdmin.i18n.themeSearchRequired, 'error');
            return;
        }

        if (isAnyActionBusy()) {
            return;
        }

        themeSearchBusy = true;
        $('#ezd-theme-repository-search-button span:last').text(ezdAdmin.i18n.searching);
        $('#ezd-theme-repository-results').empty();
        showInlineStatus('#ezd-theme-repository-status', ezdAdmin.i18n.searching, 'loading');
        updateControlStates();

        $.ajax({
            url: ezdAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            timeout: 60000,
            data: {
                action: 'ezd_search_repository_themes',
                nonce: ezdAdmin.nonce,
                search: search,
                page: 1
            }
        }).done(function (response) {
            if (!response || !response.success || !response.data) {
                showInlineStatus('#ezd-theme-repository-status', ezdAdmin.i18n.themeRepositoryError, 'error');
                return;
            }
            renderThemeRepositoryResults(response.data.themes || []);
        }).fail(function (xhr) {
            showInlineStatus('#ezd-theme-repository-status', getAjaxError(xhr, ezdAdmin.i18n.themeRepositoryError), 'error');
        }).always(function () {
            themeSearchBusy = false;
            $('#ezd-theme-repository-search-button span:last').text(ezdAdmin.i18n.themeSearchButton);
            updateControlStates();
        });
    }

    function installRepositoryItem($button, type) {
        if (isAnyActionBusy()) {
            return;
        }

        var slug = $button.data('slug');
        var wasInstalled = String($button.data('installed')) === '1';
        var wasUpdate = String($button.data('update')) === '1';
        var $card = $button.closest('.ezd-repository-plugin');
        var $message = $card.find('.ezd-repository-plugin__message');
        var action = type === 'theme' ? 'ezd_install_repository_theme' : 'ezd_install_repository_plugin';
        var fallbackError = type === 'theme' ? ezdAdmin.i18n.themeRepositoryError : ezdAdmin.i18n.repositoryError;

        repositoryBusy = true;
        $button.addClass('is-busy').text(wasUpdate ? ezdAdmin.i18n.updating : ezdAdmin.i18n.installNow);
        $message.removeClass('is-success is-error').text('');
        updateControlStates();

        $.ajax({
            url: ezdAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            timeout: 360000,
            data: {
                action: action,
                nonce: ezdAdmin.nonce,
                slug: slug
            }
        }).done(function (response) {
            if (!response || !response.success || !response.data) {
                $message.addClass('is-error').text(fallbackError);
                return;
            }

            $message.addClass('is-success').text(response.data.message || ezdAdmin.i18n.success);
            $button
                .removeClass('button-primary')
                .addClass('button-secondary')
                .attr('data-installed', '1')
                .data('installed', 1)
                .attr('data-update', '0')
                .data('update', 0)
                .text(ezdAdmin.i18n.reinstall);
        }).fail(function (xhr) {
            $message.addClass('is-error').text(getAjaxError(xhr, fallbackError));
            $button.text(wasUpdate ? ezdAdmin.i18n.update : (wasInstalled ? ezdAdmin.i18n.reinstall : ezdAdmin.i18n.install));
        }).always(function () {
            repositoryBusy = false;
            $button.removeClass('is-busy');
            updateControlStates();
        });
    }

    $('#ezd-add-plugin').on('click', addPluginField);

    $('#ezd-plugin-fields').on('click', '.ezd-remove-button', function () {
        if (isAnyActionBusy()) {
            return;
        }

        var $row = $(this).closest('.ezd-url-row');
        if ($('#ezd-plugin-fields .ezd-url-row').length <= 1) {
            return;
        }
        $row.slideUp(120, function () {
            $row.remove();
            updateRemoveButtons();
        });
    });

    $('#ezd-installer-form').on('submit', function (event) {
        event.preventDefault();

        if (isAnyActionBusy()) {
            return;
        }

        var queue = collectQueue();
        if (!queue.length) {
            showInlineStatus('#ezd-package-status', ezdAdmin.i18n.nothingSelected, 'error');
            return;
        }

        var invalidItem = queue.some(function (item) {
            return !isValidHttpUrl(item.url);
        });
        if (invalidItem) {
            showInlineStatus('#ezd-package-status', ezdAdmin.i18n.invalidUrl, 'error');
            return;
        }

        hideInlineStatus('#ezd-package-status');
        $('#ezd-progress-panel').prop('hidden', false);
        createResultRows(queue);
        setProgress(0, queue.length);
        isInstalling = true;
        updateControlStates();

        processQueue(queue).always(function () {
            isInstalling = false;
            updateControlStates();
            $('#ezd-progress-summary').text(ezdAdmin.i18n.finished);
        });
    });

    $('#ezd-export-package').on('click', exportPackage);

    $('#ezd-import-package').on('click', function () {
        if (!isAnyActionBusy()) {
            $('#ezd-package-file').trigger('click');
        }
    });

    $('#ezd-package-file').on('change', function () {
        var file = this.files && this.files.length ? this.files[0] : null;
        if (!file) {
            showInlineStatus('#ezd-package-status', ezdAdmin.i18n.choosePackage, 'error');
            return;
        }
        importPackage(file);
    });

    $('#ezd-plugin-repository-search-form').on('submit', function (event) {
        event.preventDefault();
        searchPluginRepository();
    });

    $('#ezd-theme-repository-search-form').on('submit', function (event) {
        event.preventDefault();
        searchThemeRepository();
    });

    $('#ezd-plugin-repository-results').on('click', '.ezd-plugin-repository-action', function () {
        installRepositoryItem($(this), 'plugin');
    });

    $('#ezd-plugin-repository-results').on('click', '.ezd-plugin-repository-add', function () {
        addRepositoryPluginToList($(this));
    });

    $('#ezd-theme-repository-results').on('click', '.ezd-theme-repository-action', function () {
        installRepositoryItem($(this), 'theme');
    });

    $('#ezd-theme-repository-results').on('click', '.ezd-theme-repository-add', function () {
        addRepositoryThemeToField($(this));
    });

    $('.ezd-help-toggle').on('click', function (event) {
        event.stopPropagation();
        var $button = $(this);
        var controls = $button.attr('aria-controls');
        var $popover = $('#' + controls);
        var willOpen = $popover.prop('hidden');

        $('.ezd-help-popover').prop('hidden', true);
        $('.ezd-help-toggle').attr('aria-expanded', 'false');

        $popover.prop('hidden', !willOpen);
        $button.attr('aria-expanded', willOpen ? 'true' : 'false');
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('.ezd-help-control').length) {
            $('.ezd-help-popover').prop('hidden', true);
            $('.ezd-help-toggle').attr('aria-expanded', 'false');
        }
    });

    updateRemoveButtons();
    updateControlStates();
}(jQuery));
