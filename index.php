<?php
// ตั้งค่าพารามิเตอร์ของคุกกี้สำหรับ session เพื่อเพิ่มความปลอดภัยในการจัดการ session โดยมีการตั้งค่า httponly, secure และ samesite
session_set_cookie_params([
  'httponly' => true, // ป้องกันการเข้าถึงคุกกี้จาก JavaScript เพื่อป้องกันการโจมตีแบบ XSS
  'secure' => !empty($_SERVER['HTTPS']), // true เมื่อใช้ https
  'samesite' => 'Lax', // ป้องกันการส่งคุกกี้ในคำขอข้ามไซต์ (Cross-Site Request Forgery - CSRF) โดยอนุญาตให้ส่งคุกกี้ในคำขอที่มาจากไซต์เดียวกันเท่านั้น
]);

session_start();
define('APP_ROOT', __DIR__); // กำหนดค่าคงที่ APP_ROOT เป็นเส้นทางของไดเรกทอรีปัจจุบัน ซึ่งจะใช้ในการอ้างอิงเส้นทางของไฟล์ต่าง ๆ ในโปรเจกต์ได้อย่างสะดวกและปลอดภัยมากขึ้น โดยไม่ต้องกังวลเกี่ยวกับเส้นทางสัมพัทธ์ที่อาจทำให้เกิดปัญหาในการโหลดไฟล์ในบางกรณี

require_once APP_ROOT . '/include/error_report.inc.php'; // รวมไฟล์ error_report.inc.php เพื่อกำหนดการแสดงข้อผิดพลาดและตั้งค่าโซนเวลา
require_once APP_ROOT . '/include/auth.inc.php'; // รวมไฟล์ auth.inc.php 
include_once (APP_ROOT . '/include/function.inc.php');
require_once (APP_ROOT . '/include/connect_db.inc.php');
require_once (APP_ROOT . '/include/class_crud.inc.php');
require_once (APP_ROOT . '/include/setting.inc.php');
require_once (APP_ROOT . '/include/language.inc.php');

/*--------------------------------------------------------- 
รูปแบบโครงสร้างแบบ SPA (Single Page Application) โดยใช้ URL hash เพื่อจัดการการนำทางภายในแอปพลิเคชัน ซึ่งช่วยให้หน้าเว็บไม่ต้องรีเฟรชใหม่ทุกครั้งที่ผู้ใช้คลิกเมนูหรือเปลี่ยนหน้า โดย URL จะมีรูปแบบเช่น #/app/dashboard/ จะเป็นการระบุเส้นทางภายในแอปพลิเคชัน และการใช้ URL hash ยังช่วยให้สามารถจัดการประวัติการเข้าชมของผู้ใช้ได้ง่ายขึ้น และยังสามารถทำงานร่วมกับ JavaScript เพื่อโหลดเนื้อหาที่เกี่ยวข้องกับแต่ละหน้าได้อย่างรวดเร็วโดยไม่ต้องรีเฟรชหน้าใหม่ทั้งหมด

และเป็นการแสดงผลแบบ CSR (Client-Side Rendering) ซึ่งหมายความว่าเนื้อหาของหน้าเว็บจะถูกสร้างและจัดการโดย JavaScript ที่ทำงานบนฝั่งผู้ใช้งาน (Client) แทนที่จะเป็นการสร้างเนื้อหาบนฝั่งเซิร์ฟเวอร์ (Server) ซึ่งช่วยให้การโต้ตอบกับผู้ใช้เป็นไปอย่างรวดเร็วและลื่นไหลมากขึ้น โดยไม่ต้องรอการโหลดหน้าใหม่จากเซิร์ฟเวอร์ทุกครั้งที่มีการเปลี่ยนแปลงในหน้าเว็บ
---------------------------------------------------------*/

Language::lang_menu; //โหลดภาษาที่ใช้ในเมนูจากคลาส Language เพื่อให้สามารถแสดงผลภาษาได้ตามที่ผู้ใช้เลือก โดยการเข้าถึงค่าภาษาในเมนูจะใช้รูปแบบ Language::lang_menu[ภาษาที่เลือก]['text_หมายเลข'] เช่น Language::lang_menu['th']['text_1'] เพื่อแสดงคำว่า "หน้าแรก" ในภาษาไทย หรือ Language::lang_menu['en']['text_1'] เพื่อแสดงคำว่า "Home" ในภาษาอังกฤษ

##เช็คว่ามีการเปลี่ยนภาษาไหม ถ้ามีให้เซ็ตค่าใน session แล้วรีเฟรชหน้าใหม่เพื่อให้ภาษาเปลี่ยนตามที่เลือก 
## ที่รีเฟรชหน้าใหม่เพราะป้องกัน SPA ลำดับ URL ผิดเป็น ?lang=th#/app/dashboard/
if (isset($_GET['lang']) && is_string($_GET['lang']) && in_array($_GET['lang'], ['th', 'en'])) {
  $_SESSION['lang'] = $_GET['lang'];
  header("Location: ./"); 
  exit;    
} elseif (!isset($_SESSION['lang'])) {
  $_SESSION['lang'] = 'en'; // Default
  header("Location: ./"); 
  exit;        
}

if(empty($_SESSION['user_id'])) { ##เช็คว่ามีการล็อกอินเข้ามาแล้วหรือยัง ถ้าไม่มีจะถูกส่งไปหน้า login
  include_once ('login.inc.php');
  //header('Location:#/login/'); 
  //echo '<script>window.location.href = "#/login/";</script>';    
  exit;
}


