<?php
session_start();
// เช็คว่ามีการส่งค่ามาแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit("Direct access is not allowed.");
}
 echo '<pre>' . print_r($_SESSION, true) . '</pre>';
?>
<h1>Hello Dashboard</h1>
</p>Welcome to the Dashboard page! This is a placeholder content.</p>
<a href="#/app/logout" class="btn btn-danger">Logout</a>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />