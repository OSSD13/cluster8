<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ดึงค่าจาก .env
$host = $_ENV['10.80.6.165'];
$dbname = $_ENV['cluster8'];
$username = $_ENV['cluster8'];
$password = $_ENV['k4PL1Wqq'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ: " . $e->getMessage());
}

// รับค่า POST
if (isset($_POST['work_request_id'])) {
    $work_request_id = $_POST['work_request_id'];

    $sql = "SELECT 
                wro.work_name,
                wro.work_create_date,
                u.user_fname,
                u.user_lname,
                d.department_name
            FROM work_request_order AS wro
            LEFT JOIN users u ON wro.work_create_by_user_id = u.user_id
            LEFT JOIN departments d ON wro.work_create_department_id = d.department_id
            WHERE wro.work_request_id = :work_request_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':work_request_id', $work_request_id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'ไม่พบรหัส work_request_id']);
}


?>