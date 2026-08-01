<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Whether the current WordPress locale is Persian.
 *
 * @return bool
 */
function ezd_is_persian() {
    $locale = function_exists('determine_locale') ? determine_locale() : get_locale();
    return 0 === strpos((string) $locale, 'fa');
}

/**
 * Return a built-in Persian or English translation.
 *
 * @param string $fa Persian text.
 * @param string $en English text.
 * @return string
 */
function ezd_text($fa, $en) {
    return ezd_is_persian() ? $fa : $en;
}

/**
 * Load local translation files for plugin metadata and future strings.
 *
 * @return void
 */
function ezd_load_textdomain() {
    load_plugin_textdomain('ez-downloader', false, dirname(plugin_basename(EZD_FILE)) . '/languages');
}
add_action('plugins_loaded', 'ezd_load_textdomain');

/**
 * Load assets only on the EZ Downloader admin page.
 *
 * @param string $hook Current admin page hook.
 * @return void
 */
function ezd_enqueue_admin_assets($hook) {
    if ('toplevel_page_custom-plugin-installer' !== $hook) {
        return;
    }

    wp_enqueue_style(
        'ezd-vazirmatn',
        'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'ezd-admin',
        EZD_URL . 'assets/css/admin.css',
        array('dashicons', 'ezd-vazirmatn'),
        EZD_VERSION
    );

    wp_enqueue_script(
        'ezd-admin',
        EZD_URL . 'assets/js/admin.js',
        array('jquery'),
        EZD_VERSION,
        true
    );

    wp_localize_script(
        'ezd-admin',
        'ezdAdmin',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('ezd_admin_action'),
            'isRtl'   => is_rtl(),
            'i18n'    => array(
                'pluginLabel'         => ezd_text('افزونه', 'Plugin'),
                'themeLabel'          => ezd_text('قالب', 'Theme'),
                'waiting'             => ezd_text('در انتظار نصب', 'Waiting'),
                'installing'          => ezd_text('در حال نصب…', 'Installing…'),
                'updating'            => ezd_text('در حال بروزرسانی…', 'Updating…'),
                'success'             => ezd_text('نصب شد', 'Installed'),
                'failed'              => ezd_text('ناموفق', 'Failed'),
                'nothingSelected'     => ezd_text('حداقل یک لینک افزونه یا قالب وارد کنید.', 'Enter at least one plugin or theme URL.'),
                'invalidUrl'          => ezd_text('یکی از لینک‌ها معتبر نیست.', 'One of the URLs is invalid.'),
                'finished'            => ezd_text('فرآیند نصب همه موارد به پایان رسید.', 'All installation tasks have finished.'),
                'networkError'        => ezd_text('ارتباط با سرور قطع شد یا پاسخ معتبری دریافت نشد.', 'The connection failed or the server returned an invalid response.'),
                'installAll'          => ezd_text('نصب همه موارد', 'Install all'),
                'installingAll'       => ezd_text('در حال نصب…', 'Installing…'),
                'deletePlugin'        => ezd_text('حذف این افزونه', 'Remove this plugin'),
                'delete'              => ezd_text('حذف', 'Remove'),
                'packageExported'     => ezd_text('پکیج با موفقیت برون‌بری شد.', 'The package was exported successfully.'),
                'packageImported'     => ezd_text('پکیج درون‌ریزی شد و لینک‌ها در فیلدها قرار گرفتند.', 'The package was imported and its URLs were loaded into the fields.'),
                'exporting'           => ezd_text('در حال ساخت پکیج…', 'Creating package…'),
                'importing'           => ezd_text('در حال درون‌ریزی…', 'Importing…'),
                'choosePackage'       => ezd_text('یک فایل پکیج EZ Downloader انتخاب کنید.', 'Choose an EZ Downloader package file.'),
                'pluginSearchRequired'=> ezd_text('نام یا عبارت افزونه را وارد کنید.', 'Enter a plugin name or search phrase.'),
                'themeSearchRequired' => ezd_text('نام یا عبارت قالب را وارد کنید.', 'Enter a theme name or search phrase.'),
                'searching'           => ezd_text('در حال جست‌وجو…', 'Searching…'),
                'pluginSearchButton'  => ezd_text('جست‌وجوی افزونه', 'Search plugins'),
                'themeSearchButton'   => ezd_text('جست‌وجوی قالب', 'Search themes'),
                'noPluginResults'     => ezd_text('افزونه‌ای پیدا نشد.', 'No plugins were found.'),
                'noThemeResults'      => ezd_text('قالبی پیدا نشد.', 'No themes were found.'),
                'install'             => ezd_text('نصب', 'Install'),
                'update'              => ezd_text('بروزرسانی', 'Update'),
                'reinstall'           => ezd_text('نصب مجدد', 'Reinstall'),
                'installed'           => ezd_text('نصب‌شده', 'Installed'),
                'installNow'          => ezd_text('در حال نصب…', 'Installing…'),
                'updated'             => ezd_text('بروزرسانی شد', 'Updated'),
                'repositoryError'     => ezd_text('دریافت اطلاعات مخزن وردپرس ناموفق بود.', 'Could not retrieve data from the WordPress.org repository.'),
                'themeRepositoryError'=> ezd_text('دریافت اطلاعات قالب‌ها از مخزن وردپرس ناموفق بود.', 'Could not retrieve themes from the WordPress.org repository.'),
                'addToPluginList'     => ezd_text('افزودن به لیست افزونه‌ها', 'Add to plugin list'),
                'addedToPluginList'   => ezd_text('لینک افزونه به لیست افزونه‌ها اضافه شد.', 'The plugin URL was added to the plugin list.'),
                'alreadyInList'       => ezd_text('این افزونه از قبل در لیست افزونه‌ها قرار دارد.', 'This plugin is already in the plugin list.'),
                'addToThemeField'     => ezd_text('افزودن به بخش قالب', 'Add to theme installer'),
                'addedToThemeField'   => ezd_text('لینک قالب در نصب‌کننده قالب قرار گرفت.', 'The theme URL was added to the theme installer.'),
                'themeAlreadySelected'=> ezd_text('این قالب از قبل در نصب‌کننده قالب قرار دارد.', 'This theme is already selected in the theme installer.'),
                'missingRepoUrl'      => ezd_text('لینک دانلود این مورد از مخزن دریافت نشد.', 'The repository did not provide a download URL for this item.'),
                'fetchingRepoUrl'     => ezd_text('در حال دریافت لینک دانلود…', 'Fetching download URL…'),
                'invalidPackage'      => ezd_text('فایل پکیج معتبر نیست.', 'The package file is invalid.'),
                'importReplaced'      => ezd_text('لینک‌های موجود با محتوای پکیج جایگزین شدند.', 'Current URLs were replaced with the package contents.'),
                'noticeTitle'         => ezd_text('وضعیت', 'Status'),
                'pluginPlaceholder'   => 'https://example.com/plugin.zip',
            ),
        )
    );
}
add_action('admin_enqueue_scripts', 'ezd_enqueue_admin_assets');


