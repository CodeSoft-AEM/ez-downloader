<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register EZ Downloader admin menu.
 *
 * @return void
 */
function cpi_add_admin_menu() {
    $page_title = ezd_text('دانلودر آسان، پلاگین و قالب', 'EZ Downloader – Plugin & Theme');
    $menu_title = ezd_text('دانلودر آسان', 'EZ Downloader');

    add_menu_page(
        $page_title,
        $menu_title,
        'manage_options',
        'custom-plugin-installer',
        'cpi_settings_page',
        'dashicons-download',
        65
    );
}
add_action('admin_menu', 'cpi_add_admin_menu');

/**
 * Render a help popover control.
 *
 * @param string $id Help control ID.
 * @param string $text Help text.
 * @return void
 */
function ezd_render_help_control($id, $text) {
    ?>
    <span class="ezd-help-control">
        <button type="button" class="ezd-help-toggle" aria-expanded="false" aria-controls="<?php echo esc_attr($id); ?>" aria-label="<?php echo esc_attr(ezd_text('راهنما', 'Help')); ?>">?</button>
        <span class="ezd-help-popover" id="<?php echo esc_attr($id); ?>" role="tooltip" hidden><?php echo esc_html($text); ?></span>
    </span>
    <?php
}

/**
 * Render the plugin page.
 *
 * @return void
 */
