<?php
/*
Plugin Name: EZ-Downloader
Description: Install Plugin with URL 
Version: 1.4
Author: Abolfazl Edalati 
Author URI: https://wiraweb.net/
Plugin URI: https://wiraweb.net/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit;
}



//الصاق فایل نصب افزونه ها
include("inc/plugin_install.php");
//تمپلیت صفحه پلاگین
include("inc/admin_pages.php");
// فراخوانی های توابع تو این فایل
include("inc/functions.php");
// الصاق فایل نصب قالب‌ها
include("inc/theme_install.php");