/**
 * Add a direct Settings link below the plugin name on the Plugins screen.
 *
 * @param array $links Existing plugin action links.
 * @return array
 */
function ezd_add_plugin_action_links($links) {
    if (!current_user_can('manage_options')) {
        return $links;
    }

    $settings_link = sprintf(
        '<a href="%1$s">%2$s</a>',
        esc_url(admin_url('admin.php?page=custom-plugin-installer')),
        esc_html(ezd_text('تنظیمات', 'Settings'))
    );

    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(EZD_FILE), 'ezd_add_plugin_action_links');

/**
 * Verify the shared admin AJAX nonce and capability.
 *
 * @param string $capability Required capability.
 * @return void
 */
function ezd_verify_ajax_request($capability) {
    check_ajax_referer('ezd_admin_action', 'nonce');

    if (!current_user_can($capability)) {
        wp_send_json_error(
            array('message' => ezd_text('شما مجوز انجام این عملیات را ندارید.', 'You are not allowed to perform this action.')),
            403
        );
    }
}

/**
 * Sanitize a list of package URLs.
 *
 * @param mixed $raw_urls Raw URL list.
 * @param int   $limit Maximum number of URLs.
 * @return array
 */
function ezd_sanitize_package_urls($raw_urls, $limit = 50) {
    if (!is_array($raw_urls)) {
        return array();
    }

    $urls = array();
    foreach (array_slice($raw_urls, 0, $limit) as $raw_url) {
        $url = esc_url_raw(wp_unslash((string) $raw_url), array('http', 'https'));
        if ($url && wp_http_validate_url($url) && !in_array($url, $urls, true)) {
            $urls[] = $url;
        }
    }

    return $urls;
}

/**
 * AJAX handler for one item in the direct-link installation queue.
 * Items are sent sequentially by the browser to avoid concurrent filesystem writes.
 *
 * @return void
 */
function ezd_ajax_install_package() {
    ezd_verify_ajax_request('manage_options');

    $type = isset($_POST['package_type']) ? sanitize_key(wp_unslash($_POST['package_type'])) : '';
    $url  = isset($_POST['package_url']) ? esc_url_raw(wp_unslash($_POST['package_url']), array('http', 'https')) : '';

    if (!in_array($type, array('plugin', 'theme'), true)) {
        wp_send_json_error(array('message' => ezd_text('نوع بسته نصب مشخص نیست.', 'The package type is missing.')), 400);
    }

    if (!$url || !wp_http_validate_url($url)) {
        wp_send_json_error(array('message' => ezd_text('لینک دانلود معتبر نیست.', 'The download URL is invalid.')), 400);
    }

    if ('plugin' === $type) {
        if (!current_user_can('install_plugins')) {
            wp_send_json_error(array('message' => ezd_text('شما مجوز نصب افزونه را ندارید.', 'You are not allowed to install plugins.')), 403);
        }
        $result = cpi_download_and_extract_plugin($url);
    } else {
        if (!current_user_can('install_themes')) {
            wp_send_json_error(array('message' => ezd_text('شما مجوز نصب قالب را ندارید.', 'You are not allowed to install themes.')), 403);
        }
        $result = cti_download_and_extract_theme($url);
    }

    if (is_wp_error($result)) {
        wp_send_json_error(
            array(
                'message' => $result->get_error_message(),
                'code'    => $result->get_error_code(),
            ),
            500
        );
    }

    wp_send_json_success($result);
}
add_action('wp_ajax_ezd_install_package', 'ezd_ajax_install_package');

