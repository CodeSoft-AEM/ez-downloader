<?php
/**
 * Plugin Name: EZ Downloader – Plugin & Theme
 * Description: Install or update multiple WordPress plugins and one theme from direct ZIP links or the WordPress.org repository.
 * Version: 1.8.3
 * Author: Abolfazl Edalati
 * Author URI: https://profiles.wordpress.org/drowranger/
 * Plugin URI: https://wordpress.org/plugins/ez-downloader/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ez-downloader
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('EZD_VERSION', '1.8.3');
define('EZD_FILE', __FILE__);
define('EZD_DIR', plugin_dir_path(__FILE__));
define('EZD_URL', plugin_dir_url(__FILE__));

require_once EZD_DIR . 'inc/plugin_install.php';
require_once EZD_DIR . 'inc/theme_install.php';
require_once EZD_DIR . 'inc/functions.php';
require_once EZD_DIR . 'inc/admin_pages.php';
