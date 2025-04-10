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
$user_dept_ID = session('users')->user_dept_id;
$task_work_request_id = session('task');

$sql = "SELECT 
            task.task_id, 
            task.task_deadline, 
            task.task_status, 
            task.task_recipient_user_id, 
            task.task_name, 
            task.task_recipient_department_id, 
            task.task_notation, 
            task.task_recipient_type, 
            task.task_submit_date, 
            task.task_work_request_id, 
            wro.work_name, 
            wro.work_request_id,
            user_fname,
            user_lname
        FROM task
        LEFT JOIN work_request_order AS wro 
            ON task.task_work_request_id = wro.work_request_id
        LEFT JOIN users AS userID 
            ON task.task_recipient_user_id = userID.user_id
        LEFT JOIN departments 
            ON task.task_recipient_department_id = departments.department_id
        WHERE 
            (task.task_recipient_department_id = :user_dept_ID 
            OR task.task_recipient_user_id = :userID)
        AND task.task_work_request_id = wro.work_request_id
        AND task.task_submit_date IS NULL
        LIMIT :offset, :limit";

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


$sql3 = "SELECT task_id, task_deadline, task_status, task_recipient_user_id, task_name, task_recipient_department_id, 
               task_notation, task_recipient_type, task_submit_date, task_work_request_id, work_status, work_author_type, user_fname, user_lname
        FROM task
        LEFT JOIN work_request_order AS wro1 ON task_work_request_id = wro1.work_request_id
        LEFT JOIN users ON wro1.work_create_by_user_id = user_id
        LEFT JOIN departments ON user_dept_id = department_id
        WHERE task_recipient_user_id = :userID 
        AND wro1.work_confirm_date >= NOW() - INTERVAL 5 DAY 
        AND (task_status = 'C' OR task_status = 'D')
        AND (:selected_work_request_id IS NULL OR task_work_request_id = :selected_work_request_id)
        LIMIT :offset, :limit";

$stmt3 = $pdo->prepare($sql3);
$stmt3->bindParam(':userID', $userID, PDO::PARAM_INT);
$stmt3->bindParam(':selected_work_request_id', $selected_work_request_id, PDO::PARAM_INT);
$stmt3->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt3->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt3->execute();
$data3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// คำนวณจำนวนหน้าทั้งหมด
$sql_count = "SELECT COUNT(*) FROM work_request_order 
JOIN task ON work_request_id = task_work_request_id
WHERE task_submit_date >= NOW() - INTERVAL 5 DAY AND task_recipient_user_id = $userID AND (task_status = 'C' OR task_status = 'D')";
$count_stmt = $pdo->query($sql_count);
$total_item = $count_stmt->fetchColumn();
$total_pages = ceil($total_item / $items_per_page); // คำนวณจำนวนหน้าทั้งหมด