/**
 * Normalize Persian/Arabic characters before repository alias matching.
 *
 * @param string $search Raw search phrase.
 * @return string
 */
function ezd_normalize_repository_search($search) {
    $search = sanitize_text_field((string) $search);
    $search = strtr(
        $search,
        array(
            'ي' => 'ی',
            'ى' => 'ی',
            'ك' => 'ک',
            'ۀ' => 'ه',
            'ة' => 'ه',
            'ؤ' => 'و',
            'إ' => 'ا',
            'أ' => 'ا',
            'ٱ' => 'ا',
            '‌' => ' ',
        )
    );
    $search = preg_replace('/\s+/u', ' ', trim($search));

    return is_string($search) ? $search : '';
}

/**
 * Return common Persian names mapped to WordPress.org search terms.
 * Original Persian text is still searched, so Persian-native plugins remain discoverable.
 *
 * @return array
 */
function ezd_repository_search_aliases() {
    return array(
        'المنتور'          => 'elementor',
        'المنتور پرو'      => 'elementor',
        'ووکامرس'          => 'woocommerce',
        'ووکامرث'          => 'woocommerce',
        'یواست'            => 'wordpress seo',
        'یوست'             => 'wordpress seo',
        'رنک مث'           => 'rank math',
        'رنکمث'            => 'rank math',
        'اکیسمت'           => 'akismet',
        'آکیسمت'           => 'akismet',
        'وردفنس'           => 'wordfence',
        'جت پک'            => 'jetpack',
        'جتپک'             => 'jetpack',
        'فرم تماس ۷'       => 'contact form 7',
        'فرم تماس 7'       => 'contact form 7',
        'کانتکت فرم ۷'     => 'contact form 7',
        'کانتکت فرم 7'     => 'contact form 7',
        'ویرایشگر کلاسیک'  => 'classic editor',
        'کلاسیک ادیتور'    => 'classic editor',
        'داپلیکیتور'       => 'duplicator',
        'اتو اپتیمایز'     => 'autoptimize',
        'آتو اپتیمایز'     => 'autoptimize',
        'ردیس'             => 'redis object cache',
        'لایت اسپید کش'    => 'litespeed cache',
        'لایت‌اسپید کش'    => 'litespeed cache',
    );
}

/**
 * Build multiple search terms so Persian and English names can find the same plugin.
 *
 * @param string $search Normalized search phrase.
 * @return array
 */
function ezd_build_repository_search_terms($search) {
    $terms      = array($search);
    $search_low = function_exists('mb_strtolower') ? mb_strtolower($search, 'UTF-8') : strtolower($search);

    foreach (ezd_repository_search_aliases() as $persian_name => $english_term) {
        $persian_low = function_exists('mb_strtolower') ? mb_strtolower($persian_name, 'UTF-8') : strtolower($persian_name);
        if ($search_low === $persian_low || false !== strpos($search_low, $persian_low)) {
            $terms[] = $english_term;
        }
    }

    $ascii_term = preg_replace('/[^a-z0-9._\- ]/i', '', $search);
    if (is_string($ascii_term) && '' !== trim($ascii_term)) {
        $terms[] = trim($ascii_term);
    }

    return array_values(array_unique(array_filter(array_map('trim', $terms))));
}

/**
 * Convert a WordPress.org API plugin object to safe AJAX data.
 *
 * @param object $plugin Plugin API result.
 * @param array  $installed Installed plugin index.
 * @return array|null
 */
function ezd_prepare_repository_plugin_result($plugin, $installed) {
    if (is_array($plugin)) {
        $plugin = (object) $plugin;
    }
    if (!is_object($plugin)) {
        return null;
    }

    $slug = isset($plugin->slug) ? sanitize_title($plugin->slug) : '';
    if (!$slug) {
        return null;
    }

    $installed_version = isset($installed[$slug]['version']) ? $installed[$slug]['version'] : '';
    $latest_version    = isset($plugin->version) ? sanitize_text_field($plugin->version) : '';
    $is_installed      = '' !== $installed_version;
    $update_available  = $is_installed && $latest_version && version_compare($latest_version, $installed_version, '>');
    $icon              = '';
    $icons             = isset($plugin->icons) ? (array) $plugin->icons : array();

    foreach (array('svg', '2x', '1x', 'default') as $icon_key) {
        if (!empty($icons[$icon_key])) {
            $icon = esc_url_raw($icons[$icon_key], array('http', 'https'));
            break;
        }
    }

    $download_url = isset($plugin->download_link) ? esc_url_raw($plugin->download_link, array('http', 'https')) : '';
    if (!$download_url || !wp_http_validate_url($download_url)) {
        $download_url = '';
    }

    return array(
        'name'              => isset($plugin->name) ? wp_strip_all_tags($plugin->name) : $slug,
        'slug'              => $slug,
        'version'           => $latest_version,
        'author'            => isset($plugin->author) ? wp_strip_all_tags($plugin->author) : '',
        'description'       => isset($plugin->short_description) ? wp_trim_words(wp_strip_all_tags($plugin->short_description), 25) : '',
        'icon'              => $icon,
        'rating'            => isset($plugin->rating) ? min(100, max(0, absint($plugin->rating))) : 0,
        'active_installs'   => isset($plugin->active_installs) ? absint($plugin->active_installs) : 0,
        'installed'         => $is_installed,
        'installed_version' => $installed_version,
        'update_available'  => $update_available,
        'download_url'      => $download_url,
    );
}

