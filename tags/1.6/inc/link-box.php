<?php /* link-box.php – ریسپانسیو، هم‌قد، بدون استفاده از کلاس body و بدون @import فونت */ ?>
<style>
  :root{
    --ezd-bg:#fff;
    --ezd-card:#f7f9fc;
    --ezd-text:#111827;
    --ezd-muted:#6b7280;
    --ezd-border:#e5e7eb;
    --ezd-shadow:0 10px 28px rgba(0,0,0,.07);
    --ezd-radius:16px;
  }
  @media (prefers-color-scheme: dark){
    :root{
      --ezd-bg:#0f1623; --ezd-card:#111a2b; --ezd-text:#e5e7eb;
      --ezd-muted:#9aa3b2; --ezd-border:#1f2a3c; --ezd-shadow:0 14px 34px rgba(0,0,0,.35);
    }
  }

  /* گرید ریسپانسیو + حل هم‌پوشانی فوتر ادمین با padding-bottom محلی */
  .ezd-links{
    direction:rtl;
    font-family:"Vazirmatn",ui-sans-serif,system-ui,-apple-system;
    font-size:14px; line-height:1.9; color:var(--ezd-text);
    padding-bottom: 96px; /* جا برای فوتر ادمین تا روی جدول‌ها نیفته */
  }

  .ezd-grid{ display:grid; gap:18px; grid-template-columns:repeat(12,1fr); }
  .ezd-card{ grid-column:span 4; }
  @media (max-width:1100px){ .ezd-card{ grid-column:span 6; } }
  @media (max-width:700px){ .ezd-card{ grid-column:1/-1; } }

  .ezd-card{
    background:var(--ezd-card); border:1px solid var(--ezd-border); border-radius:var(--ezd-radius);
    box-shadow:var(--ezd-shadow),0 0 0 1px rgba(0,0,0,.02) inset; overflow:hidden;
    display:flex; flex-direction:column; min-height:100%;
  }

  /* تم رنگی هر باکس */
  .-blue   { --accent:#2563eb; --accent-soft:rgba(37,99,235,.18) }
  .-green  { --accent:#10b981; --accent-soft:rgba(16,185,129,.18) }
  .-purple { --accent:#7c3aed; --accent-soft:rgba(124,58,237,.18) }
  .-amber  { --accent:#f59e0b; --accent-soft:rgba(245,158,11,.20) }
  .-rose   { --accent:#e11d48; --accent-soft:rgba(225,29,72,.18) }
  .-cyan   { --accent:#06b6d4; --accent-soft:rgba(6,182,212,.18) }

  .ezd-card__head{
    padding:14px 16px; border-bottom:1px solid var(--ezd-border);
    background:
      linear-gradient(180deg,var(--accent-soft) 0%,rgba(255,255,255,0) 90%),
      radial-gradient(60rem 6rem at 0% -20%, rgba(255,255,255,.35), transparent 60%);
    border-top:3px solid var(--accent);
  }
  #wpfooter{display:none;}
  .ezd-card__title{
    margin:0; font-weight:800; font-size:16px; display:flex; align-items:center; gap:10px;font-family:Vazirmatn!important;
  }
  .ezd-dot{
    width:10px; height:10px; border-radius:50%; background:var(--accent);
    box-shadow:0 0 0 6px var(--accent-soft);
  }
  .ezd-card__hint{ margin:6px 0 0; font-size:12px; color:var(--ezd-muted) }

  .ezd-table-wrap{ width:100%; overflow:auto; flex:1 }
  .ezd-table{ width:100%; border-collapse:collapse; min-width:420px; }
  .ezd-table th,.ezd-table td{
    padding:10px 12px; border-bottom:1px dashed var(--ezd-border);
    text-align:right; vertical-align:middle; white-space:nowrap;
  }
  .ezd-table th{
    font-size:12px; color:var(--ezd-muted); font-weight:700;
    position:sticky; top:0; background:var(--ezd-card); z-index:1;
  }

  .ezd-row-link{
    display:flex; align-items:center; gap:10px; text-decoration:none; color:inherit;
    border-radius:10px; padding:6px 8px;
    transition:transform .12s ease, background .12s ease, box-shadow .12s ease;
  }
  .ezd-row-link:hover{
    background:var(--accent-soft); transform:translateY(-1px);
    box-shadow:0 6px 16px rgba(0,0,0,.08);
  }
  .ezd-icon{
    width:26px; height:26px; display:inline-flex; align-items:center; justify-content:center;
    border-radius:8px; background:var(--ezd-bg); border:1px solid var(--ezd-border);
  }

  /* اسکرول افقی نرم جدول‌ها در موبایل */
  .ezd-table-wrap::-webkit-scrollbar{height:10px}
  .ezd-table-wrap::-webkit-scrollbar-thumb{background:var(--ezd-border);border-radius:8px}
</style>

<div class="ezd-links">
  <div class="ezd-grid">

    <!-- 1 -->
    <section class="ezd-card -blue">
      <header class="ezd-card__head">
        <h3 class="ezd-card__title"><span class="ezd-dot"></span> لینک‌های مفید</h3>
        <p class="ezd-card__hint">منابع آموزشی و ابزارها</p>
      </header>
      <div class="ezd-table-wrap">
        <table class="ezd-table" role="table" aria-label="لینک‌های مفید">
          <thead><tr><th>سایت</th><th>توضیح</th></tr></thead>
          <tbody>
            <tr><td><a class="ezd-row-link" href="https://learnfa.net/" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-welcome-learn-more"></span><span>لرنفا</span></a></td>
              <td>آموزش رایگان وردپرس</td></tr>
            <tr><td><a class="ezd-row-link" href="https://pluginyab.ir/" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-admin-plugins"></span><span>پلاگین‌یاب</span></a></td>
              <td>دانلود افزونه‌های وردپرس</td></tr>
			  <tr><td><a class="ezd-row-link" href="https://proelement.ir/" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-admin-plugins"></span><span>پرو المنت</span></a></td>
              <td>کیت های آماده المنتور</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- 2 -->
    <section class="ezd-card -green">
      <header class="ezd-card__head">
        <h3 class="ezd-card__title"><span class="ezd-dot"></span>لینک های مفید تلگرامی</h3>
        <p class="ezd-card__hint">گروه ها و کانال های مربوط به وردپرس</p>
      </header>
      <div class="ezd-table-wrap">
        <table class="ezd-table" role="table" aria-label="تلگرام">
          <thead><tr><th>کانال</th><th>توضیح</th></tr></thead>
          <tbody>
            <tr><td><a class="ezd-row-link" href="https://t.me/learnfanet" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-megaphone"></span><span>کانال لرنفا</span></a></td>
              <td>آموزش‌ها و اطلاعیه‌ها</td></tr>
            <tr><td><a class="ezd-row-link" href="https://t.me/amuzgarwp" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-admin-users"></span><span>گروه پشتیبانی آموزگار</span></a></td>
              <td>پرسش و پاسخ کاربران</td></tr>
			   <tr><td><a class="ezd-row-link" href="https://t.me/vps7_net" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-megaphone"></span><span>کانال سرور و هاست VPS 7</span></a></td>
              <td>اخبار و مقالات وردپرس و هاستینگ</td></tr>
			   <tr><td><a class="ezd-row-link" href="https://t.me/VPS7_NET_Group" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-admin-users"></span><span>گروه پشتیبانی VPS 7</span></a></td>
              <td>گروه پشتیبانی وردپرس و هاست VPS 7</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- 3 -->
    <section class="ezd-card -purple">
      <header class="ezd-card__head">
        <h3 class="ezd-card__title"><span class="ezd-dot"></span> آموزش ویدیویی وردپرس</h3>
        <p class="ezd-card__hint">YouTube و آموزش‌ها</p>
      </header>
      <div class="ezd-table-wrap">
        <table class="ezd-table" role="table" aria-label="ویدیوهای آموزشی">
          <thead><tr><th>منبع</th><th>توضیح</th></tr></thead>
          <tbody>
            <tr><td><a class="ezd-row-link" href="https://www.youtube.com/@amuzgar" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-video-alt3"></span><span>کانال آموزگار</span></a></td>
              <td>کانال یوتیوب آموزش وردپرس</td></tr>
            <tr><td><a class="ezd-row-link" href="https://www.youtube.com/@amoozyir" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-video-alt3"></span><span>کانال آموزی</span></a></td>
              <td>کانال یوتیوب آموزش وردپرس</td></tr>
			  <tr><td><a class="ezd-row-link" href="https://www.youtube.com/@niasir" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-video-alt3"></span><span>کانال نیاس</span></a></td>
              <td>کانال یوتیوب آموزش وردپرس</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- 4 -->
    <section class="ezd-card -amber">
      <header class="ezd-card__head">
        <h3 class="ezd-card__title"><span class="ezd-dot"></span> خرید هاست</h3>
        <p class="ezd-card__hint">پیشنهادهای ویدئویی/راهنما</p>
      </header>
      <div class="ezd-table-wrap">
        <table class="ezd-table" role="table" aria-label="خرید هاست">
          <thead><tr><th>لینک</th><th>توضیح</th></tr></thead>
          <tbody>
            <tr><td><a class="ezd-row-link" href="https://vps7.net/" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-cloud"></span><span>سرور و هاست VPS 7</span></a></td>
              <td>انواع هاست وردپرسی و سرور مجازی و اختصاصی</td></tr>
			 
          </tbody>
        </table>
      </div>
    </section>

    <!-- 5 -->
    <section class="ezd-card -rose">
      <header class="ezd-card__head">
        <h3 class="ezd-card__title"><span class="ezd-dot"></span>پلاگین های توسعه دهنده</h3>
        <p class="ezd-card__hint">پلاگین‌ها و پروژه‌ها</p>
      </header>
      <div class="ezd-table-wrap">
        <table class="ezd-table" role="table" aria-label="توسعه‌دهنده">
          <thead><tr><th>پروژه</th><th>توضیح</th></tr></thead>
          <tbody>
            <tr><td><a class="ezd-row-link" href="https://wordpress.org/plugins/ez-downloader/" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-download"></span><span>EZ-Downloader</span></a></td>
              <td>نصب پلاگین و قالب از طریق لینک فایل زیپ</td></tr>
			    <tr><td><a class="ezd-row-link" href="https://vps7.net/vps7-plugin/vps7-repository-client/" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-download"></span><span>EZ-Downloader Pro </span></a></td>
              <td>مخزنی شبیه به مخزن وردپرس با پلاگین های پرمیوم</td></tr>
			  
            <tr><td><a class="ezd-row-link" href="https://wordpress.org/plugins/ez-login/" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-admin-plugins"></span><span>EZ-Login</span></a></td>
              <td>ثبت نام و ورود از طریق پیامک و گوگل</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- 6 -->
    <section class="ezd-card -cyan">
      <header class="ezd-card__head">
        <h3 class="ezd-card__title"><span class="ezd-dot"></span>توسعه دهنده</h3>
        <p class="ezd-card__hint">لینک های توسعه دهنده</p>
      </header>
      <div class="ezd-table-wrap">
        <table class="ezd-table" role="table" aria-label="مستندات">
          <thead><tr><th>منبع</th><th>توضیح</th></tr></thead>
          <tbody>
            <tr><td><a class="ezd-row-link" href="https://profiles.wordpress.org/drowranger/" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-media-text"></span><span>پروفایل</span></a></td>
              <td>پروفایل توسعه دهنده</td></tr>
            <tr><td><a class="ezd-row-link" href="https://profiles.wordpress.org/drowranger/#content-plugins" target="_blank" rel="noopener noreferrer">
              <span class="ezd-icon dashicons dashicons-media-code"></span><span>دیگر پلاگین ها</span></a></td>
              <td>تمامی پلاگین های توسعه دهنده</td></tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>
</div>
