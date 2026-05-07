<?php
session_start();
define('APP_ROOT', __DIR__); // กำหนดค่าคงที่ APP_ROOT เป็นเส้นทางของไดเรกทอรีปัจจุบัน ซึ่งจะใช้ในการอ้างอิงเส้นทางของไฟล์ต่าง ๆ ในโปรเจกต์ได้อย่างสะดวกและปลอดภัยมากขึ้น โดยไม่ต้องกังวลเกี่ยวกับเส้นทางสัมพัทธ์ที่อาจทำให้เกิดปัญหาในการโหลดไฟล์ในบางกรณี

require_once APP_ROOT . '/include/error_report.inc.php'; // รวมไฟล์ error_report.inc.php เพื่อกำหนดการแสดงข้อผิดพลาดและตั้งค่าโซนเวลา

header('Content-Type: application/json; charset=utf-8'); // ตอบกลับเป็น JSON

## ต้องเปิด Zend OPcache เพื่อให้โหลดไฟล์นี้ครั้งแรกแล้วเก็บไว้ใน cache เพื่อให้การเรียกใช้งานครั้งถัดไปเร็วขึ้น 
## กรณีรันบน laragon ให้ดูที่ extensions > opcache ว่าติกถูกเปิดใช้งานหรือไม่ และตั้งค่า opcache.enable=1 ใน php.ini ด้วย
require_once APP_ROOT . '/include/auth.inc.php'; // รวมไฟล์ auth.inc.php 
include_once (APP_ROOT . '/include/function.inc.php');
require_once (APP_ROOT . '/include/connect_db.inc.php');
require_once (APP_ROOT . '/include/setting.inc.php');
require_once (APP_ROOT . '/include/language.inc.php');
require_once (APP_ROOT . '/include/class_crud.inc.php');

Language::lang_Login&&Language::lang_menu; // โหลดภาษาที่ใช้ในหน้า Login และเมนูจากคลาส Language


// เช็คว่ามีการส่งค่ามาแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit("Direct access is not allowed.");
}

$objCrud = new CRUD(); // สร้างออปเจค $objCrud เพื่อเรียกใช้งานคลาส,ฟังก์ชั่นต่างๆที่อยู่ใน class CRUD ซึ่งเชื่อมต่อกับฐานข้อมูลแล้ว


$action = $_GET['p'] ?? ''; // รับค่าพารามิเตอร์ 'p' จาก URL เพื่อใช้ในการกำหนดการทำงานของสคริปต์ เช่น การโหลดหน้า Dashboard, Config หรือการตรวจสอบการล็อกอิน เป็นต้น

if (!empty($_POST)) { // --- 1. ตรวจสอบข้อมูลจาก $_POST (สำหรับ FormData / Multipart) ---
    $data = $_POST;
}else { // --- 2. ถ้า $_POST ว่าง ให้ลองอ่านจาก JSON (php://input) ---
    $json = file_get_contents('php://input');
    $data = json_decode($json, true) ?? [];
}

// 2. เก็บ Password ดิบไว้ก่อนจะโดน Sanitize
$raw_password = $data['password'] ?? '';

$data = sanitizeInput($data); // ทำความสะอาดข้อมูลที่ได้รับมา