/**
 * Search public plugins from the WordPress.org repository.
 * Runs the original phrase, recognized Persian aliases, and exact slug lookups.
 *
 * @return void
 */
function ezd_ajax_search_repository_plugins() {
    ezd_verify_ajax_request('install_plugins');

    $search = isset($_POST['search']) ? ezd_normalize_repository_search(wp_unslash($_POST['search'])) : '';
    $page   = isset($_POST['page']) ? max(1, absint($_POST['page'])) : 1;

    if ((function_exists('mb_strlen') ? mb_strlen($search) : strlen($search)) < 2) {
        wp_send_json_error(array('message' => ezd_text('حداقل دو حرف برای جست‌وجو وارد کنید.', 'Enter at least two characters to search.')), 400);
    }

    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    $terms       = ezd_build_repository_search_terms($search);
    $installed   = ezd_get_installed_plugin_data();
    $merged      = array();
    $api_errors  = array();
    $api_success = false;
    $max_results = 18;

    foreach ($terms as $term) {
        $api = plugins_api(
            'query_plugins',
            array(
                'search'   => $term,
                'page'     => $page,
                'per_page' => 12,
                'locale'   => function_exists('get_user_locale') ? get_user_locale() : get_locale(),
                'fields'   => array(
                    'short_description' => true,
                    'icons'             => true,
                    'active_installs'   => true,
                    'rating'            => true,
                    'ratings'           => false,
                    'sections'          => false,
                    'downloaded'        => false,
                    'last_updated'      => true,
                    'download_link'     => true,
                ),
            )
        );

        if (is_wp_error($api)) {
            $api_errors[] = $api->get_error_message();
            continue;
        }

        $api_success = true;
        foreach ((array) (isset($api->plugins) ? $api->plugins : array()) as $plugin) {
            $prepared = ezd_prepare_repository_plugin_result($plugin, $installed);
            if (!$prepared || isset($merged[$prepared['slug']])) {
                continue;
            }
            $merged[$prepared['slug']] = $prepared;
            if (count($merged) >= $max_results) {
                break 2;
            }
        }
    }

    // Exact slug/name lookup makes searches such as "elementor" deterministic.
    foreach ($terms as $term) {
        if (count($merged) >= $max_results) {
            break;
        }

        $slug = sanitize_title($term);
        if (!$slug || isset($merged[$slug])) {
            continue;
        }

        $exact = plugins_api(
            'plugin_information',
            array(
                'slug'   => $slug,
                'fields' => array(
                    'sections'          => false,
                    'short_description' => true,
                    'icons'             => true,
                    'active_installs'   => true,
                    'rating'            => true,
                ),
            )
        );

        if (is_wp_error($exact)) {
            continue;
        }

        $prepared = ezd_prepare_repository_plugin_result($exact, $installed);
        if ($prepared) {
            // Exact matches appear before broad search results.
            $merged = array($prepared['slug'] => $prepared) + $merged;
        }
    }

    if (!$api_success && empty($merged)) {
        $message = !empty($api_errors)
            ? reset($api_errors)
            : ezd_text('دریافت اطلاعات مخزن وردپرس ناموفق بود.', 'Could not retrieve data from the WordPress.org repository.');
        wp_send_json_error(array('message' => $message), 502);
    }

    wp_send_json_success(
        array(
            'plugins' => array_values(array_slice($merged, 0, $max_results)),
            'page'    => $page,
            'pages'   => 1,
            'results' => count($merged),
            'terms'   => $terms,
        )
    );
}
add_action('wp_ajax_ezd_search_repository_plugins', 'ezd_ajax_search_repository_plugins');

/**
 * Return installed plugin versions keyed by WordPress.org-style slug.
 *
 * @return array
 */
function ezd_get_installed_plugin_data() {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    $data = array();
    foreach (get_plugins() as $plugin_file => $plugin_headers) {
        $directory = dirname($plugin_file);
        $slug      = '.' === $directory ? basename($plugin_file, '.php') : $directory;
        $slug      = sanitize_title($slug);

        if (!$slug) {
            continue;
        }

        $data[$slug] = array(
            'file'    => $plugin_file,
            'version' => isset($plugin_headers['Version']) ? sanitize_text_field($plugin_headers['Version']) : '',
            'active'  => is_plugin_active($plugin_file),
        );
    }

    return $data;
}

/**
 * Install or update a plugin from WordPress.org by slug.
 *
 * @return void
 */
