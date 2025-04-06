<?php
// เชื่อมต่อฐานข้อมูล MySQL
$pdo = new PDO("mysql:host=10.80.6.165;dbname=cluster8;charset=utf8", "cluster8", "k4PL1Wqq");

// กำหนดการตั้งค่าการแบ่งหน้า
$items_per_page = 50; // จำนวนรายการที่จะแสดงในแต่ละหน้า (50 รายการต่อหน้า)
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // ตรวจสอบหน้าปัจจุบัน
$offset = ($current_page - 1) * $items_per_page; // คำนวณ offset
session_start(); // เริ่มต้น session
// ลบค่า user_id ออกจาก session
unset($_SESSION['user_id']);


// ดึงข้อมูลคำขอที่สร้างภายใน 5 วันที่ผ่านมา โดยจำกัดการแสดงผลตามหน้า
$userID = session('users')->user_id;
$sql = "SELECT work_request_id, work_name, work_create_date, work_submit_date, work_create_by_user_id, work_status,
        user_fname, user_lname, department_name
        FROM work_request_order
        LEFT JOIN users ON work_create_by_user_id = user_id
        LEFT JOIN departments ON work_created_by_department_id = department_id
        WHERE work_create_by_user_id = $userID
        LIMIT :offset, :limit"; // ใช้ LIMIT สำหรับการแบ่งหน้า

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// คำนวณจำนวนหน้าทั้งหมด
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <title>Work Request System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
           font-family: 'Noto Sans Thai', sans-serif;
       }
       </style>
</head>

