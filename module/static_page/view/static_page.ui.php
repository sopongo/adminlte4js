<?php
session_start();
require_once APP_ROOT . '/include/error_report.inc.php'; // รวมไฟล์ error_report.inc.php เพื่อกำหนดการแสดงข้อผิดพลาดและตั้งค่าโซนเวลา
require_once APP_ROOT . '/include/auth.inc.php'; // รวมไฟล์ auth.inc.php

?>
<h1>Hello Static Page</h1>
<div class="col-md-6 bg-light p-3 border rounded">
    <p>รูปแบบการแสดงผลแบบ Client-Side Rendering (CSR) - Server-Side Rendering (SSR) คือ วิธีการแสดงผลหน้าเว็บไซต์ที่เบราว์เซอร์ของผู้ใช้ (Client) ดาวน์โหลดไฟล์ JavaScript หลัก และประมวลผลเพื่อสร้างเนื้อหา (HTML) ขึ้นมาเอง โดยเซิร์ฟเวอร์จะส่ง HTML เปล่าๆ พร้อมไฟล์ JS ไปให้ แทนที่จะส่งหน้าเว็บที่สมบูรณ์มาเลย เหมาะสำหรับ Single Page Application (SPA) ที่ต้องการความรวดเร็วในการ</p>

    <p>สามารถอ่านค่าจาก URL hash ได้โดยใช้ JavaScript ฝั่ง Client เพื่อดึงข้อมูลที่ต้องการแสดงผล เช่น #/app/fetch-static-page/1234 ซึ่งสามารถใช้ JavaScript ในการแยกส่วนของ URL hash เพื่อดึงค่า 1234 มาใช้งานได้ตามต้องการ</p>
    <a href="#/app/fetch-static-page/1234" class="btn btn-danger">#/app/fetch-static-page/1234</a> <br /><br />
    <?php

        $action = $_GET['p'] ?? ''; // รับค่าพารามิเตอร์ 'p' จาก URL เพื่อใช้ในการกำหนดการทำงานของสคริปต์ เช่น การโหลดหน้า Dashboard, Config หรือการตรวจสอบการล็อกอิน เป็นต้น

        if (!empty($_POST)) { // --- 1. ตรวจสอบข้อมูลจาก $_POST (สำหรับ FormData / Multipart) ---
            $data = $_POST;
        }else { // --- 2. ถ้า $_POST ว่าง ให้ลองอ่านจาก JSON (php://input) ---
            $json = file_get_contents('php://input');
            $data = json_decode($json, true) ?? [];
        }

        $data = sanitizeInput($data); // ทำความสะอาดข้อมูลที่ได้รับมา

        echo '$data = ';
        echo '<pre>';
        print_r($data); // แสดงข้อมูลที่ได้รับมาเพื่อการดีบัก
        echo '</pre>';

    ?>
</div>