function ezd_ajax_install_repository_plugin() {
    ezd_verify_ajax_request('install_plugins');

    $slug = isset($_POST['slug']) ? sanitize_title(wp_unslash($_POST['slug'])) : '';
    if (!$slug) {
        wp_send_json_error(array('message' => ezd_text('شناسه افزونه معتبر نیست.', 'The plugin slug is invalid.')), 400);
    }

    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    $installed_before = ezd_get_installed_plugin_data();
    $was_installed    = isset($installed_before[$slug]);
    $old_version      = $was_installed ? $installed_before[$slug]['version'] : '';

    $api = plugins_api(
        'plugin_information',
        array(
            'slug'   => $slug,
            'fields' => array(
                'sections' => false,
                'icons'    => false,
            ),
        )
    );

    if (is_wp_error($api)) {
        wp_send_json_error(array('message' => $api->get_error_message()), 502);
    }

    if (!is_object($api)) {
        wp_send_json_error(array('message' => ezd_text('پاسخ مخزن وردپرس معتبر نیست.', 'The WordPress.org repository returned an invalid response.')), 502);
    }

    $download_link = isset($api->download_link) ? esc_url_raw($api->download_link, array('http', 'https')) : '';
    if (!$download_link || !wp_http_validate_url($download_link)) {
        wp_send_json_error(array('message' => ezd_text('لینک دانلود افزونه از مخزن دریافت نشد.', 'The repository did not provide a valid download URL.')), 502);
    }

    $result = cpi_download_and_extract_plugin($download_link);
    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message(), 'code' => $result->get_error_code()), 500);
    }

    $new_version = isset($api->version) ? sanitize_text_field($api->version) : '';
    if ($was_installed) {
        $result['message'] = $old_version && $new_version && version_compare($new_version, $old_version, '>')
            ? sprintf(ezd_text('افزونه «%1$s» از نسخه %2$s به %3$s بروزرسانی شد.', 'Plugin “%1$s” was updated from %2$s to %3$s.'), wp_strip_all_tags($api->name), $old_version, $new_version)
            : sprintf(ezd_text('افزونه «%s» با موفقیت نصب مجدد شد.', 'Plugin “%s” was reinstalled successfully.'), wp_strip_all_tags($api->name));
    } else {
        $result['message'] = sprintf(ezd_text('افزونه «%s» از مخزن وردپرس نصب شد.', 'Plugin “%s” was installed from WordPress.org.'), wp_strip_all_tags($api->name));
    }

    $result['slug']      = $slug;
    $result['installed'] = true;
    $result['version']   = $new_version;

    wp_send_json_success($result);
}
add_action('wp_ajax_ezd_install_repository_plugin', 'ezd_ajax_install_repository_plugin');


/**
 * Return the current official WordPress.org download URL for a plugin slug.
 *
 * @return void
 */
function ezd_ajax_get_repository_plugin_url() {
    ezd_verify_ajax_request('install_plugins');

    $slug = isset($_POST['slug']) ? sanitize_title(wp_unslash($_POST['slug'])) : '';
    if (!$slug) {
        wp_send_json_error(array('message' => ezd_text('شناسه افزونه معتبر نیست.', 'The plugin slug is invalid.')), 400);
    }

    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    $api = plugins_api(
        'plugin_information',
        array(
            'slug'   => $slug,
            'fields' => array(
                'sections' => false,
                'icons'    => false,
            ),
        )
    );

    if (is_wp_error($api)) {
        wp_send_json_error(array('message' => $api->get_error_message()), 502);
    }

    $download_url = is_object($api) && isset($api->download_link)
        ? esc_url_raw($api->download_link, array('http', 'https'))
        : '';

    if (!$download_url || !wp_http_validate_url($download_url)) {
        wp_send_json_error(array('message' => ezd_text('لینک دانلود افزونه از مخزن دریافت نشد.', 'The repository did not provide a valid download URL.')), 502);
    }

    wp_send_json_success(
        array(
            'slug'         => $slug,
            'download_url' => $download_url,
        )
    );
}
add_action('wp_ajax_ezd_get_repository_plugin_url', 'ezd_ajax_get_repository_plugin_url');

/**
 * Return common Persian names mapped to WordPress.org theme search terms.
 *
 * @return array
 */
function ezd_repository_theme_aliases() {
    return array(
        'آسترا'             => 'astra',
        'استرا'             => 'astra',
        'هلو المنتور'       => 'hello elementor',
        'سلام المنتور'      => 'hello elementor',
        'هلو'               => 'hello elementor',
        'اوشن وردپرس'       => 'oceanwp',
        'اوشن'              => 'oceanwp',
        'اقیانوس وردپرس'    => 'oceanwp',
        'کادنس'             => 'kadence',
        'جنریت پرس'         => 'generatepress',
        'جنریت‌پرس'         => 'generatepress',
        'بلوکسی'            => 'blocksy',
        'نیو'               => 'neve',
        'نِو'               => 'neve',
        'زاکرا'             => 'zakra',
        'سیدنی'             => 'sydney',
        'هیستیا'            => 'hestia',
        'استورفرانت'        => 'storefront',
        'فروشگاه ووکامرس'   => 'storefront',
        'دو هزار و بیست و پنج' => 'twentytwentyfive',
    );
}

/**
 * Build Persian and English search terms for the theme repository.
 *
 * @param string $search Normalized search phrase.
 * @return array
 */
function ezd_build_repository_theme_search_terms($search) {
    $terms      = array($search);
    $search_low = function_exists('mb_strtolower') ? mb_strtolower($search, 'UTF-8') : strtolower($search);

    foreach (ezd_repository_theme_aliases() as $persian_name => $english_term) {
        $persian_low = function_exists('mb_strtolower') ? mb_strtolower($persian_name, 'UTF-8') : strtolower($persian_name);
        if ($search_low === $persian_low || false !== strpos($search_low, $persian_low)) {
            $terms[] = $english_term;
        }
    }

    $ascii_term = preg_replace('/[^a-z0-9._\- ]/i', '', $search);
    if (is_string($ascii_term) && '' !== trim($ascii_term)) {
        $terms[] = trim($ascii_term);
    }

    return array_values(array_unique(array_filter(array_map('trim', $terms))));
}