// ดึงข้อมูล tasks ที่เกี่ยวข้องกับ work_request_id
$sql_tasks = "SELECT * FROM task WHERE task_work_request_id = :work_request_id";
$stmt_tasks = $pdo->prepare($sql_tasks);



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

    <!-- การนำเข้า CSS และ Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- CSS พื้นฐาน -->
    <style>
        /* ตั้งค่า Font หลัก */
        body {
            font-family: 'Noto Sans Thai', sans-serif;

        }
    .graph-container {
        display: flex;
        justify-content: center; /* จัดกราฟให้อยู่ตรงกลางแนวนอน */
        align-items: center;     /* จัดกราฟให้อยู่ตรงกลางแนวตั้ง */
        width: 100%;             /* ทำให้กราฟมีความกว้างเต็ม */
        height: 100%;            /* ทำให้กราฟมีความสูงเต็ม */
    }

    .chart {
        width: 70%;             /* กำหนดขนาดกราฟที่ต้องการ */
        max-width: 600px;       /* กำหนดขนาดสูงสุด */
        height: auto;
    }

        /* สไตล์สำหรับ Popup */
        .popup {
            position: fixed;
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
        }
        .close-popup {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        body.popup-open {
            overflow: hidden;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollable-content {
        max-height: 400px; /* กำหนดความสูงสูงสุด */
        overflow-y: auto;  /* เปิดการเลื่อนในแนวตั้ง */

    }
    </style>
</head>
<body class="bg-[#f3f4f6] flex min-h-screen">
    <!-- เริ่มส่วน Sidebar -->
    <div class="w-60 h-screen fixed top-0 left-0 bg-white shadow-lg flex flex-col">
        <!-- โลโก้ -->
        <div class="py-2 border-b">
            <img src="{{ asset('public/wrslogo.png') }}" alt="Logo" class="h-30 mx-auto">
        </div>

        <!-- เมนู -->
        <div class="flex-1 px-3 py-6 space-y-2">
            <a href="home" class="flex items-center px-4 py-3 bg-blue-500 text-white rounded-lg">
                <i class="fas fa-home mr-3"></i><span>หน้าหลัก</span>
            </a>
            <a href="workrequest" class="flex items-center px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg">
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
                    fetch('logout', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    }).then(response => {
                        if (response.ok) {
                            // ถ้าการออกจากระบบสำเร็จ ให้ redirect ไปที่หน้า login
                            window.location.href = 'login';  // หรือ URL ที่ต้องการ
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

    <!-- เริ่มส่วนเนื้อหาหลัก -->
    <div class="flex-1 p-8 ml-60">
        <!-- หัวข้อและช่องค้นหา -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#0012E1]">หน้าหลัก</h1>

            <!-- ช่องค้นหา -->
            <!-- <div class="relative">
                <input type="text" placeholder="Search anything here..." class="pl-4 pr-10 py-2 border rounded-full w-80">
                <button class="absolute right-3 top-2.5">
                    <i class="fas fa-search text-[#9ca3af]"></i>
                </button>
            </div>  -->
        </div>

        <!-- กริดแสดงข้อมูล -->
        <div class="grid grid-cols-2 gap-6">
            <!-- การ์ดแสดงใบสั่งงานตามแผนก -->
        <div class="bg-[#ffffff] rounded-lg shadow p-6 h-[420px]">
            <div class="border-b pb-2 mb-4">
                <h2 class="text-lg font-bold">แผนก</h2>
                <p class="text-sm text-[#6b7280]">ใบสั่งงานตามแผนก</p>
            </div>
    <div class="space-y-4 scrollbar-hide scrollable-content">
        
        <!-- รายการงาน 1 -->
            <?php foreach ($data as $row): ?>
                <?php if ($row['task_status'] === 'R'): ?>
                    <?php if ($row['task_recipient_type'] === 'D'): ?>
                        <div href="work_detail.php?id=<?= $row['task_work_request_id'] ?>" class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 cursor-pointer transition h-[50px]">
                        <div class="bg-[#3b82f6] p-2 rounded-lg w-[50px] h-[50px] flex items-center justify-center">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : <?= htmlspecialchars($row['task_name']) ?> </div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : <?= htmlspecialchars($row['task_deadline']) ?></div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                        </div>
                    <?php endif; ?>
                                 
                                 
                <?php endif; ?>
            <?php endforeach; ?>

    </div>
</div>

        <!-- การ์ดแสดงกราฟการทำงานตามแผนก -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">แผนก</h2>
                    <p class="text-sm text-gray-500">กราฟแสดงการทำงานตามแผนก</p>
                </div>
            <div class="graph-container">
                <div class="chart">
                <!-- โค้ดกราฟของคุณที่ใช้แสดงกราฟที่นี่ -->
                    <script>
                        // ตัวอย่างข้อมูลที่จะดึงมา - สามารถเปลี่ยนเป็นการดึงข้อมูลจริงได้
                        const data = {
                        waiting: 55,
                        inProgress: 5,
                        completed: 48
                        };

                        // หาค่าสูงสุดเพื่อทำ scale
                        const maxValue = Math.max(data.waiting, data.inProgress, data.completed);
                        // คำนวณสเกลเพื่อให้กราฟพอดีกับความสูงที่กำหนด (200px)
                        const scale = 200 / (Math.ceil(maxValue / 10) * 10);

                        // สร้างสเกลด้านซ้าย
                        function createYAxis() {
                        const yAxis = document.getElementById('y-axis');
                        yAxis.innerHTML = '';

                        // คำนวณค่าสูงสุดของสเกล (ปัดขึ้นให้เป็นหลัก 10)
                        const maxScale = Math.ceil(maxValue / 10) * 10;

                        // สร้างช่วงสเกล 6 ช่วง (0-maxScale)
                        for (let i = 5; i >= 0; i--) {
                            const value = Math.round(maxScale * i / 5);
                            const div = document.createElement('div');
                            div.textContent = value;
                            yAxis.appendChild(div);
                        }
                        }

                        // อัพเดตความสูงของกราฟและค่าที่แสดง
                        function updateChart() {
                        // อัพเดตความสูงของกราฟ
                        document.getElementById('bar-waiting').style.height = `${data.waiting * scale}px`;
                        document.getElementById('bar-in-progress').style.height = `${data.inProgress * scale}px`;
                        document.getElementById('bar-completed').style.height = `${data.completed * scale}px`;

                        // อัพเดตตัวเลขที่แสดง
                        document.getElementById('value-waiting').textContent = data.waiting;
                        document.getElementById('value-in-progress').textContent = data.inProgress;
                        document.getElementById('value-completed').textContent = data.completed;
                        }

                        // รันฟังก์ชันเมื่อโหลดหน้า
                        window.onload = function() {
                        createYAxis();
                        updateChart();
                        };
                    </script>

                    <!-- กราฟแท่งแนวตั้ง -->
                    <div class="h-64 flex items-end justify-evenly relative">
                        <!-- แกนตั้งแสดงค่า -->
                        <div id="y-axis" class="absolute left-0 h-full flex flex-col justify-between text-gray-500 text-xs">
                        <!-- สเกลจะถูกสร้างด้วย JavaScript -->
                        </div>

                        <div class="flex flex-col items-center">
                        <div class="relative">
                            <div id="bar-waiting" class="bg-yellow-400 w-8 rounded-t"></div>
                            <span id="value-waiting" class="absolute -top-6 w-full text-center"></span>
                        </div>
                        <div class="mt-2 text-sm">รอดำเนินการ</div>
                        </div>

                        <div class="flex flex-col items-center">
                        <div class="relative">
                            <div id="bar-in-progress" class="bg-yellow-200 w-8 rounded-t"></div>
                            <span id="value-in-progress" class="absolute -top-6 w-full text-center"></span>
                        </div>
                        <div class="mt-2 text-sm">กำลังดำเนินการ</div>
                        </div>

                        <div class="flex flex-col items-center">
                        <div class="relative">
                            <div id="bar-completed" class="bg-green-500 w-8 rounded-t"></div>
                            <span id="value-completed" class="absolute -top-6 w-full text-center"></span>
                        </div>
                        <div class="mt-2 text-sm">เสร็จสิ้น</div>
                        </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    <!-- การ์ดแสดงใบสั่งงานส่วนตัว -->
                    <div class="bg-[#ffffff] rounded-lg shadow p-6 h-[420px]">
                        <div class="border-b pb-2 mb-4">
                            <h2 class="text-lg font-bold">ส่วนตัว</h2>
                            <p class="text-sm text-[#6b7280]">ใบสั่งงานส่วนตัว</p>
                        </div>

                        <div class="space-y-4 scrollbar-hide scrollable-content">
                             <!-- รายการงาน 1 -->
                             <?php foreach ($data as $row): ?>
                             <?php if ($row['task_status'] === 'R'): ?>
                                 <?php if ($row['task_recipient_type'] === 'P'): ?>
                                     <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100  cursor-pointer transition ">
                                         <div class="bg-[#D7FFC3] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                                             <i class="fas fa-box text-[#2563eb] text-2xl "></i>
                                         </div>
                                         <div class="flex-1">
                                             <div class="text-sm font-semibold text-gray-800">ชื่องาน : <?= htmlspecialchars($row['task_name']) ?>  </div>
                                             <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : <?= htmlspecialchars($row['task_deadline']) ?></div>
                                         </div>
                                         
                                         <div>
                                             <i class="fas fa-chevron-right text-gray-400"></i>
                                         </div>
                                     </div>
                                 <?php endif; ?>
                             <?php endif; ?>
                         <?php endforeach; ?>
                     </div>
                 </div>

                    <!-- การ์ดแสดงกราฟการทำงานส่วนตัว -->
        <div class="bg-[#ffffff] rounded-lg shadow p-6">
            <div class="border-b pb-2 mb-4">
                <h2 class="text-lg font-bold">ส่วนตัว</h2>
                <p class="text-sm text-[#6b7280]">กราฟแสดงการทำงานของส่วนตัว</p>
            </div>
        <div class="graph-container">
        <div class="chart">
        <!-- โค้ดกราฟของคุณที่ใช้แสดงกราฟที่นี่ -->
            <script>
                // ข้อมูลสำหรับกราฟส่วนตัว - แตกต่างจากกราฟแผนก
                const personalData = {
                    waiting: 25,
                    inProgress: 10,
                    completed: 35
                };

                // หาค่าสูงสุดเพื่อทำ scale
                const personalMaxValue = Math.max(personalData.waiting, personalData.inProgress, personalData.completed);
                // คำนวณสเกลเพื่อให้กราฟพอดีกับความสูงที่กำหนด (200px)
                const personalScale = 200 / (Math.ceil(personalMaxValue / 10) * 10);

                // สร้างสเกลด้านซ้าย
                function createPersonalYAxis() {
                    const yAxis = document.getElementById('personal-y-axis');
                    yAxis.innerHTML = '';

                    // คำนวณค่าสูงสุดของสเกล (ปัดขึ้นให้เป็นหลัก 10)
                    const maxScale = Math.ceil(personalMaxValue / 10) * 10;

                    // สร้างช่วงสเกล 6 ช่วง (0-maxScale)
                    for (let i = 5; i >= 0; i--) {
                        const value = Math.round(maxScale * i / 5);
                        const div = document.createElement('div');
                        div.textContent = value;
                        yAxis.appendChild(div);
                    }
                }

                // อัพเดตความสูงของกราฟและค่าที่แสดง
                function updatePersonalChart() {
                    // อัพเดตความสูงของกราฟ
                    document.getElementById('personal-bar-waiting').style.height = `${personalData.waiting * personalScale}px`;
                    document.getElementById('personal-bar-in-progress').style.height = `${personalData.inProgress * personalScale}px`;
                    document.getElementById('personal-bar-completed').style.height = `${personalData.completed * personalScale}px`;

                    // อัพเดตตัวเลขที่แสดง
                    document.getElementById('personal-value-waiting').textContent = personalData.waiting;
                    document.getElementById('personal-value-in-progress').textContent = personalData.inProgress;
                    document.getElementById('personal-value-completed').textContent = personalData.completed;
                }

                // เพิ่มฟังก์ชันเข้าไปในรายการที่ต้องทำเมื่อโหลดหน้า
                window.addEventListener('load', function() {
                    if (document.getElementById('personal-y-axis')) {
                        createPersonalYAxis();
                        updatePersonalChart();
                    }
                });
            </script>

            <!-- กราฟแท่งแนวตั้ง -->
            <div class="h-64 flex items-end justify-evenly relative">
                <!-- แกนตั้งแสดงค่า -->
                <div id="personal-y-axis" class="absolute left-0 h-full flex flex-col justify-between text-gray-500 text-xs">
                    <!-- สเกลจะถูกสร้างด้วย JavaScript -->
                </div>

                <div class="flex flex-col items-center">
                    <div class="relative">
                        <div id="personal-bar-waiting" class="bg-yellow-400 w-8 rounded-t"></div>
                        <span id="personal-value-waiting" class="absolute -top-6 w-full text-center"></span>
                    </div>
                    <div class="mt-2 text-sm">รอดำเนินการ</div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="relative">
                        <div id="personal-bar-in-progress" class="bg-yellow-200 w-8 rounded-t"></div>
                        <span id="personal-value-in-progress" class="absolute -top-6 w-full text-center"></span>
                    </div>
                    <div class="mt-2 text-sm">กำลังดำเนินการ</div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="relative">
                        <div id="personal-bar-completed" class="bg-green-500 w-8 rounded-t"></div>
                        <span id="personal-value-completed" class="absolute -top-6 w-full text-center"></span>
                    </div>
                    <div class="mt-2 text-sm">เสร็จสิ้น</div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- การ์ดแสดงงานที่กำลังดำเนินการ (เต็มความกว้าง) -->
    <div class="bg-[#ffffff] rounded-lg shadow p-6 col-span-2 h-[420px]">
        <div class="border-b pb-2 mb-4">
            <h2 class="text-lg font-bold">กำลังดำเนินการ</h2>
            <p class="text-sm text-[#6b7280]">ใบสั่งงานอยู่ระหว่างการทำงาน</p>
        </div>

    <div class="space-y-2 scrollbar-hide scrollable-content ">
        <!-- รายการงานที่กำลังดำเนินการ 1 -->
        <?php foreach ($data as $row): ?>
            <?php if ($row['task_status'] === 'P'): ?>
                    <div class="flex items-center justify-between pb-2 cursor-pointer work-item w-full">
                        <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition w-full"  data-work-request-id="<?= $row['task_work_request_id'] ?>" onclick="openPopup(this)">
                            <div class="bg-[#CFD0F9] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                                <i class="fas fa-box text-[#533FE4] text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-gray-800">ชื่องาน : <?= htmlspecialchars($row['task_name']) ?></div>
                                <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : <?= htmlspecialchars($row['task_deadline']) ?></div>
                            </div>
                            <div class="flex items-center">
                                <button class="bg-[#ffffff] border border-[#00AC4F] text-[#00AC4F] px-4 py-1 rounded-full text-sm mr-4 hover:bg-[#00AC4F] hover:text-[#ffffff] transition-colors duration-200">เสร็จสิ้น</button>
                                <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                            </div>
                        </div>
                    </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
            <!-- ส่วนประวัติการทำงาน -->
            <div class="bg-white rounded-lg shadow p-6 mt-10 col-span-2 h-[420px]">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <div>
                        <h2 class="text-lg font-bold">ประวัติ</h2>
                        <p class="text-sm text-gray-500">งานที่ดำเนินการเสร็จสิ้นและปฏิเสธ</p>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400]">
                        <?php if ($total_item==0): ?>
                            <span>0-0 จาก 0</span>
                        <?php elseif ($total_item<=50): ?>
                            <span>{{$current_page}}-{{$total_item}} จาก {{$total_item}}</span>
                        <?php elseif ($total_item<$current_page*50): ?>
                            <span>{{(($current_page-1)*50)+1}}-{{$total_item}} จาก {{$total_item}}</span>
                        <?php else : ?>
                            <span>{{(($current_page-1)*50)+1}}-{{$current_page*50}} จาก {{$total_item}}</span>
                        <?php endif; ?>
                        <button onclick="location.href='?page=<?= max(1, $current_page - 1) ?>'" class="text-[#6366f1] hover:text-[#4338ca]">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button onclick="location.href='?page=<?= min($total_pages, $current_page + 1) ?>'" class="text-[#6366f1] hover:text-[#4338ca]">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- กริดแสดงการ์ดประวัติ -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-4 gap-4">
                    <!-- การ์ดประวัติ -->
                    <?php foreach ($data2 as $row): ?>
                        <?php if ($row['task_status'] === 'C'): ?>
                            <div class="p-4 rounded-lg shadow-sm bg-[#e8ffe8] border hover:shadow-md transition h-[105px]">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="font-semibold text-sm text-gray-800"><?= htmlspecialchars($row['task_name']) ?></div>
                                    <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">เสร็จสิ้น</span>
                                </div>
                                <?php if ($row['work_author_type'] === 'P'): ?>
                                    <div class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($row['user_fname']) ?> <?= htmlspecialchars($row['user_lname']) ?></div>
                                <?php elseif ($row['work_author_type'] === 'D'): ?>
                                    <div class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($row['department_name']) ?></div>
                                <?php endif; ?>
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-calendar-alt mr-1 text-purple-600"></i>
                                        <?= htmlspecialchars($row['task_submit_date']) ?>
                                </div>
                            </div>
                        <?php elseif ($row['task_status'] === 'D'): ?>
                            <div class="p-4 rounded-lg shadow-sm bg-[#ffecec] border hover:shadow-md transition">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="font-semibold text-sm text-gray-800"><?= htmlspecialchars($row['task_name']) ?></div>
                                    <span class="text-xs bg-red-200 text-red-600 px-2 py-0.5 rounded-full">ปฏิเสธ</span>
                                </div>
                                <?php if ($row['work_author_type'] === 'P'): ?>
                                    <div class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($row['user_fname']) ?> <?= htmlspecialchars($row['user_lname']) ?></div>
                                <?php elseif ($row['work_author_type'] === 'D'): ?>
                                    <div class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($row['department_name']) ?></div>
                                <?php endif; ?>
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-calendar-alt mr-1 text-purple-600"></i>
                                        <?= htmlspecialchars($row['task_submit_date']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
<!-- เกี่ยวกับระบบ -->
<footer class=" border-t mt-10 px-10 py-12 col-span-2 rounded-lg shadow-sm">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-12 text-sm text-gray-700">
    <div>
      <h3 class="text-base font-semibold text-gray-800 mb-3 flex items-center">
        <span>เกี่ยวกับเรา</span>
        <img src="{{ asset('public/wrslogo.png') }}" alt="WRS" class="inline-block h-10 ml-2">
      </h3>
      <p class="leading-relaxed mb-2">
        จัดการงานง่ายขึ้น เพิ่มประสิทธิภาพองค์กร ด้วย <strong>WRS</strong>
      </p>
      <p class="text-gray-600">
        <span class="text-blue-700 font-medium">Work Request System (WRS)</span> คือระบบบริหารงานที่ช่วยองค์กรจัดระเบียบงานภายใน ลดเวลาการทำงานซ้ำซ้อน
        และเพิ่มความคล่องตัวให้กับงาน รองรับการติดตามงาน การแจ้งเตือนอัตโนมัติ และการจัดสรรทรัพยากรในองค์กรอย่างมีประสิทธิภาพ
        ช่วยให้องค์กรสามารถบริหารจัดการงานเป็นเรื่องง่ายสำหรับองค์กรทุกขนาด
      </p>
    </div>
    <div>
      <h3 class="text-base font-semibold text-gray-800 mb-3 flex items-center">
        <span>ทำงานร่วมกับ</span>
        <img src="{{ asset('public/บริษัท.png') }}" alt="WRS" class="inline-block h-5 ml-2">
      </h3>
      <p class="leading-relaxed mb-2 text-gray-600">
        บริษัท คลิกเน็กซ์ จำกัด เป็นนักพัฒนาซอฟต์แวร์มืออาชีพที่เน้นกระบวนการพัฒนาซอฟต์แวร์แบบครบวงจร
        เพื่อให้ลูกค้าได้รับผลงานที่มีคุณภาพและส่งมอบตรงเวลา
      </p>
      <p class="text-gray-500">
        Phone : 022177900<br>
        E-mail : info@clicknext.com
      </p>
    </div>
  </div>
  <div class="text-center text-xs text-gray-400 mt-10">© [2025] Work Request System. All rights reserved.</div>
</footer>
    </div>
    </div>


<!-- Popup รายละเอียดใบสั่งงาน -->
<div id="workItemPopup" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 relative">

      <!-- ปุ่มปิด -->
              <button class="close-popup absolute top-4 right-4 text-gray-500 hover:text-gray-800">
          <i class="fas fa-times text-xl"></i>
          </button>


      <!-- หัวข้อ -->
    
          <h2 class="text-xl font-bold text-blue-700 mb-4">
              รายละเอียดใบสั่งงาน 
              <span class="text-gray-400 text-base font-normal">
                  
              </span>
          </h2>
      
          <!-- ข้อมูลหลัก -->
          <div class="grid grid-cols-2 gap-4 text-sm text-gray-800 border-b pb-3 mb-4">
            <div>
                <span class="font-semibold">ชื่อเรื่อง :</span>
                <span id="popup-title">-</span>
            </div>
            <div>
                <span class="font-semibold">วันที่ร้องขอ :</span>
                <span id="popup-date">-</span>
            </div>
            <div>
                <span class="font-semibold">ผู้ส่ง :</span>
                <span id="popup-user">-</span>
            </div>
            <div>
                <span class="font-semibold">แผนก :</span>
                <span id="popup-department">-</span>
            </div>
          </div>


      <!-- การ์ดย่อยของงาน -->
      <div class="grid grid-cols-2 gap-4 max-h-80 overflow-y-auto">
        @for ($i = 0; $i < 6; $i++)
          <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition">
            <div class="text-sm font-semibold text-gray-800 truncate">สมัครอีเมลพนักงาน</div>
            <div class="text-xs text-gray-500 mb-2">จิรายุ คนโก้</div>
            <div class="flex items-center text-xs text-gray-600">
              <i class="fas fa-calendar-alt mr-1 text-purple-500"></i>
              อังคาร, 1 ธันวาคม 2025
            </div>
            <span class="mt-2 inline-block text-xs bg-blue-100 text-blue-600 rounded-full px-2 py-0.5 font-medium">รอดำเนินการ</span>
          </div>
        @endfor
      </div>

          <!-- ปุ่ม -->
      <div class="flex justify-center mt-6 gap-3">
        <button type="button" id="openDeclinePopupDoing" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">
            ปฏิเสธ
        </button>
        <button class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white transition">ส่งคืนงาน</button>
        <button id = "submit" class="px-4 py-2 border border-green-600 text-green-600 rounded-md hover:bg-green-600 hover:text-white transition">เสร็จสิ้น</button>
      </div>


    </div>

<!-- Popup รายละเอียดใบสั่งงาน(กำลังดำนินการ) -->
<div id="workItemPopupDoing" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 relative">

      <!-- ปุ่มปิด -->
              <button class="close-popup absolute top-4 right-4 text-gray-500 hover:text-gray-800">
          <i class="fas fa-times text-xl"></i>
          </button>


      <!-- หัวข้อ -->
    
          <h2 class="text-xl font-bold text-blue-700 mb-4">
              รายละเอียดใบสั่งงาน 
              <span class="text-gray-400 text-base font-normal">
                  
              </span>
          </h2>
      
          <!-- ข้อมูลหลัก -->
      <div class="grid grid-cols-2 gap-4 text-sm text-gray-800 border-b pb-3 mb-4">
      <div>
          <span class="font-semibold">ชื่อเรื่อง :</span> <span id="popup-title">-</span>
      </div>
      <div>
          <span class="font-semibold">วันที่ร้องขอ :</span> <span id="popup-date">-</span>
      </div>
      <div>
          <span class="font-semibold">ผู้ส่ง :</span>  {{ session('users')->user_fname }} hellow {{ session('users')->user_lname }}
      </div>
      <div>
          <span class="font-semibold">แผนก :</span>
      </div>
      </div>


      <!-- การ์ดย่อยของงาน -->
      <div class="grid grid-cols-2 gap-4 max-h-80 overflow-y-auto">
        @for ($i = 0; $i < 6; $i++)
          <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition">
            <div class="text-sm font-semibold text-gray-800 truncate">สมัครอีเมลพนักงาน</div>
            <div class="text-xs text-gray-500 mb-2">จิรายุ คนโก้</div>
            <div class="flex items-center text-xs text-gray-600">
              <i class="fas fa-calendar-alt mr-1 text-purple-500"></i>
              อังคาร, 1 ธันวาคม 2025
            </div>
            <span class="mt-2 inline-block text-xs bg-blue-100 text-blue-600 rounded-full px-2 py-0.5 font-medium">รอดำเนินการ</span>
          </div>
        @endfor
      </div>

          <!-- ปุ่ม -->
      <div class="flex justify-center mt-6 gap-3">
        <button type="button" id="openDeclinePopupDoing" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">
            ปฏิเสธ
        </button>
        <button class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white transition">ส่งคืนงาน</button>
        <button id = "submit" class="px-4 py-2 border border-green-600 text-green-600 rounded-md hover:bg-green-600 hover:text-white transition">เสร็จสิ้น</button>
      </div>


    </div>
</div>
<!-- ป๊อปอัพยืนยันส่งงาน-->
<div id="confirmSubmitModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="modal-container bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <div class="modal-header flex justify-between items-center border-b pb-4 mb-4">
            <div class="modal-title text-xl font-semibold text-gray-800">ยืนยันการส่งงาน</div>
            <button class="modal-close text-gray-500 text-xl" id="close-popup">&times;</button>
        </div>
        <div class="modal-body text-center mb-6">
            <p class="text-left text-lg text-gray-600 mb-4">หมายเหตุ <span style="color: red;">*</span> :</p>
            <p class="text-right text-xs text-gray-400"><span id="charCount">0</span>/100</p>
            <textarea name="task_notation" id="notation" class="border border-gray-300 rounded-lg w-full p-2 mb-4" style="height: 100px; resize: none;" maxlength="100" oninput="updateCounter()" placeholder="กรุณากรอกหมายเหตุ"></textarea>
            <hr>
            <br>
            <div class="modal-buttons flex justify-center gap-4">
                <button class="btn btn-confirm text-white bg-blue-600 px-6 py-2 rounded-full hover:bg-blue-700" id="confirmSubmit">ยืนยัน</button>
                <button class="btn btn-cancel text-gray-700 border border-gray-300 px-6 py-2 rounded-full hover:bg-gray-100" id="cancelSubmit">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>


<!-- ป๊อปอัพปฏิเสธส่งงาน-->
<div id="declineModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="modal-container bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <div class="modal-header flex justify-between items-center border-b pb-4 mb-4">
            <div class="modal-title text-xl font-semibold text-gray-800">ยืนยันการปฏิเสธงาน</div>
            <button class="modal-close text-gray-500 text-xl" id="close-decline-popup">&times;</button>
        </div>
        <div class="modal-body text-center mb-6">
            <p class="text-left text-lg text-gray-600 mb-4">เหตุผล <span style="color: red;">*</span> :</p>
            <p class="text-right text-xs text-gray-400"><span id="declineCharCount">0</span>/100</p>
            <textarea name="work_decline" id="declineReason" class="border border-gray-300 rounded-lg w-full p-2 mb-4" style="height: 100px; resize: none;" maxlength="100" oninput="updateDeclineCounter()" placeholder="กรุณากรอกเหตุผลในการปฏิเสธงาน"></textarea>
            <hr>
            <br>
            <div class="modal-buttons flex justify-center gap-4">
                <form method="POST" action="{{ url('/home') }}">
                    @csrf
                    <input type="hidden" name="decline_work_id" value="<?= $row['task_work_request_id'] ?>">
                    <button class="btn btn-confirm text-white bg-red-600 px-6 py-2 rounded-full hover:bg-red-700" type="submit">ยืนยัน</button>
                </form>
                <button class="btn btn-cancel text-gray-700 border border-gray-300 px-6 py-2 rounded-full hover:bg-gray-100" id="cancelDecline">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCounter() {
    	let input = document.getElementById("notation");
    	let maxLength = input.maxLength;
    	let remaining = input.value.length;
    	document.getElementById("charCount").textContent = remaining;
	}
    // เมื่อคลิกที่ปุ่มโปรไฟล์ผู้ใช้
    document.getElementById('submit').addEventListener('click', function() {
        // เปิดป๊อปอัพยืนยันการออกจากระบบ
        document.getElementById('confirmSubmitModal').style.display = 'flex';
    });

    // เมื่อคลิกปุ่มปิดป๊อปอัพ
   
  // เมื่อคลิกปุ่มปิด popup
  document.querySelectorAll('.close-popup').forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('workItemPopup').classList.add('hidden');
    });
  });


    // เมื่อคลิกปุ่มยกเลิก
    document.getElementById('cancelSubmit').addEventListener('click', function() {
        // ปิดป๊อปอัพ
        document.getElementById('confirmSubmitModal').style.display = 'none';
    });

    // เมื่อคลิกปุ่มยืนยัน
    document.getElementById('confirmSubmit').addEventListener('click', function() {
    // แจ้งเตือนการส่งงาน
    (response => {
        document.getElementById('workItemPopupDoing').style.display = 'none';
        alert('ส่งงานสำเร็จ');
    });

    // ปิดป๊อปอัพ
        document.getElementById('confirmSubmitModal').style.display = 'none';
    });


    // ปิดป๊อปอัพเมื่อคลิกพื้นหลัง
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('confirmSubmitModal')) {
            document.getElementById('confirmSubmitModal').style.display = 'none';
        }
    });
