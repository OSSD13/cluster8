<?php
// 1. เชื่อมต่อฐานข้อมูล MySQL
$pdo = new PDO('mysql:host=10.80.6.165;dbname=cluster8;charset=utf8', 'cluster8', 'k4PL1Wqq');

// 2. กำหนดการตั้งค่าการแบ่งหน้า
$items_per_page = 50; // จำนวนรายการที่จะแสดงในแต่ละหน้า (50 รายการต่อหน้า)
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // ตรวจสอบหน้าปัจจุบัน
$offset = ($current_page - 1) * $items_per_page; // คำนวณ offset


// 3. ดึงข้อมูลคำขอที่สร้างภายใน 5 วันที่ผ่านมา โดยจำกัดการแสดงผลตามหน้า
$sql = "SELECT work_request_id, work_name, work_create_date, work_submit_date, work_create_by_user_id, work_status,
        user_fname, user_lname, department_name
        FROM work_request_order
        LEFT JOIN users ON work_create_by_user_id = user_id
        LEFT JOIN departments ON work_created_by_department_id = department_id
        WHERE work_submit_date >= NOW() - INTERVAL 5 DAY
        LIMIT :offset, :limit"; // ใช้ LIMIT สำหรับการแบ่งหน้า

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. คำนวณจำนวนหน้าทั้งหมด
$sql_count = "SELECT COUNT(*) FROM work_request_order WHERE work_submit_date >= NOW() - INTERVAL 5 DAY";
$countStmt = $pdo->query($sql_count);
$total_item = $countStmt->fetchColumn();
$total_pages = ceil($total_item / $items_per_page); // คำนวณจำนวนหน้าทั้งหมด
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-900" x-data="{ isOpen: false }">
    <!-- Sidebar - Fixed Position -->
    <div class="w-60 bg-[#ffffff] shadow-lg fixed h-full">
        <div class="p-4 border-b">
            <div class="flex items-center">
                <img src="{{ asset('public/wrslogo.png') }}" alt="WorkRequest System Logo" class="mr-3 h-13">
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="py-4">
            <a href="home"
                class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-home mr-3"></i>
                <span>หน้าหลัก</span>
            </a>
            <a href="workrequest" class="flex items-center px-4 py-3 bg-[#3b82f6] text-[#ffffff] rounded-lg mx-2 mb-2">
                <i class="fas fa-clipboard-list mr-3"></i>
                <span>สร้างใบสั่งงาน</span>
            </a>
            <a href="report"
                class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-chart-line mr-3"></i>
                <span>รายงานการดำเนินงาน</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 ml-60">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#0012E1]">สร้างใบสั่งงาน</h1>
            <!-- ปุ่มเพิ่มคำขอ -->
            <button @click="isOpen = true"
                class="w-12 h-12 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-blue-700 transition">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md h-[506px] w-[900px]">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold">ประวัติ</h2>
                    <p class="text-sm text-gray-500">ใบสั่งงานที่ดำเนินการเสร็จสิ้นและถูกยกเลิก</p>

                    <div>
                        <p class="text-sm text-gray-500"> จาก {{$total_item}}</p>
                    </div>
                    <div class="button-container">
                        <button onclick="location.href='?page=<?= max(1, $current_page - 1) ?>'" class="px-4 py-2" style="background-image: url('public/asset/Vector (1).png'); background-repeat: no-repeat; background-position: center;" <?= $current_page === 1 ? 'disabled' : '' ?>></button>
                        <button onclick="location.href='?page=<?= min($total_pages, $current_page + 1) ?>'" class="px-4 py-2" style="background-image: url('public/asset/Vector.png'); background-repeat: no-repeat; background-position: center;" <?= $current_page === $total_pages ? 'disabled' : '' ?>></button>
                    </div>

                </div>
                <div class="overflow-y-auto h-[400px] pr-2 scrollbar-hide">
                    <div class="mt-4 grid grid-cols-3 gap-4">
                        <?php foreach ($data as $row): ?>
                        <?php if (in_array($row['work_status'], ['C', 'D'])): ?>
                            <div class="p-4 rounded-lg <?= $row['work_status'] === 'C' ? 'bg-green-100' : 'bg-red-100' ?>">
                            <p class="font-semibold"><?= htmlspecialchars($row['work_name']) ?></p>

                        <?php
                        // ตรวจสอบว่าผู้ขอเป็นบุคคลหรือแผนก
                        $isPersonalRequest = !empty($row['user_fname']) && !empty($row['user_lname']);
                        ?>

                        <?php if ($isPersonalRequest): ?>
                        <p class="text-sm">ชื่อ/แผนกผู้ร้องขอ: <?= htmlspecialchars($row['user_fname']) ?>
                            <?= htmlspecialchars($row['user_lname']) ?>
                        <?php else: ?>
                        <p class="text-sm">แผนกผู้ร้องขอ: <?= htmlspecialchars($row['department_name']) ?></p>
                        <?php endif; ?>

                        <p class="text-sm">สถานะ: <?= $row['work_status'] === 'C' ? 'เสร็จสิ้น' : 'ยกเลิก' ?></p>
                        <p class="text-sm">วันที่เสร็จสิ้น/ยกเลิก: <?= htmlspecialchars($row['work_submit_date']) ?></p>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>

                </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-lg font-semibold">สร้างคำขอใหม่</h2>
                <button @click="isOpen = false" class="text-gray-600 hover:text-black-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-4">
                <label class="block text-gray-700">ชื่อคำขอ</label>
                <input type="text" class="w-full p-2 border rounded-lg mt-1" placeholder="กรอกชื่อคำขอ">
                <label class="block text-gray-700 mt-2">วันที่ต้องการ</label>
                <input type="date" class="w-full p-2 border rounded-lg mt-1">
            </div>
            <div class="flex justify-end mt-4">
                <button @click="isOpen = false"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 mr-2">ยกเลิก</button>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">ส่ง</button>
            </div>
        </div>
    </div>
</body>

</html>