switch ($action) {
    case 'fetch-static-page':
        echo json_encode([
            'status'      => 'success',
            'http_code'   => http_response_code(200), // เบื้องต้นกำหนดสถานะการตอบกลับเป็น 200 OK
            'message'     => 'Static page fetched successfully',
            'meta-title' => 'Static Page', // ส่ง meta-title กลับไปเพื่อให้ JavaScript ฝั่ง Client สามารถตั้งค่า Title ของหน้าเว็บได้ตามที่ Backend กำหนด
            'title-page' => 'Static Page',
            'sub-title-page' => ($data['id'] ?? '') ? '<i class="bi bi-chevron-double-right small"></i> Sub Title ID: ' . htmlspecialchars($data['id'], ENT_QUOTES, 'UTF-8') : '', // ส่ง sub-title-page กลับไปเพื่อให้ JavaScript ฝั่ง Client สามารถตั้งค่า Sub Title ของหน้าเว็บได้ตามที่ Backend กำหนด โดยใช้ข้อมูลจาก $data['id'] ถ้ามี และทำความสะอาดข้อมูลด้วย htmlspecialchars เพื่อป้องกันการหลุดกรอบ HTML
            'result_html' => renderView('module/static_page/view/static_page.ui.php'),
            'result_js'   => renderView('module/static_page/control/static_page.js.php')
        ]);
        
    break;
    case 'logout':
        session_destroy(); // ทำลาย session ทั้งหมดเพื่อออกจากระบบ
        //header('location: ./'); // รีเฟรชหน้าใหม่เพื่อให้กลับไปที่หน้า Login
        echo json_encode([
            'status' => 'success', 
            'http_code' => http_response_code(200), 
            'message' => 'Logout successful', 
            'result_html' => '<script>window.location.href = "./";</script>', // ไม่มี HTML ที่จะส่งกลับมา
            'result_js' => '' // ไม่มี JavaScript ที่จะส่งกลับมา
        ]);
    break;
    case 'config':
        //$result_html = renderView('module/config/view/config.ui.php');
        echo json_encode([
            'status'      => 'success',
            'http_code'   => http_response_code(200), // เบื้องต้นกำหนดสถานะการตอบกลับเป็น 200 OK
            'message'     => 'Config page fetched successfully',
            'title-page' => 'Config', // ส่ง title-page กลับไปเพื่อให้ JavaScript ฝั่ง Client สามารถตั้งค่า Title ของหน้าเว็บได้ตามที่ Backend กำหนด
            'result_html' => renderView('module/config/view/config.ui.php'),
            'result_js'   => '' // ถ้าไม่มี JavaScript ที่จะส่งกลับมา ให้ส่งเป็นค่าว่างไป
        ]);
    break;

    case 'asset-list':
        //$result_html = renderView('module/login/view/view.inc.php');
        echo json_encode([
            'status'      => 'success',
            'http_code'   => http_response_code(200), // เบื้องต้นกำหนดสถานะการตอบกลับเป็น 200 OK
            'message'     => 'Login page fetched successfully',
            'meta-title' => 'Login', // ส่ง meta-title กลับไปเพื่อให้ JavaScript ฝั่ง Client สามารถตั้งค่า Title ของหน้าเว็บได้ตามที่ Backend กำหนด
            'title-page' => 'Login',
            'result_html' => renderView('module/login/view/login.ui.php'),
            'result_js'   => renderView('module/login/view/login.js.php')
        ]);
    break;

    case 'user-list':
        //$result_html = renderView('module/login/view/view.inc.php');
        echo json_encode([
            'status'      => 'success',
            'http_code'   => http_response_code(200), // เบื้องต้นกำหนดสถานะการตอบกลับเป็น 200 OK
            'message'     => 'User List page fetched successfully',
            'meta-title' => 'User List', // ส่ง meta-title กลับไปเพื่อให้ JavaScript ฝั่ง Client สามารถตั้งค่า Title ของหน้าเว็บได้ตามที่ Backend กำหนด
            'title-page' => 'User List',
            'result_html' => renderView('module/user/view/user.list.ui.php'),
            'result_js'   => renderView('module/user/view/user.list.js.php')
        ]);
    break;

    case 'company-list':
        //$result_html = renderView('module/login/view/view.inc.php');
        echo json_encode([
            'status'      => 'success',
            'http_code'   => http_response_code(200), // เบื้องต้นกำหนดสถานะการตอบกลับเป็น 200 OK
            'message'     => 'Company List page fetched successfully',
            'meta-title' => Language::lang_company[$_SESSION['lang']]['text_2'], // ส่ง meta-title กลับไปเพื่อให้ JavaScript ฝั่ง Client สามารถตั้งค่า Title ของหน้าเว็บได้ตามที่ Backend กำหนด
            'title-page' => Language::lang_company[$_SESSION['lang']]['text_2'],
            'result_html' => renderView('module/company/view/company.list.ui.php'),
            'result_js'   => renderView('module/company/view/company.list.js.php')
        ]);
    break;

    case 'dashboard':
    case 'home':
        //$result_html = renderView('module/dashboard/view/dashboard.ui.php');
        echo json_encode([
            'status'      => 'success',
            'http_code'   => http_response_code(200), // เบื้องต้นกำหนดสถานะการตอบกลับเป็น 200 OK
            'message'     => 'Dashboard page fetched successfully',
            'meta-title' => Language::lang_menu[$_SESSION['lang']]['text_3'], // ส่ง meta-title กลับไปเพื่อให้ JavaScript ฝั่ง Client สามารถตั้งค่า Title ของหน้าเว็บได้ตามที่ Backend กำหนด
            'title-page' => Language::lang_menu[$_SESSION['lang']]['text_3'],
            'result_html' => renderView('module/dashboard/view/dashboard.ui.php'),
            'result_js'   => '' // ถ้าไม่มี JavaScript ที่จะส่งกลับมา ให้ส่งเป็นค่าว่างไป
        ]);
    break;

    case 'login':
        //$result_html = renderView('module/login/view/view.inc.php');
        if (!empty($_SESSION['user_id'])) {
            echo json_encode([
                'status'    => 'success',
                'http_code' => http_response_code(200),
                'message'   => 'Already logged in, redirecting',
                'redirect'  => '#/app/dashboard' // ส่งคำสั่งให้ JavaScript ฝั่ง Client เปลี่ยนหน้าไปที่ Dashboard แทนที่จะโหลดหน้า Login ซ้ำอีกครั้ง
            ]);
            exit;
        }
        echo json_encode([
            'status'      => 'success',
            'http_code'   => http_response_code(200), // เบื้องต้นกำหนดสถานะการตอบกลับเป็น 200 OK
            'message'     => 'Login page fetched successfully',
            'meta-title' => Language::lang_Login[$_SESSION['lang']]['title_0'], // ส่ง meta-title กลับไปเพื่อให้ JavaScript ฝั่ง Client สามารถตั้งค่า Title ของหน้าเว็บได้ตามที่ Backend กำหนด
            'title-page' => Language::lang_Login[$_SESSION['lang']]['title_0'],
            'result_html' => renderView('module/login/view/login.ui.php'),
            'result_js'   => renderView('module/login/view/login.js.php')
        ]);
    break;

    case 'chklogin':
        if (empty($data['email']) || empty($raw_password)) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'http_code' => http_response_code(400), 'message' => 'Email and Password are required', 'result_html' => '']);
            exit();
        }
        $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL); // ทำความสะอาด Email
        $raw_password = filter_var($raw_password, FILTER_UNSAFE_RAW); // ทำความสะอาด Password
        $raw_password = trim($raw_password); // ตัดช่องว่างรอบๆ Password
        $raw_password = htmlspecialchars($raw_password, ENT_QUOTES, 'UTF-8'); // แปลงตัวอักษรพิเศษใน Password เพื่อป้องกันการหลุดกรอบ HTML
        $raw_password = str_replace(['<', '>', '"', "'", ';', '--'], '', $raw_password); // ลบอักขระที่อาจเป็นอันตรายออกจาก Password

        $sqlQuery = "SELECT * FROM tb_user WHERE email = :email AND active = :active LIMIT 1";
        $resultLogin = $objCrud->getRow($sqlQuery, [':email' => $data['email'], ':active' => 1]);

        if ($resultLogin && isset($resultLogin['id_user']) && password_verify(MySetting::Hash . $raw_password, $resultLogin['password'])) {
            session_regenerate_id(true); // สร้าง session ID ใหม่เพื่อป้องกันการโจมตีแบบ Session Fixation
            $result_html = '';
            $result_html_menu = '';
            $_SESSION['user_id'] = $resultLogin['id_user']; // เก็บ user_id ใน session เพื่อใช้ในการตรวจสอบการล็อกอินในครั้งถัดไป
            $_SESSION['role'] = $resultLogin['role']; // เก็บ role ใน session เพื่อใช้ในการแสดงข้อมูลผู้ใช้ในหน้าเว็บ

            echo json_encode([
                'status' => 'success', 
                'http_code' => http_response_code(200), 
                'message' => 'Login successful', 
                'result_html' => $result_html,
                'result_html_menu' => $result_html_menu,
                'result_js' => '' // ถ้าไม่มี JavaScript ที่จะส่งกลับมา ให้ส่งเป็นค่าว่างไป
                ]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode([
                'status' => 'error', 
                'http_code' => http_response_code(200), // แม้จะเป็น error แต่ยังคงส่งสถานะ 200 OK เพื่อให้ JavaScript ฝั่ง Client สามารถรับข้อมูลและแสดงผลได้ตามปกติ
                'message' => Language::lang_Login[$_SESSION['lang']]['warning_4'],
                'result_html' => '<div class="alert alert-danger">' . Language::lang_Login[$_SESSION['lang']]['warning_4'] . '</div>',
                'result_html_menu' => '', // ถ้าไม่มีเมนูที่จะส่งกลับมา ให้ส่งเป็นค่าว่างไป
                'result_js' => '' // ถ้าไม่มี JavaScript ที่จะส่งกลับมา ให้ส่งเป็นค่าว่างไป
                ]);
        }
        break;
    default:
        ob_end_clean(); // ล้าง buffer ทิ้งถ้าเกิดกรณีที่ไม่มีการจับคู่กับ case ใดๆ เพื่อป้องกันการส่งข้อมูลที่ไม่ต้องการออกมา
        http_response_code(400); // กำหนดสถานะการตอบกลับเป็น 400 Bad Request
        echo json_encode([
            'status' => 'error', 
            'http_code' => http_response_code(400), 
            'message' => 'Invalid action', 
            'result_html' => '', 
            'result_html_menu' => '', 
            'result_js' => ''
        ]);
        break;
}
exit(); // จบการทำงานของสคริปต์หลังจากส่งข้อมูลตอบกลับแล้ว
?>