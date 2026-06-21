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
	
  <div class="container">
    <div class="box">
       <p class="box-title-3"> دانلود پلاگین وردپرس </p>
	   <div class="sub-box"><a href="https://learnfa.net/">
        <li class="sub-title-box">لرنفا</li>
      </a></div>
	  <div class="sub-box"><a href="https://pluginyab.ir/">
        <li class="sub-title-box">پلاگین یاب</li>
      </a></div>
	 <div class="sub-box"><a href="https://wohil.com/wpdl/">
        <li class="sub-title-box">ووهیل</li>
      </a></div>
    </div>
    <div class="box">
     <p class="box-title-2"> لینک های مفید تلگرامی </p>
	     <div class="sub-box"><a href="https://t.me/learnfanet">
        <li class="sub-title-box">کانال لرنفا</li>
      </a></div>
	  <div class="sub-box"><a href="https://t.me/aandqchannel">
        <li class="sub-title-box">کانال آموزگار</li>
      </a></div>
	 <div class="sub-box"><a href="https://t.me/wiraweb">
        <li class="sub-title-box">کانال ویرا وب</li>
      </a></div>
	  <div class="sub-box"><a href="https://t.me/pluginyabfiles">
        <li class="sub-title-box">کانال پلاگین یاب</li>
      </a></div>
      <div class="sub-box"><a href="https://t.me/amuzgarwp">
        <li class="sub-title-box">گروه آموزگار وردپرس</li>
      </a></div>
	 <div class="sub-box"><a href="https://t.me/programer_grup">
        <li class="sub-title-box">گروه برنامه نویسی | طراحی وب</li>
      </a></div>
    </div>
      <div class="box">
	<p class="box-title-1"> آموزش ویدیویی وردپرس </p>
      <div class="sub-box"><a href="https://www.youtube.com/@amuzgar">
        <img src="https://ps.w.org/ez-downloader/assets/amuzgar.webp" alt="آموزگار">
        <p class="sub-title-box">کانال یوتیوب آموزش وردپرس</p>
      </a></div>
	  <div class="sub-box"><a href="https://learnfa.net/">
        <img src="https://ps.w.org/ez-downloader/assets/learnfa.png" alt="لرنفا">
        <p class="sub-title-box">آموزش وردپرس</p>
      </a></div>
    </div>
    <div class="box">
         <p class="box-title-4"> خرید هاست</p>
		   <div class="sub-box"><a href="https://www.youtube.com/playlist?list=PLmk0Q5D1W9oBoUCubm8vYUDFvrHH6yDJq">
        <li class="sub-title-box">آموزش خرید هاست</li>
      </a></div>
	  <p class="host-title">هاست های پیشنهادی</p>
	   <div class="sub-box"><a href="https://client.mizbanpack.com/aff.php?aff=35">
        <li class="sub-title-box">میزبان پک</li>
      </a></div>
	  <div class="sub-box"><a href="https://panel.limoo.host/aff.php?aff=1479&gid=3">
        <li class="sub-title-box">لیمو هاست </li>
      </a></div>
	 <div class="sub-box"><a href="https://www.bestla.net/portal/aff.php?aff=490">
        <li class="sub-title-box">بستلا هاست</li>
      </a></div>
	  
    </div>
    <div class="box">
      <p class="box-title-5"> توسعه دهنده</p>
	  	   <div class="sub-box"><a href="https://profiles.wordpress.org/drowranger/">
        <li class="sub-title-box">دیگر پلاگین ها</li>
      </a></div>
	   <div class="sub-box"><a href="https://wordpress.org/plugins/ez-downloader/#reviews">
        <li class="sub-title-box">امتیاز به این پلاگین</li>
      </a></div>
  </div>		

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

