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
        user_fname, user_lname, department_name, work_author_type
        FROM work_request_order
        LEFT JOIN users ON work_create_by_user_id = user_id
        LEFT JOIN departments ON work_created_by_department_id = department_id
        WHERE work_create_by_user_id = $userID AND work_confirm_date IS NULL
        LIMIT :offset, :limit"; // ใช้ LIMIT สำหรับการแบ่งหน้า

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลคำขอที่สร้างภายใน 5 วันที่ผ่านมา โดยจำกัดการแสดงผลตามหน้า
$sql2 = "SELECT work_request_id, work_name, work_create_date, work_submit_date, work_create_by_user_id, work_status,
        user_fname, user_lname, department_name, work_author_type, work_confirm_date
        FROM work_request_order
        LEFT JOIN users ON work_create_by_user_id = user_id
        LEFT JOIN departments ON work_created_by_department_id = department_id
        WHERE work_create_by_user_id = $userID AND work_confirm_date >= NOW() - INTERVAL 5 DAY
        LIMIT :offset, :limit"; // ใช้ LIMIT สำหรับการแบ่งหน้า

$stmt2 = $pdo->prepare($sql2);

$stmt2->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt2->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt2->execute();
$data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);


// คำนวณจำนวนหน้าทั้งหมด
$sql_count = "SELECT COUNT(*) FROM work_request_order WHERE work_submit_date >= NOW() - INTERVAL 5 DAY AND work_create_by_user_id = $userID AND (work_status = 'C' OR work_status = 'D') AND work_confirm_date IS NOT NULL";
$count_stmt = $pdo->query($sql_count);
$total_item = $count_stmt->fetchColumn();
$total_pages = ceil($total_item / $items_per_page); // คำนวณจำนวนหน้าทั้งหมด

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_work_id'])) {
    $work_id = $_POST['confirm_work_id'];
    $confirm_date = date('Y-m-d H:i:s'); // วันที่และเวลาปัจจุบัน

    $update_sql = "UPDATE work_request_order SET work_confirm_date = :confirm_date WHERE work_request_id = :work_id";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->bindParam(':confirm_date', $confirm_date);
    $update_stmt->bindParam(':work_id', work_id, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
    }
}


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
<!-- เริ่มส่วน Sidebar -->
<div class="w-60 h-screen fixed top-0 left-0 bg-white shadow-lg flex flex-col">
    <!-- โลโก้ -->
    <div class="py-2 border-b">
        <img src="{{ asset('public/wrslogo.png') }}" alt="Logo" class="h-30 mx-auto">
    </div>
    
    <!-- เมนู -->
    <div class="flex-1 px-3 py-6 space-y-2">
        <a href="home" class="flex items-center px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-home mr-3"></i><span>หน้าหลัก</span>
        </a>
        <a href="workrequest" class="flex items-center px-4 py-3 bg-blue-500 text-white rounded-lg">
            <i class="fas fa-clipboard-list mr-3"></i><span>สร้างใบสั่งงาน</span>
        </a>
        <a href="report" class="flex items-center px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-file-alt mr-3"></i><span>รายงานการดำเนินงาน</span>
        </a>
        <a href="dashboard" class="flex items-center px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-chart-bar mr-3"></i><span>แดชบอร์ด</span>
        </a>
    </div>
    
    <!-- โปรไฟล์ผู้ใช้ -->
        <div class="p-4">
            <div id="profileButton" class="bg-blue-700 text-white px-4 py-3 rounded-lg flex items-center justify-between hover:bg-blue-800" style="cursor: pointer;">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white text-blue-700 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-lg"></i>
                    </div>
                    <div>
                        <div class="leading-tight text-xs">
                            {{ session('users')->user_fname }} {{ session('users')->user_lname }}
                        </div>
                        <div class="leading-tight text-xs">
                            {{ session('users')->user_id }}
                        </div>
                    </div>
                </div>
                <i class="fas fa-arrow-right text-white text-sm"></i>
            </div>
        </div>
        <!-- ป๊อปอัพยืนยันการออกจากระบบ -->
        <div id="logoutModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
            <div class="modal-container bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
                <div class="modal-header flex justify-between items-center border-b pb-4 mb-4">
                    <div class="modal-title text-xl font-semibold text-gray-800">ยืนยันการออกจากระบบ</div>
                    <button class="modal-close text-gray-500 text-xl" id="closeModal">&times;</button>
                </div>
                <div class="modal-body text-center mb-6">
                    <p class="text-lg text-gray-600 mb-4">คุณแน่ใจว่าต้องการออกจากระบบหรือไม่?</p>
                    <div class="modal-buttons flex justify-center gap-4">
                        <button class="btn btn-confirm text-white bg-blue-600 px-6 py-2 rounded-full hover:bg-blue-700" id="confirmLogout">ยืนยัน</button>
                        <button class="btn btn-cancel text-gray-700 border border-gray-300 px-6 py-2 rounded-full hover:bg-gray-100" id="cancelLogout">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // เมื่อคลิกที่ปุ่มโปรไฟล์ผู้ใช้
            document.getElementById('profileButton').addEventListener('click', function() {
                // เปิดป๊อปอัพยืนยันการออกจากระบบ
                document.getElementById('logoutModal').style.display = 'flex';
            });

            // เมื่อคลิกปุ่มปิดป๊อปอัพ
            document.getElementById('closeModal').addEventListener('click', function() {
                // ปิดป๊อปอัพ
                document.getElementById('logoutModal').style.display = 'none';
            });

            // เมื่อคลิกปุ่มยกเลิก
            document.getElementById('cancelLogout').addEventListener('click', function() {
                // ปิดป๊อปอัพ
                document.getElementById('logoutModal').style.display = 'none';
            });

            // เมื่อคลิกปุ่มยืนยัน