</script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const workItems = document.querySelectorAll('.work-item');
            const workItemsDoing = document.querySelectorAll('.work-item-doing');
            const popup = document.getElementById('workItemPopup');
            const popupDoing = document.getElementById('workItemPopupDoing');
            const closeButtons = document.querySelectorAll('.close-popup, .close-popup-btn');
            const popupTitle = document.getElementById('popup-title');
            const popupDate = document.getElementById('popup-date');

            const openDeclineBtn = document.getElementById('openDeclinePopup');
            const declinePopup = document.getElementById('declineModal');

            workItems.forEach(item => {
                item.addEventListener('click', function() {
                    const titleElement = this.querySelector('div > div:first-child');
                    const dateElement = this.querySelector('div > div:last-child');

                    if (titleElement && dateElement) {
                        const title = titleElement.textContent.replace('ชื่องาน : ', '');
                        const date = dateElement.textContent.replace('วันสิ้นสุดการทำงาน : ', '');

                        // Set info in popup
                        popupTitle.textContent = title;
                        popupDate.textContent = date;
                    }

                    // Show popup
                    popup.style.display = 'flex';
                    document.body.classList.add('popup-open');
                });
            });
            workItemsDoing.forEach(item => {
                item.addEventListener('click', function() {
                    // Get task info from the clicked item
                    const titleElement = this.querySelector('div > div:first-child');
                    const dateElement = this.querySelector('div > div:last-child');

                    if (titleElement && dateElement) {
                        const title = titleElement.textContent.replace('ชื่องาน : ', '');
                        const date = dateElement.textContent.replace('วันสิ้นสุดการทำงาน : ', '');

                        // Set info in popup
                        popupTitle.textContent = title;
                        popupDate.textContent = date;
                    }

                    // Show popup
                    popupDoing.style.display = 'flex';
                    document.body.classList.add('popup-open');
                });
            });


            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    popup.style.display = 'none';
                    popupDoing.style.display = 'none';
                    declinePopup.style.display = 'none';
                    document.body.classList.remove('popup-open');
                });
            });

            // Close popup when clicking outside the content
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    popupDoing.style.display = 'none';
                    document.body.classList.remove('popup-open');
                }
            });
        // Open decline popup
        openDeclineBtn.addEventListener('click', function() {
            declinePopup.style.display = 'flex';
            
        });

        // Close decline popup when clicking outside the content
        declinePopup.addEventListener('click', function(e) {
            if (e.target === declinePopup) {
                declinePopup.style.display = 'none';
                
            }
        });


        });


        function acceptWork() {
            const popup = document.getElementById('workItemPopup');
            popup.style.display = 'none';
            document.body.classList.remove('popup-open');
        }

        function closePopup() {
            const popup = document.getElementById('workItemPopup');
            popup.style.display = 'none';
            document.body.classList.remove('popup-open');
            
        }

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = document.querySelectorAll('.work-item');

            items.forEach(item => {
                item.addEventListener('click', function (e) {
                    e.stopPropagation();

                    document.querySelectorAll('.popover-content').forEach(p => p.classList.add('hidden'));

                    const popover = this.querySelector('.popover-content');
                    if (popover.classList.contains('hidden')) {
                        popover.classList.remove('hidden');
                    } else {
                        popover.classList.add('hidden');
                    }
                });
            });

            document.addEventListener('click', function () {
                document.querySelectorAll('.popover-content').forEach(p => p.classList.add('hidden'));
            });
        });
    </script>

    <script>
        
    function updateDeclineCounter() {
        let input = document.getElementById("declineReason");
        document.getElementById("declineCharCount").textContent = input.value.length;
    }

    // เปิดป๊อปอัพปฏิเสธ
    document.getElementById('openDeclinePopup').addEventListener('click', function () {
        document.getElementById('declineModal').style.display = 'flex';
    });
    // สำหรับ popup "กำลังดำเนินการ"
    document.getElementById('openDeclinePopupDoing').addEventListener('click', function () {
        document.getElementById('declineModal').style.display = 'flex';
    });

    // ปิดป๊อปอัพปฏิเสธ
    document.getElementById('close-decline-popup').addEventListener('click', function () {
        document.getElementById('declineModal').style.display = 'none';
    });

    document.getElementById('cancelDecline').addEventListener('click', function () {
        document.getElementById('declineModal').style.display = 'none';
    });

    document.getElementById('confirmDecline').addEventListener('click', function () {
        // ใส่ logic ปฏิเสธงานตรงนี้
        alert('ปฏิเสธงานเรียบร้อย');
        document.getElementById('declineModal').style.display = 'none';
    });
    


    // ปิด modal เมื่อคลิกนอกกล่อง
    window.addEventListener('click', function(event) {
        const declineModal = document.getElementById('declineModal');
        if (event.target === declineModal) {
            declineModal.style.display = 'none';
        }
    });

    </script>