<body class="bg-gray-100 text-gray-900" x-data="{ isOpen: false }">
    <!-- Sidebar -->
    <div class="w-60 bg-white shadow-lg fixed h-full p-4">
        <img src="public/wrslogo.png" alt="WorkRequest System Logo" class="h-13 mb-4">
        <a href="home" class="flex items-center px-4 py-3 hover:bg-gray-200 rounded-lg mb-2"><i class="fas fa-home mr-3"></i>หน้าหลัก</a>
        <a href="workrequest" class="flex items-center px-4 py-3 bg-blue-500 text-white rounded-lg mb-2"><i class="fas fa-clipboard-list mr-3"></i>สร้างใบสั่งงาน</a>
        <a href="report" class="flex items-center px-4 py-3 hover:bg-gray-200 rounded-lg mb-2"><i class="fas fa-chart-line mr-3"></i>รายงาน</a>
    </div>
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-600">สร้างใบสั่งงาน </h1>
             <!-- ปุ่มเพิ่มคำขอ -->
             <button @click="isOpen = true"
             class="w-12 h-12 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-blue-700 transition">
             <i class="fas fa-plus"></i>
         </button>
        </div>
        
        <!-- Hide Scroll Bar -->
        <style>
             
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            
        </style>
        
        <div class="grid grid-cols-12 gap-6">

            <!-- เสร็จสิ้น -->
            <div class="col-span-6 bg-white p-6 rounded-lg shadow-md h-[581px]">
                <h2 class="text-xl ">เสร็จสิ้น
                    <span class="text-gray-500 text-sm font-normal">ใบสั่งงานที่ดำเนินการเสร็จสิ้นแล้ว</span>
                </h2>
                <div class="overflow-y-auto h-[500px] pr-2 scrollbar-hide">
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <?php foreach ($data as $row): ?>
                            <?php if ($row['work_status'] === 'C'): ?>
                            <div class="p-4 rounded-lg h-[140px] relative shadow" style="background-color: #EEFFE5;">
                                <!-- เสร็จสิ้น badge -->
                                <span class="absolute top-4 right-4 text-[10px] px-2 py-0.5 rounded-full border border-dashed border-[#6E44FF] text-[#6E44FF] bg-white" style="border-radius: 8px">
                                    เสร็จสิ้น
                                </span>
                            
                                <!-- งาน -->
                                <p class="font-semibold text-[16px] text-[#3A3541] truncate"> <?= htmlspecialchars($row['work_name']) ?> </p>
                            
                                <!-- ผู้ใช้ -->
                                <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['user_fname']) ?> <?= htmlspecialchars($row['user_lname']) ?></p>
                            
                                <!-- วันที่กด -->
                                <div class="flex items-center text-sm text-[#3A3541] mt-2">
                                    <img src="public/Vector.png" class="w-4 h-4 mr-2" alt="Calendar Icon">
                                    วันที่เสร็จสิ้น <?= htmlspecialchars($row['work_submit_date']) ?>
                                </div>
                            
                                <!-- ปุ่มตกลง -->
                                <button class="absolute bottom-2 left-4 px-2 py-0.5 text-[10px] border border-black rounded-full bg-white text-black hover:bg-black hover:text-white transition-all duration-500 ease-in-out" style="border-radius: 8px">
                                    ตกลง
                                </button>
                            </div>
                            
                            <?php elseif ($row['work_status'] === 'D' ): ?>
                            <div class="p-4 rounded-lg h-[140px] relative shadow" style="background-color: #FFF3F3;">
                                <!-- เสร็จสิ้น badge -->
                                <span class="absolute top-4 right-4 text-[10px] px-2 py-0.5  rounded-full border border-dashed border-[#E60000] text-[#E60000] bg-[#ffB6B6]" style="border-radius: 8px">
                                    ปฏิเสธ
                                </span>
                            
                                <!-- งาน -->
                                <p class="font-semibold text-[16px] text-[#3A3541] truncate"> <?= htmlspecialchars($row['work_name']) ?> </p>
                            
                                <!-- ผู้ใช้ -->
                                <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['user_fname']) ?> <?= htmlspecialchars($row['user_lname']) ?></p>
                            
                                <!-- วันที่กด -->
                                <div class="flex items-center text-sm text-[#3A3541] mt-2">
                                    <img src="public/Vector.png" class="w-4 h-4 mr-2" alt="Calendar Icon">
                                    วันที่เสร็จสิ้น <?= htmlspecialchars($row['work_submit_date']) ?>
                                </div>
                            
                                <!-- ปุ่มตกลง --> 
                                <button class="absolute bottom-2 left-4 px-2 py-0.5 text-[10px] border border-black rounded-full bg-white text-black hover:bg-black hover:text-white transition-all duration-500 ease-in-out" style="border-radius: 8px">
                                    ตกลง
                                </button>
                            </div>
                            
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- กำลังดำเนินการ -->
            <div class="col-span-6 bg-white p-6 rounded-lg shadow-md h-[581px]">
                <h2 class="text-xl ">กำลังดำเนินการ
                    <span class="text-gray-500 text-sm font-normal">ใบสั่งงานที่กำลังดำเนินการอยู่</span>
                </h2>
                <div class="overflow-y-auto h-[500px] pr-2 scrollbar-hide">
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <?php foreach ($data as $row): ?>
                            <?php if ($row['work_status'] === 'P'): ?>
                            <div class="p-4 bg-white rounded-lg h-[105px] relative shadow">
                                <!-- กำลังดำเนินการ badge -->
                                <span class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded-full border border-dashed border-[#FBBC05] text-[#FBBC05] bg-[#FFF5D8]" style="border-radius: 8px">
                                    กำลังดำเนินการ
                                </span>
                            
                                <!-- ชื่อใบสั่งงาน -->
                                <p class="font-semibold text-[16px] text-[#3A3541] truncate">
                                    <?= htmlspecialchars($row['work_name']) ?>
                                </p>
                            
                                <!-- ชื่อ / แผนกผู้ขอ -->
                                <p class="text-[13px] text-[#3A3541] mt-1">
                                    <?= htmlspecialchars($row['user_fname']) ?>
                                </p>
                            
                                <!-- ไอคอน + วันที่ -->
                                <div class="flex items-center text-sm text-gray-700 mt-2">
                                    <img src="public/Vector.png" class="w-4 h-4 mr-2" alt="Calendar Icon">
                                    -
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- ประวัติสร้างใบ -->
            <div class="col-span-9 bg-white p-6 rounded-lg shadow-md h-[506px]">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold">ประวัติ</h2>
                        <p class="text-sm text-gray-500">ใบสั่งงานที่ดำเนินการเสร็จสิ้นและถูกยกเลิก</p>
                        
                        <div>
                            <p class="text-sm text-gray-500"> จาก {{$total_item}}</p>
                        </div>
                        <div class="button-container">
                            <button onclick="location.href='?page=<?= max(1, $current_page - 1) ?>'" class="px-4 py-2" style="background-image: url('public/asset/l.png'); background-repeat: no-repeat; background-position: center;" <?= $current_page === 1 ? 'disabled' : '' ?>></button>
                            <button onclick="location.href='?page=<?= min($total_pages, $current_page + 1) ?>'" class="px-4 py-2" style="background-image: url('public/asset/r.png'); background-repeat: no-repeat; background-position: center;" <?= $current_page === $total_pages ? 'disabled' : '' ?>></button>
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

                <!-- แบบร่าง -->

            <div class="col-span-3 bg-white p-6 rounded-lg shadow-md h-[506px]">
                <h2 class="text-xl font-semibold">แบบร่าง
                    <span class="text-gray-500 text-sm font-normal">ใบสั่งงานที่ร่างไว้</span>
                </h2>
                <div class="overflow-y-auto h-[440px] mt-4 pr-1 scrollbar-hide space-y-3">
                    <?php foreach ($data as $row): ?>
                        <?php if ($row['work_status'] === 'Draft'): ?>
                        <div class="p-4 bg-white rounded-lg shadow relative">
                            <!-- Badge -->
                            <span class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded-full border border-dashed border-gray-300 text-gray-500 bg-gray-100" style="border-radius: 8px">
                                แบบร่าง
                            </span>
            
                            <!-- Work Name -->
                            <p class="font-semibold text-sm text-gray-800 truncate">
                                <?= htmlspecialchars($row['work_name']) ?>
                            </p>
            
                            <!-- User Name -->
                            <p class="text-sm text-gray-600 mt-1 truncate">
                                <?= htmlspecialchars($row['user_fname']) ?>
                            </p>
            
                            <!-- Calendar Icon + Date -->
                            <div class="flex items-center text-sm text-gray-500 mt-2">
                                <img src="public/Vector.png" class="w-4 h-4 mr-2" alt="Calendar Icon">
                                <span class="truncate">
                                    -
                                </span>
                            </div>
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
