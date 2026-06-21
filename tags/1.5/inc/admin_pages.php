<?php 

// برای امنیت این کد اضافه میشه
if (!defined('ABSPATH')) {
    exit;
}

// Create admin menu
function cpi_add_admin_menu() {
    add_menu_page('EZ-Downloader', 'EZ-Downloader', 'manage_options', 'custom-plugin-installer', 'cpi_settings_page');
}
add_action('admin_menu', 'cpi_add_admin_menu');

// Settings page HTML
function cpi_settings_page() {
    ?>
    <div class="wrap">
        <h1>EZ-Downloader</h1>
		 <a href="https://wordpress.org/plugins/ez-downloader/" class="plugin-logo" target="_blank"><img  src="https://ps.w.org/ez-downloader/assets/icon-128%C3%97128.jpg" /></a>
        <form method="post" style="margin-top:20px;" action="">
		<h2 class="title-dis">نصب کننده پلاگین</h2>
            <?php wp_nonce_field('cpi_download_file', 'cpi_nonce'); ?>
            <label for="plugin_url" class="text-field"><b>لینک پلاگین :</b></label>
            <input type="text" name="plugin_url" id="plugin_url" style="width: 300px;" required />
            <input type="submit" name="submit" value="دانلود پلاگین" class="button button-primary" />
        </form>
			<br><hr>
			    <h2 class="title-dis">نصب کننده قالب</h2>
			<div class="wrap">
    <form method="post" action="">
        <?php wp_nonce_field('cti_download_file', 'cti_nonce'); ?>
        <label for="theme_url" class="text-field"><b>لینک قالب :</b></label>
        <input type="text" name="theme_url" id="theme_url" style="width: 300px;" required />
        <input type="submit" name="install_theme" value="دانلود قالب" class="button2 button-primary" />
    </form>
</div>
<?php
if (isset($_POST['install_theme']) && check_admin_referer('cti_download_file', 'cti_nonce')) {
    $theme_url = isset($_POST['theme_url']) ? esc_url_raw(wp_unslash($_POST['theme_url'])) : '';
    if (!empty($theme_url)) {
        cti_download_and_extract_theme($theme_url);
    } else {
        echo '<div class="error"><p>لطفا لینک قالب را وارد کنید.</p></div>';
    }
}
?>
<br><hr>
			<h3 class="title-dis">روش دستیابی به لینک ها </h3>
			<p>درصورتی که نمیتوانید لینک های مربوط به پلاگین ها را پیدا کنید بهتر است به سایت های زیر مراجعه کنید
	<?php
include("link-box.php");	?>

</div>
	 <center><p class="review">برای حمایت از ما از <a href="https://wordpress.org/plugins/ez-downloader/#reviews"  target="_blank"> این لینک </a> نظر خود را راجبع این پلاگین بنویسید و اگر دوست داشتید امتیاز 5 ستاره بدهید.</p></center>
<br>
        <center><h2 class="copy-right">Power By Abolfazl Edalati</h2></center>
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

