<?php
class MySetting
{
    public const Owner = 'Sopon.G'; // ชื่อเจ้าของลิขสิทธิ์
    public const Version = '1.0.0'; // เวอร์ชันของโปรเจค
    public const AppName = 'CCMS'; // ชื่อแอปพลิเคชัน
    public const Hash = '#ccmS@2026'; // ค่าคงที่สำหรับการเข้ารหัสรหัสผ่าน (ใช้ร่วมกับ password_hash และ password_verify)

    public const role = array(
        1 => array( // กำหนดสิทธิ์สำหรับผู้ใช้ที่มี role == 1 (Admin)
            'home' => '/#/app/home/',
            'dashboard' => '/#/app/dashboard/',
            'masterData' => array(
                'company' => '/#/app/company-list/',
                'user' => '/#/app/user-list/',
            ),
            'setting' => '/#/app/setting/',
            ),
        2 => array( // กำหนดสิทธิ์สำหรับผู้ใช้ที่มี role == 2 (User)
            'home' => '/#/app/home/',
            'dashboard' => '/#/app/dashboard/',
            ),
    );
}
?>