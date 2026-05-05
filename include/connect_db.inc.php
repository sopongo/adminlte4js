<?php
require_once __DIR__ . "/mysecret.php";

class Database
{
    private static $instance = null;
    protected $conn;

    public function __construct()
    {
        // เลือก config จาก MySecret
        $config = (MySecret::$conNow === 'db') ? 
        [
            'db'   => MySecret::$dbDatabase,
            'user' => MySecret::$dbUser,
            'pass' => MySecret::$dbPass,
            'host' => MySecret::$dbServer,
            'port' => MySecret::$dbPort
        ] : [
            'db'   => MySecret::$LocalDatabase,
            'user' => MySecret::$LocalUser,
            'pass' => MySecret::$LocalPass,
            'host' => MySecret::$LocalServer,
            'port' => MySecret::$LocalPort
        ];

        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['db']};charset=utf8mb4";
                        
            $options = [
                // 1. เปิดโหมด Exception เมื่อเกิด Error (คุณมีอยู่แล้ว)
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                // 2. ปิดการจำลองการเตรียมคำสั่ง (ช่วยเรื่องความปลอดภัยและชนิดข้อมูล)
                PDO::ATTR_EMULATE_PREPARES   => false,
                // 3. กำหนดให้ผลลัพธ์ที่ดึงออกมาเป็น Array ชื่อคอลัมน์เสมอ
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // 4. บังคับใช้ UTF-8 (คุณมีอยู่แล้ว แต่แนะนำให้ใส่ไว้ใน DSN ด้วย)
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'",
                // 5. ปิด Persistent Connection (ตามที่เคยแนะนำ)
                PDO::ATTR_PERSISTENT         => false,
                // 6. แปลงค่าทศนิยมเป็น string เพื่อความแม่นยำกรณีเลขเยอะมากๆ (Optional)
                // PDO::ATTR_STRINGIFY_FETCHES => false,
                // 7. แปลงค่า NULL เป็น string ว่างเมื่อดึงข้อมูล (Optional)                
                PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING, 
            ];

            $this->conn = new PDO($dsn, $config['user'], $config['pass'], $options);
            $this->conn->exec("SET time_zone = '+07:00'"); // สำหรับเวลาประเทศไทย
            //echo "Database connection established successfully."; // ข้อความยืนยันการเชื่อมต่อ (สามารถลบออกได้ใน production)
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            die("ระบบขัดข้อง: ไม่สามารถเชื่อมต่อฐานข้อมูลได้");
        }
    }

    // ฟังก์ชันสำหรับเรียกใช้งาน Instance เดียวกันทั้งระบบ
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // ฟังก์ชันตรวจสอบและดึงการเชื่อมต่อ
    public function getConnection()
    {
        if ($this->conn instanceof PDO) {
            return $this->conn;
        } else {
            // กรณีที่การเชื่อมต่อหลุดหายไปให้ลองสร้างใหม่
            self::$instance = new Database();
            return self::$instance->conn;
        }
    }
}