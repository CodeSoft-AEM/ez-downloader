<?php
// امنیت
if (!defined('ABSPATH')) { exit; }

/* ============ منو ادمین ============ */
function cpi_add_admin_menu() {
    add_menu_page(
        'EZ-Downloader',
        'EZ-Downloader',
        'manage_options',
        'custom-plugin-installer',
        'cpi_settings_page',
        'dashicons-download',
        65
    );
}
add_action('admin_menu', 'cpi_add_admin_menu');

/* ============ صفحه تنظیمات ============ */
function cpi_settings_page() {
    $notice_id = 'ezd';

    /* پردازش فرم‌ها (قبل از HTML برای نمایش درست نوتیفیکیشن‌ها) */
    if (isset($_POST['submit']) && isset($_POST['cpi_nonce']) && wp_verify_nonce($_POST['cpi_nonce'], 'cpi_download_file')) {
        if (!current_user_can('install_plugins')) {
            add_settings_error($notice_id, 'perm_plugin', 'شما مجوز نصب افزونه را ندارید.', 'error');
        } else {
            $plugin_url = isset($_POST['plugin_url']) ? esc_url_raw(wp_unslash($_POST['plugin_url'])) : '';
            if ($plugin_url === '') {
                add_settings_error($notice_id, 'plugin_empty', 'لطفاً لینک پلاگین را وارد کنید.', 'error');
            } else {
                // جلوگیری از پیامِ تکراری: خروجی تابع را می‌بلعیم
                ob_start();
                $result = function_exists('cpi_download_and_extract_plugin') ? cpi_download_and_extract_plugin($plugin_url) : true;
                ob_end_clean();

                if (is_wp_error($result)) {
                    add_settings_error($notice_id, 'plugin_err', esc_html($result->get_error_message()), 'error');
                } else {
                    add_settings_error($notice_id, 'plugin_ok', 'افزونه با موفقیت نصب شد.', 'updated');
                }
            }
        }
    }

    if (isset($_POST['install_theme']) && isset($_POST['cti_nonce']) && wp_verify_nonce($_POST['cti_nonce'], 'cti_download_file')) {
        if (!current_user_can('install_themes')) {
            add_settings_error($notice_id, 'perm_theme', 'شما مجوز نصب قالب را ندارید.', 'error');
        } else {
            $theme_url = isset($_POST['theme_url']) ? esc_url_raw(wp_unslash($_POST['theme_url'])) : '';
            if ($theme_url === '') {
                add_settings_error($notice_id, 'theme_empty', 'لطفاً لینک قالب را وارد کنید.', 'error');
            } else {
                // جلوگیری از پیامِ تکراری: خروجی تابع را می‌بلعیم
                ob_start();
                $result = function_exists('cti_download_and_extract_theme') ? cti_download_and_extract_theme($theme_url) : true;
                ob_end_clean();

                if (is_wp_error($result)) {
                    add_settings_error($notice_id, 'theme_err', esc_html($result->get_error_message()), 'error');
                } else {
                    add_settings_error($notice_id, 'theme_ok', 'قالب با موفقیت نصب شد.', 'updated');
                }
            }
        }
    }
    ?>

    <style>
      /* فیکس فوتر ادمین فقط در همین صفحه (بدون وابستگی خارجی) */
      #wpfooter{ position: static !important; margin-top: 24px !important; }
      #wpfooter #footer-left, #wpfooter #footer-upgrade{ display: none !important; }

      /* پایه‌ها */
      .ezd-admin{direction:rtl;font-family:"Vazirmatn",ui-sans-serif,system-ui,-apple-system;color:#111827}
      .ezd-admin h1{display:flex;align-items:center;gap:12px;margin-bottom:8px}
      .ezd-admin .plugin-logo img{width:48px;height:48px;border-radius:12px;vertical-align:middle}

      /* گرید دو ستونه (موبایل تکی) */
      .ezd-form-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:18px;margin-top:14px}
      .ezd-card-form{grid-column:span 6;background:#f7f9fc;border:1px solid #e5e7eb;border-radius:16px;box-shadow:0 6px 20px rgba(0,0,0,.06);overflow:hidden;display:flex;flex-direction:column;min-height:100%}
      @media (max-width:960px){.ezd-card-form{grid-column:1/-1}}

      /* سربرگ هر کارت */
      .ezd-card-head{padding:14px 16px;border-bottom:1px solid #e5e7eb;background:linear-gradient(180deg,rgba(59,130,246,.12),transparent 85%);border-top:3px solid #3b82f6}
      .ezd-card-head.-green{background:linear-gradient(180deg,rgba(16,185,129,.14),transparent 85%);border-top-color:#10b981}
      .ezd-card-title{margin:0;font-weight:800;font-size:16px}

      /* فیلدها */
      .ezd-fields{padding:16px;display:grid;gap:12px;align-content:start}
      .ezd-field label{display:block;font-size:13px;font-weight:700;margin-bottom:6px;color:#374151}
      .ezd-input{width:100%;max-width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:10px;background:#fff;outline:none;transition:border-color .15s ease, box-shadow .15s ease}
      .ezd-input:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.15)}

      /* دکمه‌ها (بدون تکیه بر CSS خارجی) */
      .ezd-actions{display:flex;gap:10px;margin-top:2px}
      .ezd-btn{appearance:none;-webkit-appearance:none;border:0;cursor:pointer;height:38px;padding:0 16px;border-radius:10px;font-weight:700;line-height:38px;display:inline-flex;align-items:center;justify-content:center;transition:transform .12s ease, filter .12s ease, box-shadow .12s ease}
      .ezd-btn:active{transform:translateY(1px)}
      .ezd-btn.-primary{background:#3b82f6;color:#fff;box-shadow:0 6px 16px rgba(59,130,246,.25)}
      .ezd-btn.-primary:hover{filter:brightness(.95)}
      .ezd-btn.-success{background:#10b981;color:#fff;box-shadow:0 6px 16px rgba(16,185,129,.25)}
      .ezd-btn.-success:hover{filter:brightness(.96)}
.title-dis {font-family:Vazirmatn!important;}
      /* بخش‌های پایین */
      .ezd-divider{margin:24px 0}
      .review{font-size:13px}
      .copy-right{font-weight:800;margin:0}
    </style>

    <div class="wrap ezd-admin">
      <h1>
        EZ-Downloader
        <a href="https://wordpress.org/plugins/ez-downloader/" class="plugin-logo" target="_blank" rel="noopener noreferrer">
          <img src="https://ps.w.org/ez-downloader/assets/icon-128%C3%97128.jpg" alt="EZ-Downloader">
        </a>
      </h1>

      <?php settings_errors($notice_id); ?>

      <div class="ezd-form-grid">
        <!-- نصب‌کننده پلاگین -->
        <section class="ezd-card-form">
          <header class="ezd-card-head">
            <h2 class="ezd-card-title">نصب کننده پلاگین</h2>
          </header>
          <form method="post" action="">
            <?php wp_nonce_field('cpi_download_file', 'cpi_nonce'); ?>
            <div class="ezd-fields">
              <div class="ezd-field">
                <label for="plugin_url">لینک پلاگین (فایل ZIP)</label>
                <input type="url" name="plugin_url" id="plugin_url" class="ezd-input" placeholder="https://example.com/plugin.zip" required>
              </div>
              <div class="ezd-actions">
                <button type="submit" name="submit" class="ezd-btn -primary">دانلود پلاگین</button>
              </div>
            </div>
          </form>
        </section>

        <!-- نصب‌کننده قالب -->
        <section class="ezd-card-form">
          <header class="ezd-card-head -green">
            <h2 class="ezd-card-title">نصب کننده قالب</h2>
          </header>
          <form method="post" action="">
            <?php wp_nonce_field('cti_download_file', 'cti_nonce'); ?>
            <div class="ezd-fields">
              <div class="ezd-field">
                <label for="theme_url">لینک قالب (فایل ZIP)</label>
                <input type="url" name="theme_url" id="theme_url" class="ezd-input" placeholder="https://example.com/theme.zip" required>
              </div>
              <div class="ezd-actions">
                <button type="submit" name="install_theme" class="ezd-btn -success">دانلود قالب</button>
              </div>
            </div>
          </form>
        </section>
      </div>

      <hr class="ezd-divider">
      <h3 class="title-dis">روش دستیابی به لینک ها</h3>
      <p>پیشنهاد ما برای دستیابی به لینک های دانلود پلاگین و قالب ها سایت پلاگین یاب هستش</p>
	  <p>همچنین میتوانید با استفاده از نسخه EZ-Downloader Pro مانند مخزن وردپرس پلاگین ها و قالب های پرمیوم را رایگان دانلود کنید ، لینک مخزن وردپرس VPS 7 در جداول زیر قرار دارد</p>

      <?php include("link-box.php"); ?>

      <p class="review" style="text-align:center;margin-top:18px">
        برای حمایت از ما از
        <a href="https://wordpress.org/plugins/ez-downloader/#reviews" target="_blank" rel="noopener noreferrer">این لینک</a>
        نظر خود را درباره این پلاگین بنویسید و اگر دوست داشتید امتیاز ۵ ستاره بدهید.
      </p>
      <h2 class="copy-right" style="text-align:center">Power By Abolfazl Edalati</h2>
    </div>

    <?php
}