/**
 * Return installed themes keyed by stylesheet slug.
 *
 * @return array
 */
function ezd_get_installed_theme_data() {
    require_once ABSPATH . 'wp-admin/includes/theme.php';

    $data = array();
    foreach (wp_get_themes() as $stylesheet => $theme) {
        $slug = sanitize_title($stylesheet);
        if (!$slug || !is_object($theme)) {
            continue;
        }

        $data[$slug] = array(
            'version' => sanitize_text_field($theme->get('Version')),
            'name'    => sanitize_text_field($theme->get('Name')),
        );
    }

    return $data;
}

/**
 * Convert a WordPress.org API theme object to safe AJAX data.
 *
 * @param object $theme Theme API result.
 * @param array  $installed Installed theme index.
 * @return array|null
 */
function ezd_prepare_repository_theme_result($theme, $installed) {
    if (is_array($theme)) {
        $theme = (object) $theme;
    }
    if (!is_object($theme)) {
        return null;
    }

    $slug = isset($theme->slug) ? sanitize_title($theme->slug) : '';
    if (!$slug) {
        return null;
    }

    $installed_version = isset($installed[$slug]['version']) ? $installed[$slug]['version'] : '';
    $latest_version    = isset($theme->version) ? sanitize_text_field($theme->version) : '';
    $is_installed      = '' !== $installed_version;
    $update_available  = $is_installed && $latest_version && version_compare($latest_version, $installed_version, '>');
    $screenshot        = '';

    if (!empty($theme->screenshot_url)) {
        $screenshot = (string) $theme->screenshot_url;
    } elseif (!empty($theme->screenshot)) {
        $screenshot = (string) $theme->screenshot;
    }

    if (0 === strpos($screenshot, '//')) {
        $screenshot = 'https:' . $screenshot;
    }
    $screenshot = esc_url_raw($screenshot, array('http', 'https'));
    if (!$screenshot || !wp_http_validate_url($screenshot)) {
        $screenshot = '';
    }

    $download_url = isset($theme->download_link) ? esc_url_raw($theme->download_link, array('http', 'https')) : '';
    if (!$download_url || !wp_http_validate_url($download_url)) {
        $download_url = '';
    }

    return array(
        'name'              => isset($theme->name) ? wp_strip_all_tags($theme->name) : $slug,
        'slug'              => $slug,
        'version'           => $latest_version,
        'author'            => isset($theme->author) ? wp_strip_all_tags($theme->author) : '',
        'description'       => isset($theme->description) ? wp_trim_words(wp_strip_all_tags($theme->description), 25) : '',
        'screenshot'        => $screenshot,
        'rating'            => isset($theme->rating) ? min(100, max(0, absint($theme->rating))) : 0,
        'installed'         => $is_installed,
        'installed_version' => $installed_version,
        'update_available'  => $update_available,
        'download_url'      => $download_url,
    );
}

/**
 * Search public themes from the WordPress.org repository.
 *
 * @return void
 */
function ezd_ajax_search_repository_themes() {
    ezd_verify_ajax_request('install_themes');

    $search = isset($_POST['search']) ? ezd_normalize_repository_search(wp_unslash($_POST['search'])) : '';
    $page   = isset($_POST['page']) ? max(1, absint($_POST['page'])) : 1;

    if ((function_exists('mb_strlen') ? mb_strlen($search) : strlen($search)) < 2) {
        wp_send_json_error(array('message' => ezd_text('حداقل دو حرف برای جست‌وجوی قالب وارد کنید.', 'Enter at least two characters to search themes.')), 400);
    }

    require_once ABSPATH . 'wp-admin/includes/theme-install.php';
    require_once ABSPATH . 'wp-admin/includes/theme.php';

    $terms       = ezd_build_repository_theme_search_terms($search);
    $installed   = ezd_get_installed_theme_data();
    $merged      = array();
    $api_errors  = array();
    $api_success = false;
    $max_results = 18;
    $fields      = array(
        'description'    => true,
        'sections'       => false,
        'rating'         => true,
        'ratings'        => false,
        'downloaded'     => false,
        'download_link'  => true,
        'last_updated'   => true,
        'homepage'       => false,
        'tags'           => false,
        'template'       => false,
        'parent'         => false,
        'versions'       => false,
        'screenshot_url' => true,
    );

    foreach ($terms as $term) {
        $api = themes_api(
            'query_themes',
            array(
                'search'   => $term,
                'page'     => $page,
                'per_page' => 12,
                'fields'   => $fields,
            )
        );

        if (is_wp_error($api)) {
            $api_errors[] = $api->get_error_message();
            continue;
        }

        $api_success = true;
        foreach ((array) (isset($api->themes) ? $api->themes : array()) as $theme) {
            $prepared = ezd_prepare_repository_theme_result($theme, $installed);
            if (!$prepared || isset($merged[$prepared['slug']])) {
                continue;
            }
            $merged[$prepared['slug']] = $prepared;
            if (count($merged) >= $max_results) {
                break 2;
            }
        }
    }

    // Exact slug lookup makes searches such as "astra" deterministic.
    foreach ($terms as $term) {
        if (count($merged) >= $max_results) {
            break;
        }

        $slug = sanitize_title($term);
        if (!$slug || isset($merged[$slug])) {
            continue;
        }

        $exact = themes_api(
            'theme_information',
            array(
                'slug'   => $slug,
                'fields' => $fields,
            )
        );

        if (is_wp_error($exact)) {
            continue;
        }

        $prepared = ezd_prepare_repository_theme_result($exact, $installed);
        if ($prepared) {
            $merged = array($prepared['slug'] => $prepared) + $merged;
        }
    }

    if (!$api_success && empty($merged)) {
        $message = !empty($api_errors)
            ? reset($api_errors)
            : ezd_text('دریافت اطلاعات قالب‌ها از مخزن وردپرس ناموفق بود.', 'Could not retrieve themes from the WordPress.org repository.');
        wp_send_json_error(array('message' => $message), 502);
    }

    wp_send_json_success(
        array(
            'themes'  => array_values(array_slice($merged, 0, $max_results)),
            'page'    => $page,
            'pages'   => 1,
            'results' => count($merged),
            'terms'   => $terms,
        )
    );
}
add_action('wp_ajax_ezd_search_repository_themes', 'ezd_ajax_search_repository_themes');

