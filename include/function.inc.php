<?php

function renderView($filePath) {
    //ฟังก์ชันนี้ใช้สำหรับโหลดไฟล์ view และเก็บผลลัพธ์ที่ถูกส่งออกมาใน buffer เพื่อให้สามารถนำไปใช้ต่อได้ เช่น ส่งกลับเป็น JSON ใน fetch.inc.php
    if (!file_exists($filePath)) {
        return ""; // หรือส่งข้อความ Error กลับไป
    }
    ob_start();
    include $filePath;
    return ob_get_clean();
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
function generateRandomNumber($length = 10) {
    $numbers = '0123456789';
    $numbersLength = strlen($numbers);
    $randomNumber = '';
    for ($i = 0; $i < $length; $i++) {
        $randomNumber .= $numbers[random_int(0, $numbersLength - 1)];
    }
    return $randomNumber;
}

function generateRandomDateTime() {
    $timestamp = random_int(strtotime('2020-01-01 00:00:00'), strtotime('2025-12-31 23:59:59'));
    return date('Y-m-d H:i:s', $timestamp);
}

/**
 * ฟังก์ชันสำหรับทำความสะอาดข้อมูลเบื้องต้น 
 * ป้องกัน HTML Injection และจัดการช่องว่าง
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        // ถ้าส่งมาเป็น Array (เช่น $_POST) ให้วนลูปจัดการทุกตัว
        return array_map('sanitizeInput', $data);
    }

    if (is_string($data)) {
        // 1. ลบ Tag HTML/PHP ออกเพื่อป้องกัน XSS (Cross-Site Scripting)
        // หมายเหตุ: strip_tags จะลบพวก <b>, <script>, <a> ออกทั้งหมด
        $data = strip_tags($data);

        // 2. ตัดช่องว่างหัว-ท้าย
        $data = trim($data);
        
        // 3. แปลงตัวอักษรพิเศษเป็น HTML Entities (ป้องกันการหลุดกรอบ HTML)
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    return $data;

    /*Ex.การใช้งาน
        $fullname = sanitizeInput($_POST['fullname']);
        $email    = sanitizeInput($_POST['email']);
        $remark   = sanitizeInput($_POST['remark']);    

    // คลีนค่าทั้งหมดใน $_POST ครั้งเดียว
    $cleanPOST = sanitizeInput($_POST);

    $fullname = $cleanPOST['fullname'];
    $email    = $cleanPOST['email'];        
    */
}

?>