</div>

<script>
    function openPopup(element) {
        const workRequestId = element.getAttribute('data-work-request-id');
    
        fetch(`/getWorkDetails.php?work_request_id=${workRequestId}`)
          .then(res => res.json())
          .then(data => {
            // Set title and date
            document.getElementById('popup-title').textContent = data.work_name;
            document.getElementById('popup-date').textContent = data.work_submit_date;
    
            // Render tasks
            const container = document.querySelector('#workItemPopupDoing .grid');
            container.innerHTML = ''; // Clear old tasks
    
            data.tasks.forEach(task => {
                container.innerHTML += `
                  <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <div class="text-sm font-semibold text-gray-800 truncate">${task.task_name}</div>
                    <div class="text-xs text-gray-500 mb-2">${task.user_fname} ${task.user_lname}</div>
                    <div class="flex items-center text-xs text-gray-600">
                      <i class="fas fa-calendar-alt mr-1 text-purple-500"></i>
                      ${task.deadline_thai}
                    </div>
                    <span class="mt-2 inline-block text-xs bg-blue-100 text-blue-600 rounded-full px-2 py-0.5 font-medium">
                      ${task.status_text}
                    </span>
                  </div>
                `;
            });
    
            // Show popup
            document.getElementById('workItemPopupDoing').classList.remove('hidden');
          });
    }
    </script>
    <script>
      // เมื่อคลิกที่การ์ดงานที่กำลังดำเนินการ
    document.querySelectorAll('.work-item').forEach(item => {
        item.addEventListener('click', function () {
          const workRequestId = this.getAttribute('data-work-request-id');
      
          fetch('/get_work_request_detail.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'work_request_id=' + workRequestId
          })
          .then(response => response.json())
          .then(data => {
            document.getElementById('popup-title').innerText = data.work_name;
            document.getElementById('popup-date').innerText = data.work_create_date;
            document.getElementById('popup-user').innerText = `${data.user_fname} ${data.user_lname}`;
            document.getElementById('popup-department').innerText = data.department_name;
      
            // เปิด popup
            document.getElementById('workItemPopupDoing').classList.remove('hidden');
          });
        });
      });
    </script>      

</body>

</html>