/**
 * Install or update a theme from WordPress.org by slug.
 *
 * @return void
 */
function ezd_ajax_install_repository_theme() {
    ezd_verify_ajax_request('install_themes');

    $slug = isset($_POST['slug']) ? sanitize_title(wp_unslash($_POST['slug'])) : '';
    if (!$slug) {
        wp_send_json_error(array('message' => ezd_text('شناسه قالب معتبر نیست.', 'The theme slug is invalid.')), 400);
    }

    require_once ABSPATH . 'wp-admin/includes/theme-install.php';

    $installed_before = ezd_get_installed_theme_data();
    $was_installed    = isset($installed_before[$slug]);
    $old_version      = $was_installed ? $installed_before[$slug]['version'] : '';

    $api = themes_api(
        'theme_information',
        array(
            'slug'   => $slug,
            'fields' => array(
                'sections'      => false,
                'download_link' => true,
            ),
        )
    );

    if (is_wp_error($api)) {
        wp_send_json_error(array('message' => $api->get_error_message()), 502);
    }

    if (!is_object($api)) {
        wp_send_json_error(array('message' => ezd_text('پاسخ مخزن قالب‌های وردپرس معتبر نیست.', 'The WordPress.org theme repository returned an invalid response.')), 502);
    }

    $download_link = isset($api->download_link) ? esc_url_raw($api->download_link, array('http', 'https')) : '';
    if (!$download_link || !wp_http_validate_url($download_link)) {
        wp_send_json_error(array('message' => ezd_text('لینک دانلود قالب از مخزن دریافت نشد.', 'The repository did not provide a valid theme download URL.')), 502);
    }

    $result = cti_download_and_extract_theme($download_link);
    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message(), 'code' => $result->get_error_code()), 500);
    }

    $new_version = isset($api->version) ? sanitize_text_field($api->version) : '';
    $theme_name  = isset($api->name) ? wp_strip_all_tags($api->name) : $slug;

    if ($was_installed) {
        $result['message'] = $old_version && $new_version && version_compare($new_version, $old_version, '>')
            ? sprintf(ezd_text('قالب «%1$s» از نسخه %2$s به %3$s بروزرسانی شد.', 'Theme “%1$s” was updated from %2$s to %3$s.'), $theme_name, $old_version, $new_version)
            : sprintf(ezd_text('قالب «%s» با موفقیت نصب مجدد شد.', 'Theme “%s” was reinstalled successfully.'), $theme_name);
    } else {
        $result['message'] = sprintf(ezd_text('قالب «%s» از مخزن وردپرس نصب شد.', 'Theme “%s” was installed from WordPress.org.'), $theme_name);
    }

    $result['slug']      = $slug;
    $result['installed'] = true;
    $result['version']   = $new_version;

    wp_send_json_success($result);
}
add_action('wp_ajax_ezd_install_repository_theme', 'ezd_ajax_install_repository_theme');

/**
 * Return the current official WordPress.org download URL for a theme slug.
 *
 * @return void
 */
function ezd_ajax_get_repository_theme_url() {
    ezd_verify_ajax_request('install_themes');

    $slug = isset($_POST['slug']) ? sanitize_title(wp_unslash($_POST['slug'])) : '';
    if (!$slug) {
        wp_send_json_error(array('message' => ezd_text('شناسه قالب معتبر نیست.', 'The theme slug is invalid.')), 400);
    }

    require_once ABSPATH . 'wp-admin/includes/theme-install.php';

    $api = themes_api(
        'theme_information',
        array(
            'slug'   => $slug,
            'fields' => array(
                'sections'      => false,
                'download_link' => true,
            ),
        )
    );

    if (is_wp_error($api)) {
        wp_send_json_error(array('message' => $api->get_error_message()), 502);
    }

    $download_url = is_object($api) && isset($api->download_link)
        ? esc_url_raw($api->download_link, array('http', 'https'))
        : '';

    if (!$download_url || !wp_http_validate_url($download_url)) {
        wp_send_json_error(array('message' => ezd_text('لینک دانلود قالب از مخزن دریافت نشد.', 'The repository did not provide a valid theme download URL.')), 502);
    }

    wp_send_json_success(
        array(
            'slug'         => $slug,
            'download_url' => $download_url,
        )
    );
}
add_action('wp_ajax_ezd_get_repository_theme_url', 'ezd_ajax_get_repository_theme_url');

