<?php

if (!defined('ABSPATH')) {
    exit;
}

$ezd_link_groups = array(
    array(
        'title' => ezd_text('لینک‌های مفید', 'Useful links'),
        'icon'  => '🔗',
        'color' => '#2563eb',
        'soft'  => 'rgba(37,99,235,.08)',
        'items' => array(
            array('url' => 'https://pluginyab.ir/', 'label' => ezd_text('پلاگین‌یاب', 'PluginYab'), 'icon' => '🔌', 'desc' => ezd_text('دانلود افزونه‌های وردپرس', 'WordPress plugin downloads')),
            array('url' => 'https://proelement.ir/', 'label' => ezd_text('پرو المنت', 'Pro Element'), 'icon' => '⚡', 'desc' => ezd_text('کیت‌های آماده المنتور', 'Ready-made Elementor kits')),
        ),
    ),
    array(
        'title' => ezd_text('گروه‌ها و منابع آموزشی', 'Groups and learning resources'),
        'icon'  => '📢',
        'color' => '#10b981',
        'soft'  => 'rgba(16,185,129,.08)',
        'items' => array(
            array('url' => 'https://t.me/amuzgarwp', 'label' => ezd_text('گروه آموزگار', 'Amuzgar group'), 'icon' => '👥', 'desc' => ezd_text('پرسش و پاسخ کاربران', 'Community questions and answers')),
            array('url' => 'https://t.me/vps7_net', 'label' => ezd_text('کانال VPS 7', 'VPS 7 channel'), 'icon' => '📡', 'desc' => ezd_text('اخبار وردپرس و هاستینگ', 'WordPress and hosting news')),
            array('url' => 'https://t.me/VPS7_NET_Group', 'label' => ezd_text('گروه VPS 7', 'VPS 7 group'), 'icon' => '💬', 'desc' => ezd_text('پشتیبانی وردپرس و هاست', 'WordPress and hosting support')),
        ),
    ),
    array(
        'title' => ezd_text('آموزش ویدیویی', 'Video tutorials'),
        'icon'  => '🎬',
        'color' => '#7c3aed',
        'soft'  => 'rgba(124,58,237,.08)',
        'items' => array(
            array('url' => 'https://www.youtube.com/@amuzgar', 'label' => ezd_text('آموزگار', 'Amuzgar'), 'icon' => '▶️', 'desc' => ezd_text('آموزش وردپرس', 'WordPress tutorials')),
            array('url' => 'https://www.youtube.com/@amoozyir', 'label' => ezd_text('آموزی', 'Amoozy'), 'icon' => '▶️', 'desc' => ezd_text('آموزش وردپرس', 'WordPress tutorials')),
            array('url' => 'https://www.youtube.com/@niasir', 'label' => ezd_text('نیاس', 'Nias'), 'icon' => '▶️', 'desc' => ezd_text('آموزش وردپرس', 'WordPress tutorials')),
        ),
    ),
    array(
        'title' => ezd_text('هاست و سرور', 'Hosting and servers'),
        'icon'  => '☁️',
        'color' => '#f59e0b',
        'soft'  => 'rgba(245,158,11,.08)',
        'items' => array(
            array('url' => 'https://vps7.net/', 'label' => 'VPS 7', 'icon' => '🖥️', 'desc' => ezd_text('هاست وردپرس، VPS و سرور اختصاصی', 'WordPress hosting, VPS and dedicated servers')),
        ),
    ),
    array(
        'title' => ezd_text('افزونه‌های توسعه‌دهنده', 'Developer plugins'),
        'icon'  => '🔧',
        'color' => '#e11d48',
        'soft'  => 'rgba(225,29,72,.08)',
        'items' => array(
            array('url' => 'https://wordpress.org/plugins/ez-downloader/', 'label' => 'EZ Downloader', 'icon' => '⬇️', 'desc' => ezd_text('نصب افزونه و قالب از لینک', 'Install plugins and themes from URLs')),
            array('url' => 'https://vps7.net/vps7-plugin/vps7-repository-client/', 'label' => 'EZ Downloader Pro', 'icon' => '🏪', 'desc' => ezd_text('مخزن افزونه‌های پرمیوم', 'Premium plugin repository')),
            array('url' => 'https://wordpress.org/plugins/ez-login/', 'label' => 'EZ Login', 'icon' => '🔑', 'desc' => ezd_text('ورود با پیامک و گوگل', 'OTP and Google login')),
        ),
    ),
    array(
        'title' => ezd_text('توسعه‌دهنده', 'Developer'),
        'icon'  => '👨‍💻',
        'color' => '#06b6d4',
        'soft'  => 'rgba(6,182,212,.08)',
        'items' => array(
            array('url' => 'https://profiles.wordpress.org/drowranger/', 'label' => ezd_text('پروفایل توسعه‌دهنده', 'Developer profile'), 'icon' => '👤', 'desc' => ezd_text('پروفایل رسمی در WordPress.org', 'Official WordPress.org profile')),
            array('url' => 'https://profiles.wordpress.org/drowranger/#content-plugins', 'label' => ezd_text('تمامی افزونه‌ها', 'All developer plugins'), 'icon' => '📦', 'desc' => ezd_text('افزونه‌های منتشرشده توسعه‌دهنده', 'Published plugins by the developer')),
            array('url' => 'https://codsoft.ir/', 'label' => ezd_text('سایت توسعه‌دهنده', 'Developer website'), 'icon' => '🌐', 'desc' => ezd_text('وب‌سایت رسمی CodeSoft', 'Official CodeSoft website')),
        ),
    ),
);
?>
<section class="ezd-links-section" aria-labelledby="ezd-useful-links-title">
    <div class="ezd-section-divider">
        <span></span>
        <h2 id="ezd-useful-links-title"><?php echo esc_html(ezd_text('لینک‌های مفید و پشتیبانی', 'Useful links and support')); ?></h2>
        <span></span>
    </div>

    <div class="ezd-links-grid">
        <?php foreach ($ezd_link_groups as $group) : ?>
            <article class="ezd-link-card" style="--ezd-accent:<?php echo esc_attr($group['color']); ?>;--ezd-accent-soft:<?php echo esc_attr($group['soft']); ?>;">
                <header class="ezd-link-card__header">
                    <span class="ezd-link-card__icon" aria-hidden="true"><?php echo esc_html($group['icon']); ?></span>
                    <h3><?php echo esc_html($group['title']); ?></h3>
                </header>
                <div class="ezd-link-card__items">
                    <?php foreach ($group['items'] as $item) : ?>
                        <a href="<?php echo esc_url($item['url']); ?>" target="_blank" rel="noopener noreferrer" class="ezd-resource-link">
                            <span class="ezd-resource-link__icon" aria-hidden="true"><?php echo esc_html($item['icon']); ?></span>
                            <span class="ezd-resource-link__content">
                                <strong><?php echo esc_html($item['label']); ?></strong>
                                <small><?php echo esc_html($item['desc']); ?></small>
                            </span>
                            <span class="ezd-resource-link__arrow" aria-hidden="true">↗</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
