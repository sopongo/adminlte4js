<?php
ini_set('error_reporting', E_ALL); // แสดงข้อผิดพลาดทั้งหมดเพื่อช่วยในการพัฒนาและดีบัก
ini_set('display_errors', true); // แสดงข้อผิดพลาดบนหน้าเว็บเพื่อช่วยในการพัฒนาและดีบัก
error_reporting(error_reporting() & ~E_NOTICE); // ปิดการแสดงข้อผิดพลาดประเภท Notice ซึ่งมักเกิดจากการใช้ตัวแปรที่ยังไม่ได้กำหนดค่า หรือการเข้าถึง index ที่ไม่มีอยู่ใน array เพื่อให้การแสดงผลสะอาดขึ้นและไม่รบกวนผู้ใช้มากเกินไป
date_default_timezone_set('Asia/Bangkok');
?>