/**
 * Build and return a portable EZ Downloader JSON package.
 *
 * @return void
 */
function ezd_ajax_export_package() {
    ezd_verify_ajax_request('manage_options');

    $plugin_urls = isset($_POST['plugin_urls']) ? ezd_sanitize_package_urls($_POST['plugin_urls']) : array();
    $theme_url   = isset($_POST['theme_url']) ? esc_url_raw(wp_unslash($_POST['theme_url']), array('http', 'https')) : '';

    if ($theme_url && !wp_http_validate_url($theme_url)) {
        $theme_url = '';
    }

    if (!$plugin_urls && !$theme_url) {
        wp_send_json_error(array('message' => ezd_text('برای برون‌بری، حداقل یک لینک وارد کنید.', 'Enter at least one URL before exporting.')), 400);
    }

    $package = array(
        'format'     => 'ez-downloader-package',
        'version'    => 1,
        'created_at' => gmdate('c'),
        'generator'  => 'EZ Downloader ' . EZD_VERSION,
        'plugins'    => array_map(
            static function ($url) {
                return array('url' => $url);
            },
            $plugin_urls
        ),
        'theme'      => $theme_url ? array('url' => $theme_url) : null,
    );

    wp_send_json_success(
        array(
            'filename' => 'ez-downloader-package-' . gmdate('Y-m-d-His') . '.json',
            'content'  => wp_json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'message'  => ezd_text('پکیج لینک‌ها آماده شد.', 'The link package is ready.'),
        )
    );
}
add_action('wp_ajax_ezd_export_package', 'ezd_ajax_export_package');

/**
 * Parse an uploaded EZ Downloader JSON package and return its URLs.
 *
 * @return void
 */
function ezd_ajax_import_package() {
    ezd_verify_ajax_request('manage_options');

    if (empty($_FILES['package_file']) || !is_array($_FILES['package_file'])) {
        wp_send_json_error(array('message' => ezd_text('فایل پکیج ارسال نشده است.', 'No package file was uploaded.')), 400);
    }

    $file = $_FILES['package_file'];
    if (!empty($file['error']) || empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        wp_send_json_error(array('message' => ezd_text('بارگذاری فایل پکیج ناموفق بود.', 'The package upload failed.')), 400);
    }

    if (!empty($file['size']) && (int) $file['size'] > 1024 * 1024) {
        wp_send_json_error(array('message' => ezd_text('حجم فایل پکیج نباید بیشتر از یک مگابایت باشد.', 'The package file must be smaller than 1 MB.')), 413);
    }

    $filename = isset($file['name']) ? sanitize_file_name($file['name']) : '';
    if ('json' !== strtolower(pathinfo($filename, PATHINFO_EXTENSION))) {
        wp_send_json_error(array('message' => ezd_text('فقط فایل JSON پکیج قابل درون‌ریزی است.', 'Only a JSON package file can be imported.')), 400);
    }

    $contents = file_get_contents($file['tmp_name']); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
    if (false === $contents || '' === trim($contents)) {
        wp_send_json_error(array('message' => ezd_text('فایل پکیج خالی است.', 'The package file is empty.')), 400);
    }

    $package = json_decode($contents, true);
    if (!is_array($package) || empty($package['format']) || 'ez-downloader-package' !== $package['format']) {
        wp_send_json_error(array('message' => ezd_text('ساختار فایل پکیج معتبر نیست.', 'The package structure is invalid.')), 400);
    }

    $raw_plugins = array();
    foreach ((array) ($package['plugins'] ?? array()) as $plugin) {
        $raw_plugins[] = is_array($plugin) && isset($plugin['url']) ? $plugin['url'] : $plugin;
    }
    $plugin_urls = ezd_sanitize_package_urls($raw_plugins);

    $raw_theme = $package['theme'] ?? '';
    $theme_url = is_array($raw_theme) && isset($raw_theme['url']) ? $raw_theme['url'] : $raw_theme;
    $theme_url = esc_url_raw((string) $theme_url, array('http', 'https'));
    if ($theme_url && !wp_http_validate_url($theme_url)) {
        $theme_url = '';
    }

    if (!$plugin_urls && !$theme_url) {
        wp_send_json_error(array('message' => ezd_text('هیچ لینک معتبری در پکیج پیدا نشد.', 'The package contains no valid URLs.')), 400);
    }

    wp_send_json_success(
        array(
            'plugin_urls' => $plugin_urls,
            'theme_url'   => $theme_url,
            'message'     => ezd_text('پکیج با موفقیت درون‌ریزی شد.', 'The package was imported successfully.'),
        )
    );
}
add_action('wp_ajax_ezd_import_package', 'ezd_ajax_import_package');