document.getElementById('confirmLogout').addEventListener('click', function() {
// ส่งคำขอไปยัง route logout
fetch('/logout', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
    },
}).then(response => {
    if (response.ok) {
        // ถ้าการออกจากระบบสำเร็จ ให้ redirect ไปที่หน้า login
        window.location.href = '/login';  // หรือ URL ที่ต้องการ
    } else {
        alert('เกิดข้อผิดพลาดในการออกจากระบบ');
    }
});

// ปิดป๊อปอัพ
document.getElementById('logoutModal').style.display = 'none';
});


            // ปิดป๊อปอัพเมื่อคลิกพื้นหลัง
            window.addEventListener('click', function(event) {
                if (event.target === document.getElementById('logoutModal')) {
                    document.getElementById('logoutModal').style.display = 'none';
                }
            });
    </script>

</div>
<!-- จบส่วน Sidebar -->
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-600">สร้างใบสั่งงาน </h1>
             <!-- ปุ่มเพิ่มคำขอ -->
            
            <div class="flex items-center space-x-3" style="position: relative; right: 50px;">
                <button @click="isOpen = true"
                    class="w-12 h-12 flex items-center justify-center bg-white text-black rounded-full border-2 border-black hover:bg-black hover:text-white transition">
                    <i class="fas fa-plus text-2xl"></i>
                </button>
                <h1 class="text-2xl font-bold text-black-600">สร้าง</h1>
            </div>
            
                
            
            
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
                                <?php if ($row['work_author_type'] === 'P'): ?>
                                    <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['user_fname']) ?> <?= htmlspecialchars($row['user_lname']) ?></p>
                                <?php elseif ($row['work_author_type'] === 'D'): ?>
                                    <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['department_name']) ?></p>
                                <?php endif; ?>
                            
                                <!-- วันที่กด -->
                                <div class="flex items-center text-sm text-[#3A3541] mt-2">
                                    <img src="public/Vector.png" class="w-4 h-4 mr-2" alt="Calendar Icon">
                                    วันที่เสร็จสิ้น <?= htmlspecialchars($row['work_submit_date']) ?>
                                </div>
                            
                                <!-- ปุ่มตกลง -->
                                <form method="POST" action="{{ url('/workrequest') }}">
                                    @csrf
                                    <input type="hidden" name="confirm_work_id" value="<?= $row['work_request_id'] ?>">
                                    <button type="submit" class="absolute bottom-2 left-4 px-2 py-0.5 text-[10px] border border-black rounded-full bg-white text-black hover:bg-black hover:text-white transition-all duration-500 ease-in-out" style="border-radius: 8px">
                                        ตกลง
                                    </button>
                                </form>
                                
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
                                <?php if ($row['work_author_type'] === 'P'): ?>
                                    <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['user_fname']) ?> <?= htmlspecialchars($row['user_lname']) ?></p>
                                <?php elseif ($row['work_author_type'] === 'D'): ?>
                                    <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['department_name']) ?></p>
                                <?php endif; ?>
                                <!-- วันที่กด -->
                                <div class="flex items-center text-sm text-[#3A3541] mt-2">
                                    <img src="public/Vector.png" class="w-4 h-4 mr-2" alt="Calendar Icon">
                                    วันที่เสร็จสิ้น <?= htmlspecialchars($row['work_submit_date']) ?>
                                </div>
                            
                                <!-- ปุ่มตกลง --> 
                                <form method="POST" action="{{ url('/workrequest') }}">
                                    @csrf
                                    <input type="hidden" name="confirm_work_id" value="<?= $row['work_request_id'] ?>">
                                    <button type="submit" class="absolute bottom-2 left-4 px-2 py-0.5 text-[10px] border border-black rounded-full bg-white text-black hover:bg-black hover:text-white transition-all duration-500 ease-in-out" style="border-radius: 8px">
                                        ตกลง 
                                    </button>
                                </form>
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
                        <h2 class="text-xl font-semibold">ประวัติ
                            <span class="text-gray-500 text-sm font-normal">ใบสั่งงานที่ดำเนินการเสร็จสิ้นและถูกยกเลิก</span>
                        </h2>
                        <div style="margin-left: 380px;">
                            <?php if ($total_item==0): ?>
                                <p class="text-gray-500 text-sm font-normal">0 - 0 จาก 0</p>
                            <?php elseif ($total_item<=50): ?>
                                <p class="text-gray-500 text-sm font-normal">{{$current_page}}-{{$total_item}} จาก {{$total_item}}</p>
                            <?php elseif ($total_item<$current_page*50): ?>
                                <p class="text-gray-500 text-sm font-normal">{{(($current_page-1)*50)+1}}-{{$total_item}} จาก {{$total_item}}</p>
                            <?php else : ?>
                                <p class="text-gray-500 text-sm font-normal">{{(($current_page-1)*50)+1}}-{{$current_page*50}} จาก {{$total_item}}</p>
                            <?php endif; ?>
                        </div>
                        <div class="button-container" style="margin-top: -10px;">
                            <button onclick="location.href='?page=<?= max(1, $current_page - 1) ?>'" class="px-4 py-2" style="background-image: url('public/asset/l.png'); background-repeat: no-repeat; background-position: center;" <?= $current_page === 1 ? 'disabled' : '' ?>></button>
                            <button onclick="location.href='?page=<?= min($total_pages, $current_page + 1) ?>'" class="px-4 py-2" style="background-image: url('public/asset/r.png'); background-repeat: no-repeat; background-position: center;" <?= $current_page === $total_pages ? 'disabled' : '' ?>></button>
                        </div>
    
                    </div>
                    <div class="overflow-y-auto h-[400px] pr-2 scrollbar-hide">
                        <div class="mt-4 grid grid-cols-3 gap-4">
                            <?php foreach ($data2 as $row): ?>
                                <?php if ($row['work_status'] === 'C'): ?>
                                    <div class="p-4 rounded-lg h-[105px] relative shadow" style="background-color: #EEFFE5;">
                                        <!-- เสร็จสิ้น badge -->
                                        <span class="absolute top-4 right-4 text-[10px] px-2 py-0.5 rounded-full border border-dashed border-[#6E44FF] text-[#6E44FF] bg-white" style="border-radius: 8px">
                                            เสร็จสิ้น
                                        </span>
                            
                                        <!-- งาน -->
                                        <p class="font-semibold text-[16px] text-[#3A3541] truncate"> <?= htmlspecialchars($row['work_name']) ?> </p>
                            
                                        <!-- ผู้ใช้ -->
                                        <?php if ($row['work_author_type'] === 'P'): ?>
                                            <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['user_fname']) ?> <?= htmlspecialchars($row['user_lname']) ?></p>
                                        <?php elseif ($row['work_author_type'] === 'D'): ?>
                                            <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['department_name']) ?></p>
                                        <?php endif; ?>
                            
                                        <!-- วันที่กด -->
                                        <div class="flex items-center text-sm text-[#3A3541] mt-2">
                                            <img src="public/Vector.png" class="w-4 h-4 mr-2" alt="Calendar Icon">
                                            วันที่เสร็จสิ้น <?= htmlspecialchars($row['work_submit_date']) ?>
                                        </div>
                                    </div>
                                <?php elseif ($row['work_status'] === 'D' ): ?>
                                    <div class="p-4 rounded-lg h-[105px] relative shadow" style="background-color: #FFF3F3;">
                                        <!-- เสร็จสิ้น badge -->
                                            <span class="absolute top-4 right-4 text-[10px] px-2 py-0.5  rounded-full border border-dashed border-[#E60000] text-[#E60000] bg-[#ffB6B6]" style="border-radius: 8px">
                                                ปฏิเสธ
                                            </span>
                            
                                        <!-- งาน -->
                                        <p class="font-semibold text-[16px] text-[#3A3541] truncate"> <?= htmlspecialchars($row['work_name']) ?> </p>
                            
                                        <!-- ผู้ใช้ -->
                                        <?php if ($row['work_author_type'] === 'P'): ?>
                                            <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['user_fname']) ?> <?= htmlspecialchars($row['user_lname']) ?></p>
                                        <?php elseif ($row['work_author_type'] === 'D'): ?>
                                            <p class="text-[13px] text-[#3A3541] mt-1"><?= htmlspecialchars($row['department_name']) ?></p>
                                        <?php endif; ?>                            
                                        <!-- วันที่กด -->
                                        <div class="flex items-center text-sm text-[#3A3541] mt-2">
                                            <img src="public/Vector.png" class="w-4 h-4 mr-2" alt="Calendar Icon">
                                            วันที่เสร็จสิ้น <?= htmlspecialchars($row['work_submit_date']) ?>
                                        </div>
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
<<<<<<< HEAD
    <div x-show="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-lg font-semibold">สร้างคำขอใหม่</h2>
                <button @click="isOpen = false" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
