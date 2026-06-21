<?php
/*
Plugin Name: EZ-Downloader
Description: Download and extract plugin from a URL.
Version: 1.0
Author: Abolfazl Edalati 
Author URI: https://profiles.wordpress.org/drowranger/
Plugin URI: https://wiraweb.net/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit;
}

// Create admin menu
function cpi_add_admin_menu() {
    add_menu_page('EZ-Downloader', 'EZ-Downloader', 'manage_options', 'custom-plugin-installer', 'cpi_settings_page');
}
add_action('admin_menu', 'cpi_add_admin_menu');

// Enqueue Google Fonts
function cpi_enqueue_styles() {
    wp_enqueue_style('vazirmatn-font', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap', [], null);
}
add_action('admin_enqueue_scripts', 'cpi_enqueue_styles');

// Settings page HTML
function cpi_settings_page() {
    ?>
    <div class="wrap">
        <h1>EZ-Downloader</h1>
        <form method="post" style="margin-top:20px;" action="">
            <?php wp_nonce_field('cpi_download_file', 'cpi_nonce'); ?>
            <label for="plugin_url" style="font-family:Vazirmatn;"><b>لینک پلاگین :</b></label>
            <input type="text" name="plugin_url" id="plugin_url" style="width: 300px;" required />
            <input type="submit" name="submit" style="font-family:Vazirmatn;background-color:green;" value="دانلود پلاگین" class="button button-primary" />
        </form>
        <center><h2 style="margin-top:200px;">Power By Abolfazl Edalati</h2></center>
    </div>
    <?php
    if (isset($_POST['submit']) && check_admin_referer('cpi_download_file', 'cpi_nonce')) {
        $plugin_url = isset($_POST['plugin_url']) ? esc_url_raw(wp_unslash($_POST['plugin_url'])) : '';
        if (!empty($plugin_url)) {
            cpi_download_and_extract_plugin($plugin_url);
        } else {
            echo '<div class="error"><p>لطفا لینک پلاگین را وارد کنید.</p></div>';
        }
    }
}

// Download and extract the plugin
function cpi_download_and_extract_plugin($url) {
    global $wp_filesystem;

    // Initialize the WP Filesystem
    if (!function_exists('WP_Filesystem')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    WP_Filesystem();

    // Temporary file path
    $temp_file = wp_tempnam($url);

    // Download the file
    $response = wp_remote_get($url, array('timeout' => 300));
    if (is_wp_error($response)) {
        echo '<div class="error"><p>خطا در دانلود فایل.</p></div>';
        return;
    }

    // Write the file using WP_Filesystem
    $body = wp_remote_retrieve_body($response);
    if (!$wp_filesystem->put_contents($temp_file, $body, FS_CHMOD_FILE)) {
        echo '<div class="error"><p>خطا در ذخیره فایل.</p></div>';
        return;
    }

    // Include necessary files for extracting
    if (!class_exists('PclZip')) {
        require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
    }

    // Extract the zip file
    $archive = new PclZip($temp_file);
    if ($archive->extract(PCLZIP_OPT_PATH, WP_PLUGIN_DIR) == 0) {
        echo '<div class="error"><p>خطا در اکسترکت فایل.</p></div>';
        return;
    }

    // Cleanup using wp_delete_file
    wp_delete_file($temp_file);

    echo '<div class="updated"><p>پلاگین با موفقیت نصب شد.</p></div>';
}
