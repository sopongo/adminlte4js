<?php
session_start();

define('APP_ROOT', __DIR__); // กำหนดค่าคงที่ APP_ROOT เป็นเส้นทางของไดเรกทอรีปัจจุบัน ซึ่งจะใช้ในการอ้างอิงเส้นทางของไฟล์ต่าง ๆ ในโปรเจกต์ได้อย่างสะดวกและปลอดภัยมากขึ้น โดยไม่ต้องกังวลเกี่ยวกับเส้นทางสัมพัทธ์ที่อาจทำให้เกิดปัญหาในการโหลดไฟล์ในบางกรณี
require_once APP_ROOT . '/include/error_report.inc.php'; // รวมไฟล์ error_report.inc.php เพื่อกำหนดการแสดงข้อผิดพลาดและตั้งค่าโซนเวลา
require_once APP_ROOT . '/include/auth.inc.php'; // รวมไฟล์ auth.inc.php 

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินแล้วหรือไม่ โดยตรวจสอบว่ามีค่า user_id ใน session หรือไม่
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit("You must be logged in to access this resource.");
}

// เช็คว่ามีการส่งค่ามาแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit("Direct access is not allowed.");
}

/*
    $checkRole = $resultLogin['role'] ?? 2; // กำหนดค่าเริ่มต้นเป็น 2 (User) หากไม่มีข้อมูล role ในฐานข้อมูล
    $_SESSION['permissions'] = MySetting::role[$checkRole] ?? []; // เก็บสิทธิ์การเข้าถึงใน session เพื่อให้สามารถตรวจสอบสิทธิ์การเข้าถึงในแต่ละหน้าได้

    foreach($_SESSION['permissions'] as $key => $value) {
        if (is_array($value)) {
            foreach($value as $subKey => $subValue) {
                $result_html_menu .= '<li class="nav-item"><a href="' . $subValue . '" class="nav-link">' . ucfirst($subKey) . '</a></li>';
            }
        } else {
            $result_html_menu .= '<li class="nav-item"><a href="' . $value . '" class="nav-link">' . ucfirst($key) . '</a></li>';
        }
    }
*/
?>
              <div class="nav-search p-1">
                <div class="input-group">
                  <input type="text" id="sidebar-search" class="form-control form-control-sm bg-white text-dark border-secondary" placeholder="ค้นหาเมนู...">
                </div>
              </div>

            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="true" id="navigation">
              <li class="nav-item"><a href="#/app/home" class="nav-link"><i class="nav-icon bi bi-house-fill"></i><p>หน้าแรก</p></a></li>
              <li class="nav-item"><a href="#/app/fetch-static-page" class="nav-link"><i class="nav-icon bi bi-file-earmark-text"></i><p>Fetch Static Page</p></a></li>
              <li class="nav-item"><a href="#/app/datatable" class="nav-link"><i class="nav-icon bi bi-postcard"></i><p>Datatable</p></a></li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-database"></i><p>Master Data<i class="nav-arrow bi bi-chevron-right"></i></p></a>
                <ul class="nav nav-treeview" role="navigation" aria-label="Navigation 10" style="box-sizing: border-box;">
                  <li class="nav-item ps-2"><a href="#/app/company-list" class="nav-link"><i class="small nav-icon bi bi-chevron-double-right"></i><p>Company</p></a></li>
                  <li class="nav-item ps-2"><a href="#/app/user-list" class="nav-link"><i class="small nav-icon bi bi-chevron-double-right"></i><p>User</p></a></li>
                  <li class="nav-item ps-2"><a href="#/app/machine-list" class="nav-link"><i class="small nav-icon bi bi-chevron-double-right"></i><p>Machine</p></a></li>
                  <li class="nav-item ps-2"><a href="#/app/unit-list" class="nav-link"><i class="small nav-icon bi bi-chevron-double-right"></i><p>Unit</p></a></li>
                  <li class="nav-item">
                    <a href="#" class="nav-link"><i class="nav-icon bi bi-box-arrow-in-right"></i><p>xxxxxxx <i class="nav-arrow bi bi-chevron-right"></i></p></a>
                    <ul class="nav nav-treeview" role="navigation" aria-label="Navigation 11" style="box-sizing: border-box;">
                      <li class="nav-item">
                        <a href="examples/login.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Login</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="examples/register.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Register</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-box-arrow-in-right"></i>
                      <p>
                        Version 2
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview" role="navigation" aria-label="Navigation 12" style="box-sizing: border-box;">
                      <li class="nav-item">
                        <a href="examples/login-v2.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Login</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="examples/register-v2.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Register</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item"><a href="examples/lockscreen.html" class="nav-link"><i class="nav-icon bi bi-circle"></i><p>Lockscreen</p></a></li>
                </ul>
              </li>              
              <li class="nav-item"><a href="#/app/dashboard" class="nav-link"><i class="nav-icon bi bi-house-fill"></i><p>Dashboard</p></a></li>
              <li class="nav-item"><a href="#/app/config" class="nav-link"><i class="nav-icon bi bi-house-fill"></i><p>Config</p></a></li>
              <li class="nav-item"><a href="#/app/asset-list" class="nav-link"><i class="nav-icon bi bi-postcard"></i><p>Asset List</p></a></li>
              <li class="nav-item"><a href="#/app/asset-detail/1234" class="nav-link"><i class="nav-icon bi bi-postcard"></i><p>Asset ID=1234 </p></a></li>
              <li class="nav-item"><a href="#/app/machine-list" class="nav-link"><i class="nav-icon bi bi-postcard"></i><p>Machine List</p></a></li>
              <li class="nav-item"><a href="#/app/crud/657" class="nav-link"><i class="nav-icon bi bi-postcard"></i><p>Class CRUD</p></a></li>
            </ul>
            <!--end::Sidebar Menu-->