=======
<div x-show="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <!-- Modal Box -->
    <div class="bg-white p-4 rounded-xl shadow-xl max-w-3xl p-6 relative">
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="text-xl font-bold text-blue-700 mb-4">
                    รายละเอียดใบสั่งงาน <span class="text-gray-400 text-base font-normal">#</span>
                </h2>
            <button @click="isOpen = false" class="text-gray-600 hover:text-black text-xl">
                <i class="fas fa-times-circle"></i>
            </button>
        </div>
    
            <!-- FORM -->
>>>>>>> main
            <form action="{{ url('/workrequest') }}" method="POST">
                @csrf
                
                <!-- ข้อมูลหลัก -->
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-800 border-b pb-3 mb-4">
                  <div>
                      <label for="work_name" class="font-semibold">ชื่อเรื่อง :</label>
                        <input type="text" name="work_name" id="work_name"
                            class="border px-3 py-2 rounded w-half" placeholder="กรุณากรอกชื่อเรื่อง" required>
                  </div>
                  <div class="px-3 py-2">
                      <label class="font-semibold">วันที่ร้องขอ :</label>
                        <span name="create_date" value="" class="text-gray-900 font-medium">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
                  </div>
                  <div>
                    <span class="font-semibold">ผู้ส่ง :</span>
                    {{ session('users')->user_fname }} {{ session('users')->user_lname }}                     
                    
                    
                  </div>
                  <div class="px-3">
                        <label class="font-semibold ">แผนก <span class="text-red-500">*</span> :</label>
                        <label class="space-x-2 px-3 py-3">
                            <input type="radio" name="work_author_type" value="D" checked class="">
                            <span class="">ระบุ</span>
                        </label>
                        <label class="space-x-2 px-3 py-3">
                            <input type="radio" name="work_author_type" value="P" class="">
                            <span class="">ไม่ระบุ</span>
                        </label>
                  </div>
                </div>

    
                <!-- งานย่อย -->
                <div 
                    x-data="{
                        tasks: [{ id: 1, name: '', description: '' }],
                        addTask() {
                            this.tasks.push({ id: this.tasks.length + 1, name: '', description: '' });
                        },
                        removeTask(index) {
                            this.tasks.splice(index, 1);
                        }
                    }"
                    class="max-h-80 overflow-y-auto scrollbar-hide"
                >
    
                    <!-- ปุ่มเพิ่ม -->
                    <div class="flex justify-end items-center mt-1 max-h-80 overflow-y-auto">
                        <button type="button" @click="addTask" class="button-button5 bg-green-500 text-white px-4 py-1 rounded hover:bg-green-700 transition">
                            <i class="fas fa-plus"></i> เพิ่มรายการ
                        </button>
                    </div>
                </template>
    
                    <!-- งานย่อย template -->
                    <template x-for="(task, index) in tasks" :key="task.id">
                        <div class="mt-4 border border-gray-300 rounded-lg p-4 space-y-3">
                    
                            <!-- ชื่องาน วันที่ -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <input type="text" name="task_name[]" placeholder="ชื่องาน" class="w-full border rounded px-3 py-2" required>
                                <div class="flex items-center border rounded px-3 py-2 w-full space-x-2">
                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                <input type="date" name="task_deadline[]" class="flex-1 outline-none" required>
                                </div>
                            </div>  
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-2"
                                x-data="{ selected : 'P' }"
                            >
                                <label class="flex items-center border rounded px-3 py-2 space-x-2">
                                    <input type="radio" :name="'task_recipient_type[]' + index" value="P" x-model="selected">
                                    <input type="text" name="task_recipient_user_id[]" placeholder="บุคคล" class="flex-1 outline-none" 
                                    :disabled="selected !== 'P' , value='-'"
                                    :required="selected === 'P'">
                                    <i class="fas fa-search text-gray-500"></i>
                                </label>
                    
                                <!-- แผนก -->
                                <label class="flex items-center border rounded px-3 py-2 space-x-2">
                                    <input type="radio" :name="'task_recipient_type[]' + index" value="D" x-model="selected">
                                    <input type="text" name="task_recipient_department_id[]" placeholder="แผนก" class="flex-1 outline-none" 
                                    :disabled="selected !== 'D' , value='-'"
                                    :required="selected === 'D'">
                                    <i class="fas fa-search text-gray-500"></i>
                                </label>
                            </div>
                    
                         <!-- ปุ่มลบ -->
                            <div class="flex justify-end">
                                <button type="button" @click="removeTask(index)" class="text-red-500 hover:text-red-700 text-sm">
                                    <i class="fas fa-trash-alt"></i> ลบ
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <hr class="semibold my-6">
                   
                <!-- ปุ่มส่ง -->
                <div class="flex justify-end space-x-4">
                    <button type="submit" name="work_status" value="R" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">ส่ง</button>
                    <button type="submit" name="work_status" value="draft" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">แบบร่าง</button>
                </div>
            </form>
        </div>
        
    </div>
</body>

</html>
