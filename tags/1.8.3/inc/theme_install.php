<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Install or replace a theme from a remote ZIP package.
 *
 * The package is downloaded first so redirects and remote HTTP errors are
 * reported clearly before WordPress starts writing to the themes directory.
 *
 * @param string $url Direct URL to the theme ZIP package.
 * @return array|WP_Error
 */
function cti_download_and_extract_theme($url) {
    if (!current_user_can('install_themes')) {
        return new WP_Error('ezd_theme_permission', ezd_text('شما مجوز نصب قالب را ندارید.', 'You are not allowed to install themes.'));
    }

    $url = esc_url_raw($url, array('http', 'https'));
    if (!$url || !wp_http_validate_url($url)) {
        return new WP_Error('ezd_invalid_theme_url', ezd_text('لینک قالب معتبر نیست.', 'The theme URL is invalid.'));
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/theme.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

    // Download separately to handle redirects and return the actual HTTP error.
    $temporary_package = download_url($url, 300);
    if (is_wp_error($temporary_package)) {
        return new WP_Error(
            'ezd_theme_download_failed',
            sprintf(
                ezd_text('دانلود فایل قالب ناموفق بود: %s', 'The theme package could not be downloaded: %s'),
                $temporary_package->get_error_message()
            ),
            $temporary_package->get_error_data()
        );
    }

    $skin     = new Automatic_Upgrader_Skin();
    $upgrader = new Theme_Upgrader($skin);
    $result   = $upgrader->install(
        $temporary_package,
        array(
            'clear_update_cache' => true,
            'overwrite_package'  => true,
        )
    );

    // The upgrader normally removes the temporary package; clean up if it did not.
    if (is_string($temporary_package) && file_exists($temporary_package)) {
        wp_delete_file($temporary_package);
    }

    if (is_wp_error($result)) {
        return $result;
    }

    if (is_wp_error($skin->result)) {
        return $skin->result;
    }

    if (is_wp_error($upgrader->result)) {
        return $upgrader->result;
    }

    if (!$result || !is_array($upgrader->result)) {
        return new WP_Error(
            'ezd_theme_install_failed',
            ezd_text(
                'نصب قالب انجام نشد. مطمئن شوید لینک مستقیماً یک فایل ZIP معتبر قالب وردپرس را دانلود می‌کند و پوشه wp-content/themes قابل نوشتن است.',
                'Theme installation failed. Make sure the URL downloads a valid WordPress theme ZIP directly and that wp-content/themes is writable.'
            )
        );
    }

    $destination = !empty($upgrader->result['destination_name'])
        ? sanitize_text_field($upgrader->result['destination_name'])
        : '';

    // Clear stale theme data so the newly installed version is immediately visible.
    if (function_exists('wp_clean_themes_cache')) {
        wp_clean_themes_cache(true);
    }

    if ($destination) {
        $installed_theme = wp_get_theme($destination);
        if (!$installed_theme->exists()) {
            return new WP_Error(
                'ezd_theme_validation_failed',
                ezd_text('فایل قالب استخراج شد اما وردپرس نتوانست قالب نصب‌شده را شناسایی کند.', 'The package was extracted, but WordPress could not detect the installed theme.')
            );
        }
    }

    return array(
        'type'        => 'theme',
        'destination' => $destination,
        'message'     => $destination
            ? sprintf(ezd_text('قالب «%s» با موفقیت نصب یا بروزرسانی شد.', 'Theme “%s” was installed or updated successfully.'), $destination)
            : ezd_text('قالب با موفقیت نصب یا بروزرسانی شد.', 'The theme was installed or updated successfully.'),
    );
}
