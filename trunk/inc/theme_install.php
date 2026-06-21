<?php 

// برای امنیت این کد اضافه می‌شود
if (!defined('ABSPATH')) {
    exit;
}

// دانلود و استخراج قالب
function cti_download_and_extract_theme($url) {
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
    if ($archive->extract(PCLZIP_OPT_PATH, get_theme_root()) == 0) {
        echo '<div class="error"><p>خطا در اکسترکت فایل.</p></div>';
        return;
    }

    // Cleanup using wp_delete_file
    wp_delete_file($temp_file);

    echo '<div class="updated"><p>قالب با موفقیت نصب شد.</p></div>';
}
