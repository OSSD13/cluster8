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
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 ml-60">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#0012E1]">สร้างใบสั่งงาน</h1>
        </div>
        <main class="container mx-auto p-4">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold">รายการคำขอใน 5 วันที่ผ่านมา</h2>
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
                <button @click="isOpen = true" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus"></i> สร้างคำขอใหม่
                </button>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div x-show="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-lg font-semibold">สร้างคำขอใหม่</h2>
                <button @click="isOpen = false" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ url('/work_request') }}" method="POST">
                @csrf

                <!-- ชื่อเรื่อง / วันที่ร้องขอ -->
                <div class="flex justify-between mb-4 mt-4">
                    <div>ชื่อเรื่อง : <input type="text" name="work_name" class="border p-2 rounded w-full" placeholder="กรุณากรอกชื่อเรื่อง" required>
                    </div>
                    <div>วันที่ร้องขอ : </div>
                </div>

                <!-- ผู้ส่ง / แผนก -->
                <div class="flex justify-between items-center mb-4">
                    <div>ผู้ส่ง : <strong></strong></div>
                    <div class="flex items-center space-x-4">
                        <span>แผนก <span class="text-red-500">* </span>:</span>
                        <label><input type="radio" name="work_author_type" value="ระบุ" checked> ระบุ</label>
                        <label><input type="radio" name="work_author_type" value="ไม่ระบุ"> ไม่ระบุ</label>
                    </div>
                </div>

                <hr class="mb-4">

                <!-- งานย่อยแบบ dynamic -->
                <template x-for="(task, index) in tasks" :key="index">
                    <div class="flex items-center bg-gray-50 p-3 rounded-lg mb-2 shadow">
                        <input type="text" name="sub_tasks[][name]" class="w-full border p-2 rounded" x-model="task.name" placeholder="ชื่อรายการงาน">
                        <input type="date" name="sub_tasks[][due_date]" class="ml-4 border p-2 rounded text-sm" x-model="task.due_date">
                        <input type="text" name="sub_tasks[][department]" class="ml-4 border p-2 rounded text-sm w-20" x-model="task.department" placeholder="แผนก">
                    </div>
                </template>

                <!-- เพิ่มรายการใหม่ -->
                <div class="flex space-x-2 items-center mt-3">
                    <button type="button" @click="addTask" class="text-xl font-bold text-blue-500">+</button>
                    <span class="text-sm">เพิ่มรายการ</span>
                </div>

                <!-- ปุ่มส่ง -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="submit" class="px-6 py-2 border border-black rounded">แบบร่าง</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">ส่ง</button>
                </div>
            </form>
        </div>

    </div>
</body>

</html>