function cpi_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html(ezd_text('شما اجازه دسترسی به این صفحه را ندارید.', 'You are not allowed to access this page.')));
    }

    $page_name = ezd_text('دانلودر آسان، پلاگین و قالب', 'EZ Downloader – Plugin & Theme');
    ?>
    <div class="wrap ezd-admin <?php echo is_rtl() ? 'ezd-is-rtl' : 'ezd-is-ltr'; ?>" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
        <header class="ezd-page-header">
            <div>
                <h1><?php echo esc_html($page_name); ?></h1>
                <p><?php echo esc_html(ezd_text('چند افزونه و یک قالب را از لینک مستقیم یا مخزن وردپرس، بدون رفرش صفحه نصب کنید.', 'Install multiple plugins and one theme from direct links or WordPress.org without reloading the page.')); ?></p>
            </div>
            <a href="https://wordpress.org/plugins/ez-downloader/" class="ezd-plugin-logo" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr(ezd_text('صفحه افزونه در WordPress.org', 'Plugin page on WordPress.org')); ?>">
                <img src="https://ps.w.org/ez-downloader/assets/icon-128%C3%97128.jpg" width="128" height="128" alt="EZ Downloader">
            </a>
        </header>

        <div class="ezd-update-notice" role="note">
            <span class="dashicons dashicons-update" aria-hidden="true"></span>
            <strong><?php echo esc_html(ezd_text('هر افزونه یا قالبی که هم‌اکنون نصب باشد، با نسخه واردشده بروزرسانی می‌شود.', 'Any plugin or theme that is already installed will be updated with the supplied package.')); ?></strong>
        </div>

        <div class="ezd-form-grid ezd-workspace-grid">
            <div class="ezd-workspace-column">
                <section class="ezd-install-card">
                    <header class="ezd-install-card__header">
                        <div>
                            <h2><?php echo esc_html(ezd_text('نصب‌کننده افزونه‌ها', 'Plugin installer')); ?></h2>
                            <p><?php echo esc_html(ezd_text('برای افزودن لینک‌های بیشتر روی دکمه + بزنید.', 'Use the + button to add more plugin URLs.')); ?></p>
                        </div>
                        <button type="button" class="ezd-icon-button" id="ezd-add-plugin" aria-label="<?php echo esc_attr(ezd_text('افزودن افزونه', 'Add plugin')); ?>" title="<?php echo esc_attr(ezd_text('افزودن افزونه', 'Add plugin')); ?>">
                            <span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
                        </button>
                    </header>

                    <div class="ezd-install-card__body">
                        <div id="ezd-plugin-fields" class="ezd-plugin-fields">
                            <div class="ezd-url-row">
                                <label class="screen-reader-text" for="ezd-plugin-url-1"><?php echo esc_html(ezd_text('لینک افزونه', 'Plugin URL')); ?></label>
                                <input type="url" id="ezd-plugin-url-1" class="ezd-input ezd-plugin-url" form="ezd-installer-form" inputmode="url" dir="ltr" placeholder="https://example.com/plugin.zip">
                                <button type="button" class="ezd-remove-button" aria-label="<?php echo esc_attr(ezd_text('حذف این افزونه', 'Remove this plugin')); ?>" title="<?php echo esc_attr(ezd_text('حذف', 'Remove')); ?>" disabled>
                                    <span class="dashicons dashicons-trash" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ezd-repository-card ezd-repository-card--plugin" aria-labelledby="ezd-plugin-repository-title">
            <header class="ezd-repository-card__header">
                <div>
                    <h2 id="ezd-plugin-repository-title"><?php echo esc_html(ezd_text('نصب افزونه از مخزن وردپرس', 'Install a plugin from WordPress.org')); ?></h2>
                    <p><?php echo esc_html(ezd_text('نام فارسی یا انگلیسی افزونه را جست‌وجو کنید؛ سپس آن را نصب کنید یا لینک دانلودش را به لیست افزونه‌ها بیفزایید.', 'Search by a Persian or English plugin name, then install it or add its download URL to the plugin list.')); ?></p>
                </div>
                <span class="dashicons dashicons-admin-plugins" aria-hidden="true"></span>
            </header>
            <form id="ezd-plugin-repository-search-form" class="ezd-repository-search" novalidate>
                <label class="screen-reader-text" for="ezd-plugin-repository-search"><?php echo esc_html(ezd_text('جست‌وجوی افزونه', 'Search plugins')); ?></label>
                <input type="search" id="ezd-plugin-repository-search" class="ezd-input" minlength="2" autocomplete="off" placeholder="<?php echo esc_attr(ezd_text('مثلاً: المنتور، Elementor، ووکامرس یا WooCommerce', 'For example: WooCommerce or Elementor')); ?>">
                <button type="submit" class="button button-primary" id="ezd-plugin-repository-search-button">
                    <span class="dashicons dashicons-search" aria-hidden="true"></span>
                    <span><?php echo esc_html(ezd_text('جست‌وجوی افزونه', 'Search plugins')); ?></span>
                </button>
            </form>
            <div id="ezd-plugin-repository-status" class="ezd-inline-status" hidden aria-live="polite"></div>
            <div id="ezd-plugin-repository-results" class="ezd-repository-results" aria-live="polite"></div>
        </section>
            </div>

            <div class="ezd-workspace-column">
                <section class="ezd-install-card ezd-install-card--theme">
                    <header class="ezd-install-card__header">
                        <div>
                            <h2><?php echo esc_html(ezd_text('نصب‌کننده قالب', 'Theme installer')); ?></h2>
                            <p><?php echo esc_html(ezd_text('لینک مستقیم ZIP قالب را وارد کنید یا آن را از نتایج جست‌وجوی قالب اضافه کنید.', 'Enter a direct theme ZIP URL or add one from the theme search results.')); ?></p>
                        </div>
                        <span class="dashicons dashicons-admin-appearance ezd-card-icon" aria-hidden="true"></span>
                    </header>

                    <div class="ezd-install-card__body">
                        <div class="ezd-url-row ezd-url-row--single">
                            <label class="screen-reader-text" for="ezd-theme-url"><?php echo esc_html(ezd_text('لینک قالب', 'Theme URL')); ?></label>
                            <input type="url" id="ezd-theme-url" class="ezd-input" form="ezd-installer-form" inputmode="url" dir="ltr" placeholder="https://example.com/theme.zip">
                        </div>
                    </div>
                </section>

                <section class="ezd-repository-card ezd-repository-card--theme" aria-labelledby="ezd-theme-repository-title">
            <header class="ezd-repository-card__header">
                <div>
                    <h2 id="ezd-theme-repository-title"><?php echo esc_html(ezd_text('نصب قالب از مخزن وردپرس', 'Install a theme from WordPress.org')); ?></h2>
                    <p><?php echo esc_html(ezd_text('قالب را در یک فرم مستقل جست‌وجو کنید؛ سپس مستقیم نصب کنید یا لینک رسمی آن را داخل نصب‌کننده قالب قرار دهید.', 'Search themes independently, then install one directly or add its official URL to the theme installer.')); ?></p>
                </div>
                <span class="dashicons dashicons-admin-appearance" aria-hidden="true"></span>
            </header>
            <form id="ezd-theme-repository-search-form" class="ezd-repository-search" novalidate>
                <label class="screen-reader-text" for="ezd-theme-repository-search"><?php echo esc_html(ezd_text('جست‌وجوی قالب', 'Search themes')); ?></label>
                <input type="search" id="ezd-theme-repository-search" class="ezd-input" minlength="2" autocomplete="off" placeholder="<?php echo esc_attr(ezd_text('مثلاً: آسترا، Astra، هلو المنتور یا Hello Elementor', 'For example: Astra or Hello Elementor')); ?>">
                <button type="submit" class="button button-primary" id="ezd-theme-repository-search-button">
                    <span class="dashicons dashicons-search" aria-hidden="true"></span>
                    <span><?php echo esc_html(ezd_text('جست‌وجوی قالب', 'Search themes')); ?></span>
                </button>
            </form>
            <div id="ezd-theme-repository-status" class="ezd-inline-status" hidden aria-live="polite"></div>
            <div id="ezd-theme-repository-results" class="ezd-repository-results" aria-live="polite"></div>
        </section>
            </div>
        </div>

        <form id="ezd-installer-form" class="ezd-installer ezd-installer-actions" novalidate>

            <div class="ezd-main-actions">
                <button type="submit" class="button button-primary button-hero" id="ezd-install-all">
                    <span class="dashicons dashicons-download" aria-hidden="true"></span>
                    <span class="ezd-button-text"><?php echo esc_html(ezd_text('نصب همه موارد', 'Install all')); ?></span>
                </button>

                <span class="ezd-action-with-help">
                    <button type="button" class="button button-secondary" id="ezd-import-package">
                        <span class="dashicons dashicons-upload" aria-hidden="true"></span>
                        <span class="ezd-button-text"><?php echo esc_html(ezd_text('درون‌ریزی پکیج', 'Import package')); ?></span>
                    </button>
                    <?php
                    ezd_render_help_control(
                        'ezd-import-help',
                        ezd_text(
                            'با درون‌ریزی فایل پکیج EZ Downloader، لینک‌های ذخیره‌شده افزونه‌ها و قالب به‌صورت خودکار در این بخش قرار می‌گیرند. سپس می‌توانید همه افزونه‌ها و قالب را یکجا نصب یا بروزرسانی کنید و نیازی به واردکردن تک‌تک لینک‌ها نیست.',
                            'Import an EZ Downloader package to load its saved plugin and theme URLs automatically. You can then install or update the entire package without entering every URL separately.'
                        )
                    );
                    ?>
                    <input type="file" id="ezd-package-file" accept="application/json,.json" hidden>
                </span>

                <span class="ezd-action-with-help">
                    <button type="button" class="button button-secondary" id="ezd-export-package">
                        <span class="dashicons dashicons-download" aria-hidden="true"></span>
                        <span class="ezd-button-text"><?php echo esc_html(ezd_text('برون‌بری پکیج', 'Export package')); ?></span>
                    </button>
                    <?php
                    ezd_render_help_control(
                        'ezd-export-help',
                        ezd_text(
                            'شما با دریافت این فایل، لینک‌های واردشده فعلی در بخش افزونه‌ها و قالب EZ Downloader را ذخیره می‌کنید. سپس با درون‌ریزی این فایل در EZ Downloader هر سایت، می‌توانید افزونه‌ها و قالب را به‌صورت پکیج نصب کنید و نیازی به واردکردن تک‌تک لینک‌ها نیست؛ مواردی که از قبل نصب باشند بروزرسانی می‌شوند.',
                            'This file saves the plugin and theme URLs currently entered in EZ Downloader. Import it into EZ Downloader on any site to install the items as one package without re-entering each URL; items already installed will be updated.'
                        )
                    );
                    ?>
                </span>

                <span class="ezd-action-hint"><?php echo esc_html(ezd_text('موارد به‌ترتیب و بدون رفرش صفحه نصب می‌شوند.', 'Items are processed sequentially without reloading the page.')); ?></span>
            </div>

            <div id="ezd-package-status" class="ezd-inline-status" hidden aria-live="polite"></div>

            <section id="ezd-progress-panel" class="ezd-progress-panel" hidden aria-live="polite">
                <div class="ezd-progress-heading">
                    <h2><?php echo esc_html(ezd_text('وضعیت نصب', 'Installation status')); ?></h2>
                    <span id="ezd-progress-summary"></span>
                </div>
                <div class="ezd-progress-track" aria-hidden="true">
                    <span id="ezd-progress-bar"></span>
                </div>
                <div id="ezd-install-results" class="ezd-install-results"></div>
            </section>
        </form>



        <details class="ezd-guide-accordion">
            <summary>
                <span class="ezd-guide-summary__icon dashicons dashicons-editor-help" aria-hidden="true"></span>
                <span><?php echo esc_html(ezd_text('راهنمای قدم‌به‌قدم استفاده از افزونه', 'Step-by-step plugin guide')); ?></span>
                <span class="ezd-guide-summary__arrow dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span>
            </summary>
            <div class="ezd-guide-content">
                <ol class="ezd-guide-steps">
                    <li>
                        <strong><?php echo esc_html(ezd_text('روش نصب افزونه را انتخاب کنید', 'Choose how to install plugins')); ?></strong>
                        <p><?php echo esc_html(ezd_text('می‌توانید لینک مستقیم فایل ZIP را در نصب‌کننده افزونه وارد کنید یا از باکس جست‌وجوی زیر آن، افزونه موردنظر را در مخزن WordPress.org پیدا کنید.', 'Enter a direct plugin ZIP URL, or use the WordPress.org search box below the plugin installer.')); ?></p>
                    </li>
                    <li>
                        <strong><?php echo esc_html(ezd_text('افزونه‌های بیشتری اضافه کنید', 'Add more plugins')); ?></strong>
                        <p><?php echo esc_html(ezd_text('برای نصب چند افزونه، روی دکمه + بزنید و لینک هر افزونه را در یک فیلد جداگانه قرار دهید. در نتایج مخزن نیز می‌توانید گزینه افزودن به لیست افزونه‌ها را انتخاب کنید.', 'Use the + button to add one field per plugin. Repository results can also be added directly to the plugin list.')); ?></p>
                    </li>
                    <li>
                        <strong><?php echo esc_html(ezd_text('قالب را وارد یا جست‌وجو کنید', 'Enter or search for a theme')); ?></strong>
                        <p><?php echo esc_html(ezd_text('لینک مستقیم ZIP قالب را در نصب‌کننده قالب وارد کنید یا از باکس جست‌وجوی زیر آن، قالب را از مخزن وردپرس انتخاب کنید. در هر پکیج فقط یک قالب نصب می‌شود.', 'Enter a direct theme ZIP URL, or search WordPress.org below the theme installer. Each package supports one theme.')); ?></p>
                    </li>
                    <li>
                        <strong><?php echo esc_html(ezd_text('نصب همه موارد را اجرا کنید', 'Install all selected items')); ?></strong>
                        <p><?php echo esc_html(ezd_text('روی «نصب همه موارد» کلیک کنید. افزونه‌ها و قالب به‌ترتیب و با AJAX نصب می‌شوند و وضعیت هر مورد بدون رفرش صفحه نمایش داده می‌شود.', 'Click “Install all”. Plugins and the theme are processed sequentially with AJAX, and each status appears without reloading the page.')); ?></p>
                    </li>
                    <li>
                        <strong><?php echo esc_html(ezd_text('پکیج خود را ذخیره یا منتقل کنید', 'Save or move your package')); ?></strong>
                        <p><?php echo esc_html(ezd_text('با برون‌بری، لینک‌های فعلی را در یک فایل JSON ذخیره کنید. سپس همان فایل را در EZ Downloader سایت دیگری درون‌ریزی کنید تا فهرست افزونه‌ها و قالب خودکار آماده شود.', 'Export the current URLs to a JSON file, then import that file into EZ Downloader on another site to restore the plugin and theme list automatically.')); ?></p>
                    </li>
                </ol>
                <div class="ezd-guide-note">
                    <span class="dashicons dashicons-update" aria-hidden="true"></span>
                    <p><?php echo esc_html(ezd_text('اگر افزونه یا قالب از قبل نصب باشد، نسخه واردشده جایگزین یا بروزرسانی می‌شود. پیش از بروزرسانی فایل‌های سفارشی، از سایت نسخه پشتیبان تهیه کنید.', 'If an item is already installed, the supplied version replaces or updates it. Back up the site before updating customized files.')); ?></p>
                </div>
            </div>
        </details>

        <section class="ezd-help-copy">
            <h2><?php echo esc_html(ezd_text('روش دستیابی به لینک‌ها', 'Using direct links')); ?></h2>
            <p><?php echo esc_html(ezd_text('لینک واردشده باید مستقیماً فایل ZIP افزونه یا قالب را دانلود کند. لینک‌های صفحه محصول یا لینک‌هایی که نیاز به ورود دارند ممکن است قابل نصب نباشند.', 'Each URL must download a plugin or theme ZIP file directly. Product-page URLs and links that require authentication may not work.')); ?></p>
        </section>

        <?php require EZD_DIR . 'inc/link-box.php'; ?>

        <p class="ezd-review">
            <?php echo esc_html(ezd_text('برای حمایت از افزونه، می‌توانید در', 'To support the plugin, you can leave a review on the')); ?>
            <a href="https://wordpress.org/plugins/ez-downloader/#reviews" target="_blank" rel="noopener noreferrer"><?php echo esc_html(ezd_text('صفحه دیدگاه‌ها', 'review page')); ?></a>.
        </p>
        <p class="ezd-copyright"><a href="https://codsoft.ir/" target="_blank" rel="noopener noreferrer">Powered by CodeSoft</a></p>
    </div>
    <?php
}
