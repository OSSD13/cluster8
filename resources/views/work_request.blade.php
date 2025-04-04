<?php
// 1. เชื่อมต่อฐานข้อมูล MySQL
$pdo = new PDO("mysql:host=10.80.6.165;dbname=cluster8;charset=utf8", "cluster8", "k4PL1Wqq");

// 2. ดึงข้อมูลคำขอที่สร้างภายใน 5 วันที่ผ่านมา
$sql = "SELECT work_request_id, work_name, work_create_date, work_submit_date, work_create_by_user_id FROM work_request_order 
        WHERE work_submit_date >= NOW() - INTERVAL 5 DAY";

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
<!-- Sidebar - Fixed Position -->
<div class="w-60 bg-[#ffffff] shadow-lg fixed h-full">
        <div class="p-4 border-b">
            <div class="flex items-center">
                <img src="{{ asset('public\wrslogo.png') }}" alt="WorkRequest System Logo" class="mr-3 h-13">
            </div>
        </div>
    
        <!-- Sidebar Menu -->
        <div class="py-4">
            <a href="home" class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-home mr-3"></i>
                <span>หน้าหลัก</span>
            </a>
            
            
            <a href="workrequest" class="flex items-center px-4 py-3 bg-[#3b82f6] text-[#ffffff] rounded-lg mx-2 mb-2">
                <i class="fas fa-clipboard-list mr-3"></i>
                <span>สร้างใบสั่งงาน</span>
            </a>
            
            <a href="report" class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-chart-line mr-3"></i>
                <span>รายงานการดำเนินงาน</span>
            </a>
        </div>
        
        <!-- User Profile -->
        <div class="absolute bottom-0 w-60 p-2">
            <div class="flex items-center bg-[#1e3a8a] text-[#ffffff] p-2 rounded-lg">
                <div class="relative">
                    <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-full w-10 h-10">
                </div>
                <div class="ml-2">
                    <div class="font-semibold">จิรายุท คนโก้</div>
                    <div class="text-xs">anita@commerce.com</div>
                </div>
                <div class="ml-auto">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
        </div>
    </div>
<body class="bg-gray-100 text-gray-900">
    <!-- Main Content -->
    <div class="flex-1 p-8 ml-60">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#0012E1]">สร้างใบสั่งงาน</h1>
        </div>
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
                            <td class="p-2 text-center"><?= htmlspecialchars($row['work_request_id']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['work_name']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['work_create_date']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['work_submit_date']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['work_create_by_user_id']) ?></td>
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
    </div>
    

</body>
</html>