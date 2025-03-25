<?php
// 1. เชื่อมต่อฐานข้อมูล MySQL
$pdo = new PDO("mysql:host=10.80.6.165;dbname=cluster8;charset=utf8", "cluster8", "k4PL1Wqq");

// 2. ดึงข้อมูลคำขอที่สร้างภายใน 5 วันที่ผ่านมา
$sql = "SELECT wrq_id, wrq_name, wrq_create_date, wrq_finish_date, wrq_user_id FROM works_requests 
        WHERE wrq_finish_date >= NOW() - INTERVAL 5 DAY";

$stmt = $pdo->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Header -->
    <header class="bg-blue-600 text-white py-4 text-center text-xl font-bold">
        Work Request System
    </header>

    <!-- Main Content -->
    <main class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold">รายการคำขอใน 5 วันที่ผ่านมา</h2>

            <!-- ตารางแสดงคำขอ -->
            <div class="mt-4">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2">ID</th>
                            <th class="border p-2">ชื่อคำขอ</th>
                            <th class="border p-2">วันที่สร้าง</th>
                            <th class="border p-2">วันที่เสร็จ</th>
                            <th class="border p-2">ผู้ใช้</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                        <tr class="border">
                            <td class="p-2 text-center"><?= htmlspecialchars($row['wrq_id']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($row['wrq_name']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['wrq_create_date']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['wrq_finish_date']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['wrq_user_id']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- ปุ่มเพิ่มคำขอ -->
            <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus"></i> สร้างคำขอใหม่
            </button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-4 text-gray-600">
        &copy; 2025 Work Request System
    </footer>

</body>
</html>