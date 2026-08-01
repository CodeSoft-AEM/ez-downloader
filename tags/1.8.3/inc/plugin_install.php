<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Install or replace a plugin from a remote ZIP package.
 *
 * @param string $url Direct URL to the plugin ZIP package.
 * @return array|WP_Error
 */
function cpi_download_and_extract_plugin($url) {
    if (!current_user_can('install_plugins')) {
        return new WP_Error('ezd_plugin_permission', ezd_text('شما مجوز نصب افزونه را ندارید.', 'You are not allowed to install plugins.'));
    }

    $url = esc_url_raw($url, array('http', 'https'));
    if (!$url || !wp_http_validate_url($url)) {
        return new WP_Error('ezd_invalid_plugin_url', ezd_text('لینک افزونه معتبر نیست.', 'The plugin URL is invalid.'));
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

    $skin     = new Automatic_Upgrader_Skin();
    $upgrader = new Plugin_Upgrader($skin);
    $result   = $upgrader->install(
        $url,
        array(
            'overwrite_package' => true,
        )
    );

    if (is_wp_error($result)) {
        return $result;
    }

    if (is_wp_error($skin->result)) {
        return $skin->result;
    }

    if (!$result) {
        return new WP_Error('ezd_plugin_install_failed', ezd_text('نصب افزونه انجام نشد. فایل ZIP و لینک دانلود را بررسی کنید.', 'Plugin installation failed. Check the ZIP file and download URL.'));
    }

    $destination = '';
    if (is_array($upgrader->result) && !empty($upgrader->result['destination_name'])) {
        $destination = sanitize_text_field($upgrader->result['destination_name']);
    }

    return array(
        'type'        => 'plugin',
        'destination' => $destination,
        'message'     => $destination
            ? sprintf(ezd_text('افزونه «%s» با موفقیت نصب یا بروزرسانی شد.', 'Plugin “%s” was installed or updated successfully.'), $destination)
            : ezd_text('افزونه با موفقیت نصب یا بروزرسانی شد.', 'The plugin was installed or updated successfully.'),
    );
}