$crud = new CRUD(); ##สร้างออปเจค $crud เพื่อเรียกใช้งานคลาส,ฟังก์ชั่นต่างๆที่อยู่ใน class CRUD ซึ่งเชื่อมต่อกับฐานข้อมูลแล้ว

?>
<!doctype html>
<html lang="en">
<!--begin::Head-->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>CCMS | Login Page</title>

  <!-- Favicon -->
  <link rel="icon" type="image/webp" href="img/ico/main-ico.png">

  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

  <!--end::Accessibility Meta Tags-->
  <!--begin::Primary Meta Tags-->
  <meta name="title" content="CCMS | Login Page" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="CCMS" />
  <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard" />
  <!--end::Primary Meta Tags-->

  <!--begin::Accessibility Features-->
  <link rel="preload" href="css/adminlte.css" as="style" />
  <!--end::Accessibility Features-->
  
  <!--begin::google Fonts-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <link type="text/css" href="css/sarabun_font.css" rel="stylesheet" />
  <!--end:: google Fonts-->
  
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="css/adminlte.css" />
  <link rel="stylesheet" href="css/customize.scss" />
  <!--end::Required Plugin(AdminLTE)-->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<!--end::Head-->
<!--begin::Body-->
<body class="layout-fixed fixed-header fixed-footer sidebar-expand-lg bg-body-tertiary sidebar-collapse"><!--app-loaded reduce-motion-->
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Start Navbar Links พื้นที่ด้านบนสุด-->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="#" class="nav-link">How to</a>
          </li>
          <!--<li class="nav-item d-none d-md-block txt_smaller">
            <a href="#" class="nav-link">Login</a>
          </li>-->
        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
          <!--begin::Navbar Search-->
          <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button">
              <i class="bi bi-translate"></i> <?php echo strtoupper($_SESSION['lang'] ?? 'EN'); ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-0">
              <a href="?lang=th" class="dropdown-item d-flex align-items-center">
                <img src="assets/img/flags/th.png" alt="Thai Flag" class="me-2" style="width: 20px; height: auto;">
                Thai
              </a>
              <a href="?lang=en" class="dropdown-item d-flex align-items-center">
                <img src="assets/img/flags/uk.png" alt="English Flag" class="me-2" style="width: 20px; height: auto;">
                English
              </a>
            </div>
          </li>
          <!--<li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <i class="bi bi-search"></i>
            </a>
          </li>-->
          <!--end::Navbar Search-->

          <!--begin::Messages Dropdown Menu-->
          <!--end::Messages Dropdown Menu-->

          <!--begin::Notifications Dropdown Menu จุดแทรก Notifications-->
          <li id="msg_notifications" class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
              <i class="bi bi-bell-fill"></i>
              <span class="navbar-badge badge text-bg-warning">15</span>
            </a>              
          </li>
          <!--end::Notifications Dropdown Menu-->

          <!--begin::Fullscreen Toggle-->
          <!--<li class="nav-item">
            <a class="nav-link" href="#" data-lte-toggle="fullscreen">
              <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
              <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
            </a>
          </li>-->
          <!--end::Fullscreen Toggle-->

          <!--begin::User Menu Dropdown จุดแทรก User Profile-->
          <li class="nav-item dropdown user-menu" id="user-profile"></li>
          <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
      </div>
      <!--end::Container-->
    </nav>
    <!--end::Header-->
    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./" class="brand-link">
          <!--begin::Brand Image-->
            <img src="assets/img/settings.png" alt="CMMS DEMO Logo" class="brand-image opacity-75 shadow" />
          <!--end::Brand Image-->
          <!--begin::Brand Text-->
          <span class="brand-text fw-light">CMMS DEMO</span>
          <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
      </div>
      <!--end::Sidebar Brand-->
      <!--begin::Sidebar Wrapper จุดแทรก Sidebar Menu ที่ ID left-sidebar-menu -->
      <div class="sidebar-wrapper">
        <nav id="left-sidebar-menu" class="mt-2"></nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->

        <!--end::Container-->
      </div>
      <!--end::App Content Header-->
      <!--begin::App Content-->
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid"><!--Start Content Container-->

        <div class="row">
            <div class="col-12">
              <!-- Default box -->
              <div class="card">
                <div id="btn-back" class="card-header d-flex align-items-center"><a href="javascript:history.back()" class="btn btn-sm btn-outline-gray py-0 me-2 shadow-sm"><i class="bi bi-chevron-double-left small"></i> Back</a>
                <h4 class="title-page card-title mb-0 fw-bold"></h4>
                <span class="sub-title-page ms-2 small text-muted"></span>
                </div>
                <div class="card-body">
                  <div class="app"></div>
                </div>


                <div class="card-footer">Footer</div>
                <!-- /.card-footer-->
              </div>
              <!-- /.card -->
            </div>
          </div><!--End Row-->

        </div><!--End Content Container-->
        <!--end::Container-->
      </div>
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer">
      <!--begin::Copyright-->
      <strong>
        Copyright &copy; 2014-2026&nbsp;
        <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
      </strong>
      All rights reserved.
      <!--end::Copyright-->
    </footer>
    <!--end::Footer-->
  </div>
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous" ></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="js/adminlte.js"></script>
  <script src="js/main.js"></script>
  <script src="js/index.js"></script>
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
  </script>
  <!--end::OverlayScrollbars Configure-->
  <!--end::Script-->
  <!--end::OverlayScrollbars Configure--><!--begin::Script-->
  <a href="#" class="scrollup" id="scrollup"><i class="bi bi-chevron-double-up"></i> เลื่อนขึ้น</a>    
</body>
<!--end::Body-->
</html>