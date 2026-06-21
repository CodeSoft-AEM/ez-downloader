<?php

// برای امنیت این کد اضافه میشه
if (!defined('ABSPATH')) {
    exit;
}

// CSS
function cpi_enqueue_custom_styles($hook) {
    if ($hook != 'toplevel_page_custom-plugin-installer') {
        return;
    }
    
    
    wp_enqueue_style('custom-style', plugin_dir_url('') . 'ez-downloader/assets/style.css', [], null);
    wp_enqueue_style('vazirmatn-font', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap', [], null);
}
// تنظیم اولویت با عدد پایین برای اطمینان از لود شدن در اولویت بالاتر
add_action('admin_enqueue_scripts', 'cpi_enqueue_custom_styles', 1);

