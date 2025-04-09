<?php
use Illuminate\Support\Facades\Log;
session_start(); // เริ่มต้น session
$userID = session('users')->user_id;
$completedTasks = 0;
$pendingTasks = 0;


if ($userID) {
    $result = DB::select(
        "
        SELECT
            SUM(CASE WHEN task_status = 'C' THEN 1 ELSE 0 END) AS completed_tasks,
            SUM(CASE WHEN task_status = 'R' THEN 1 ELSE 0 END) AS pending_tasks,
            SUM(CASE WHEN task_status = 'P' THEN 1 ELSE 0 END) AS processed_tasks
        FROM task
        WHERE task_recipient_user_id = ?
          AND task_recipient_type = 'P'
        GROUP BY task_recipient_user_id, task_recipient_type
    ",
        [$userID],
    );

    if (!empty($result)) {
        $completedTasks = $result[0]->completed_tasks ?? 0;
        $processedTasks = $result[0]->processed_tasks ?? 0;
        $pendingTasks = $result[0]->pending_tasks ?? 0;
        
    }
    $departmentUserID = $userID; // รหัสผู้ใช้สำหรับแผนก


    // ดึงข้อมูลจากฐานข้อมูล
    $result = DB::select(
        "
    SELECT
        task_recipient_user_id,
        task_recipient_type,
        SUM(CASE WHEN task_status = 'C' THEN 1 ELSE 0 END) AS completedDepartment_tasks,
        SUM(CASE WHEN task_status = 'R' THEN 1 ELSE 0 END) AS pendingDepartment_tasks,
        SUM(CASE WHEN task_status = 'D' THEN 1 ELSE 0 END) AS rejectedDepartment_tasks
    FROM task
    WHERE task_recipient_user_id = ?
      AND task_recipient_type = 'D'
    GROUP BY task_recipient_user_id, task_recipient_type
",
        [$departmentUserID],
    );
    
    // ตรวจสอบผลลัพธ์
    if (!empty($result)) {
        $completedDepartmentTasks = $result[0]->completedDepartment_tasks ?? 0;
        $pendingDepartmentTasks = $result[0]->pendingDepartment_tasks ?? 0;
        $rejectedDepartmentTasks = $result[0]->rejectedDepartment_tasks ?? 0;
    }
    $weeklyTaskSummary = [];

    // ดึงข้อมูลจากฐานข้อมูล
    $result = DB::select("
    SELECT
        DATE(task.task_submit_date) AS completed_date,
        task.task_recipient_type,
        COUNT(*) AS total_tasks
    FROM task
    WHERE
        task.task_status = 'C'
        AND task.task_submit_date IS NOT NULL
        AND task.task_submit_date BETWEEN
            DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
            AND DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)
    GROUP BY
        DATE(task.task_submit_date),
        task.task_recipient_type
    ORDER BY
        completed_date ASC
");
    if (!empty($result)) {
        foreach ($result as $row) {
            $weeklyTaskSummary[] = [
                'completed_date' => $row->completed_date,
                'task_recipient_type' => $row->task_recipient_type,
                'total_tasks' => $row->total_tasks,
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <!-- Meta tags และ Fonts -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System</title>

    <!-- การนำเข้า CSS และ Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- CSS พื้นฐาน -->
    <style>
        /* ตั้งค่า Font หลัก */
        body {
            font-family: 'Noto Sans Thai', sans-serif;

        }

        .graph-container {
            display: flex;
            justify-content: center;
            /* จัดกราฟให้อยู่ตรงกลางแนวนอน */
            align-items: center;
            /* จัดกราฟให้อยู่ตรงกลางแนวตั้ง */
            width: 100%;
            /* ทำให้กราฟมีความกว้างเต็ม */
            height: 100%;
            /* ทำให้กราฟมีความสูงเต็ม */
            overflow-y: auto;
        }

        .chart {
            width: 70%;
            /* กำหนดขนาดกราฟที่ต้องการ */
            max-width: 600px;
            /* กำหนดขนาดสูงสุด */
            height: auto;
        }
        .close-popup {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        /* body.popup-open {
            overflow: hidden;
        } */

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollable-content {
            max-height: 400px;
            /* กำหนดความสูงสูงสุด */
            overflow-y: auto;
            /* เปิดการเลื่อนในแนวตั้ง */

        }
    </style>
</head>

<body class="bg-[#f3f4f6] flex min-h-screen statitic w-full ">
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
            <div id="profileButton"
                class="bg-blue-700 text-white px-4 py-3 rounded-lg flex items-center justify-between hover:bg-blue-800"
                style="cursor: pointer;">
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



    </div><!-- จบส่วน Sidebar -->

    <!-- ป๊อปอัพยืนยันการออกจากระบบ -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
        <div id="test" class="bg-white rounded-lg shadow-lg w-96">
            <div class="flex justify-between items-center border-b pb-4 px-6">
                <div class="text-xl font-semibold text-gray-800">ยืนยันการออกจากระบบ</div>
                <button class="text-gray-500 text-xl" id="closeModal">&times;</button>
            </div>
            <div class="text-center mb-6">
                <p class="text-lg text-gray-600 mb-4">คุณแน่ใจว่าต้องการออกจากระบบหรือไม่?</p>
                <div class="flex justify-center gap-4">
                    <button class="btn btn-confirm text-white bg-blue-600 px-6 py-2 rounded-full hover:bg-blue-700"
                        id="confirmLogout">ยืนยัน</button>
                    <button
                        class="btn btn-cancel text-gray-700 border border-gray-300 px-6 py-2 rounded-full hover:bg-gray-100"
                        id="cancelLogout">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // เมื่อคลิกที่ปุ่มโปรไฟล์ผู้ใช้
        document.getElementById('profileButton').addEventListener('click', function () {
            // เปิดป๊อปอัพยืนยันการออกจากระบบ
            document.getElementById('logoutModal').style.display = 'flex';


        });

        document.getElementById('closeModal').addEventListener('click', function () {

            document.getElementById('logoutModal').style.display = 'none';
        });


        // เมื่อคลิกปุ่มยกเลิก
        document.getElementById('cancelLogout').addEventListener('click', function () {
            // ปิดป๊อปอัพ
            document.getElementById('logoutModal').style.display = 'none';

        });

        // เมื่อคลิกปุ่มยืนยัน
        document.getElementById('confirmLogout').addEventListener('click', function () {
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
        window.addEventListener('click', function (event) {
            if (event.target === document.getElementById('logoutModal')) {
                document.getElementById('logoutModal').style.backgroundColor = 'red';
            }
        });
    </script>

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
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">แผนก</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานตามแผนก</p>
                </div>
                <div class="space-y-4 scrollbar-hide scrollable-content">
                    <!-- รายการงาน 1 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#3b82f6] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                    <!-- รายการงาน 1 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#3b82f6] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- รายการงาน 2 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#3b82f6] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- รายการงาน 3 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#3b82f6] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- รายการงาน 4 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#3b82f6] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
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
                            const data = { 
                                waiting: {{ $pendingTasks }},
                                inProgress: {{ $processedTasks }},
                                completed: {{ $completedTasks }}
                                
    };

                            const maxValue = Math.max(data.waiting, data.inProgress, data.completed);
                            const scale = 200 / (Math.ceil(maxValue / 10) * 10);

                            function createYAxis() {
                                const yAxis = document.getElementById('y-axis');
                                yAxis.innerHTML = '';
                                const maxScale = Math.ceil(maxValue / 10) * 10;

                                for (let i = 5; i >= 0; i--) {
                                    const value = Math.round(maxScale * i / 5);
                                    const div = document.createElement('div');
                                    div.textContent = value;
                                    yAxis.appendChild(div);
                                }
                            }
                            console.log(data)

                            function updateChart() {
                                document.getElementById('bar-waiting').style.height = `${data.waiting * scale}px`;
                                document.getElementById('bar-in-progress').style.height = `${data.inProgress * scale}px`;
                                document.getElementById('bar-completed').style.height = `${data.completed * scale}px`;

                                document.getElementById('value-waiting').textContent = data.waiting;
                                document.getElementById('value-in-progress').textContent = data.inProgress;
                                document.getElementById('value-completed').textContent = data.completed;
                            }

                            window.onload = function () {
                                createYAxis();
                                updateChart();
                            };
                        </script>


                        <!-- กราฟแท่งแนวตั้ง -->
                        <div class="h-64 flex  items-end justify-evenly relative  z-index-1">
                            <!-- แกนตั้งแสดงค่า -->
                            <div id="y-axis"
                                class="absolute left-0 h-full flex flex-col justify-between text-gray-500 text-xs">
                                <!-- สเกลจะถูกสร้างด้วย JavaScript -->
                            </div>

                            <div class="flex flex-col items-center ">
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
                                <div class="relative border z-index-1">
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
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">ส่วนตัว</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานส่วนตัว</p>
                </div>

                <div class="space-y-4 scrollbar-hide scrollable-content">
                    <!-- รายการงาน 1 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#D7FFC3] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-[#2563eb] text-2xl "></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- รายการงาน 2 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#D7FFC3] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-[#2563eb] text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- รายการงาน 3 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#D7FFC3] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-[#2563eb] text-2xl "></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- รายการงาน 4 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#D7FFC3] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-[#2563eb] text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                    <!-- รายการงาน 5 -->
                    <div
                        class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                        <div class="bg-[#D7FFC3] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                            <i class="fas fa-box text-[#2563eb] text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                            <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
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
                                waiting: {{ $pendingTasks }},
                                inProgress: {{ $processedTasks }},
                                completed: {{ $completedTasks }}
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
                            window.addEventListener('load', function () {
                                if (document.getElementById('personal-y-axis')) {
                                    createPersonalYAxis();
                                    updatePersonalChart();
                                }
                            });
                        </script>

                        <!-- กราฟแท่งแนวตั้ง -->
                        <div class="h-64 flex items-end justify-evenly relative">
                            <!-- แกนตั้งแสดงค่า -->
                            <div id="personal-y-axis"
                                class="absolute left-0 h-full flex flex-col justify-between text-gray-500 text-xs">
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
                                    <span id="personal-value-in-progress"
                                        class="absolute -top-6 w-full text-center"></span>
                                </div>
                                <div class="mt-2 text-sm">กำลังดำเนินการ</div>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="relative">
                                    <div id="personal-bar-completed" class="bg-green-500 w-8 rounded-t"></div>
                                    <span id="personal-value-completed"
                                        class="absolute -top-6 w-full text-center"></span>
                                </div>
                                <div class="mt-2 text-sm">เสร็จสิ้น</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- การ์ดแสดงงานที่กำลังดำเนินการ (เต็มความกว้าง) -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6 col-span-2">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">กำลังดำเนินการ</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานอยู่ระหว่างการทำงาน</p>
                    <div class="space-y-2 scrollbar-hide scrollable-content ">
                        <!-- รายการงานที่กำลังดำเนินการ 1 -->
                        <div class="flex items-center justify-between pb-2 cursor-pointer work-item-doing w-full">
                            <div
                                class="work-item-doing flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition w-full">
                                <div class="bg-[#CFD0F9] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                                    <i class="fas fa-box text-[#533FE4] text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-800">ชื่องาน : สร้างอีเมลพนักงาน</div>
                                    <div class="text-xs text-gray-500">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                                </div>
                                <div class="flex items-center">
                                    <button
                                        class="bg-[#ffffff] border border-[#00AC4F] text-[#00AC4F] px-4 py-1 rounded-full text-sm mr-4 hover:bg-[#00AC4F] hover:text-[#ffffff] transition-colors duration-200">เสร็จสิ้น</button>
                                    <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- ส่วนประวัติการทำงาน -->
            <div class="bg-white rounded-lg shadow p-6 mt-10 col-span-2">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <div>
                        <h2 class="text-lg font-bold">ประวัติ</h2>
                        <p class="text-sm text-gray-500">งานที่ดำเนินการเสร็จสิ้นและปฏิเสธ</p>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <span>1-50 จาก 250</span>
                        <button class="text-[#6366f1] hover:text-[#4338ca]">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="text-[#6366f1] hover:text-[#4338ca]">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- กริดแสดงการ์ดประวัติ -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    <!-- การ์ดประวัติ: เสร็จสิ้น -->
                    <div class="p-4 rounded-lg shadow-sm bg-[#e8ffe8] border hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-2">
                            <div class="font-semibold text-sm text-gray-800">ชื่องาน</div>
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">เสร็จสิ้น</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-1">ชื่อ / แผนกผู้งาน</div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-calendar-alt mr-1 text-purple-600"></i>
                            วันที่เสร็จสิ้น
                        </div>
                    </div>

                    <!-- การ์ดประวัติ: ปฏิเสธ -->
                    <div class="p-4 rounded-lg shadow-sm bg-[#ffecec] border hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-2">
                            <div class="font-semibold text-sm text-gray-800">ชื่องาน</div>
                            <span class="text-xs bg-red-200 text-red-600 px-2 py-0.5 rounded-full">ปฏิเสธ</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-1">ชื่อ / แผนกผู้งาน</div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-calendar-alt mr-1 text-purple-600"></i>
                            วันที่ปฏิเสธ
                        </div>
                    </div>

                    <!-- ใช้ลูป Blade เพื่อแสดงการ์ดเพิ่มเติม -->
                    @for ($i = 0; $i < 12; $i++)
                        <div class="p-4 rounded-lg shadow-sm bg-[#e8ffe8] border hover:shadow-md transition">
                            <div class="flex justify-between items-center mb-2">
                                <div class="font-semibold text-sm text-gray-800">ชื่องาน</div>
                                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">เสร็จสิ้น</span>
                            </div>
                            <div class="text-xs text-gray-500 mb-1">ชื่อ / แผนกผู้งาน</div>
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-calendar-alt mr-1 text-purple-600"></i>
                                วันที่เสร็จสิ้น
                            </div>
                        </div>
                    @endfor
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
                            <span class="text-blue-700 font-medium">Work Request System (WRS)</span>
                            คือระบบบริหารงานที่ช่วยองค์กรจัดระเบียบงานภายใน ลดเวลาการทำงานซ้ำซ้อน
                            และเพิ่มความคล่องตัวให้กับงาน รองรับการติดตามงาน การแจ้งเตือนอัตโนมัติ
                            และการจัดสรรทรัพยากรในองค์กรอย่างมีประสิทธิภาพ
                            ช่วยให้องค์กรสามารถบริหารจัดการงานเป็นเรื่องง่ายสำหรับองค์กรทุกขนาด
                        </p>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-3 flex items-center">
                            <span>ทำงานร่วมกับ</span>
                            <img src="{{ asset('public/บริษัท.png') }}" alt="WRS" class="inline-block h-5 ml-2">
                        </h3>
                        <p class="leading-relaxed mb-2 text-gray-600">
                            บริษัท คลิกเน็กซ์ จำกัด
                            เป็นนักพัฒนาซอฟต์แวร์มืออาชีพที่เน้นกระบวนการพัฒนาซอฟต์แวร์แบบครบวงจร
                            เพื่อให้ลูกค้าได้รับผลงานที่มีคุณภาพและส่งมอบตรงเวลา
                        </p>
                        <p class="text-gray-500">
                            Phone : 022177900<br>
                            E-mail : info@clicknext.com
                        </p>
                    </div>
                </div>
                <div class="text-center text-xs text-gray-400 mt-10">© [2025] Work Request System. All rights reserved.
                </div>
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
                รายละเอียดใบสั่งงาน <span class="text-gray-400 text-base font-normal">#HR-680003</span>
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
                    <span class="font-semibold">ผู้ส่ง :</span> {{ session('users')->user_fname }}
                    {{ session('users')->user_lname }}
                </div>
                <div>
                    <span class="font-semibold">แผนก :</span>
                </div>
            </div>

            <!-- การ์ดย่อยของงาน -->
            <div class="grid grid-cols-2 gap-4 max-h-80 overflow-y-auto">
                @for ($i = 0; $i < 6; $i++)
                    <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition relative work-item"
                        data-title="สมัครอีเมลพนักงาน" data-owner="จิรายุ คนโก้" data-date="อังคาร, 1 ธันวาคม 2025">
                        <div class="text-sm font-semibold text-gray-800 truncate">สมัครอีเมลพนักงาน</div>
                        <div class="text-xs text-gray-500 mb-2">จิรายุ คนโก้</div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-calendar-alt mr-1 text-purple-500"></i>
                            อังคาร, 1 ธันวาคม 2025
                        </div>
                        <span
                            class="mt-2 inline-block text-xs bg-blue-100 text-blue-600 rounded-full px-2 py-0.5 font-medium">รอดำเนินการ</span>

                        <!-- กล่อง Popover ที่ซ่อนอยู่ -->
                        <div
                            class="popover-content absolute z-50 top-full mt-2 left-0 bg-white border rounded-lg shadow-lg text-sm text-gray-700 w-64 p-3 hidden">
                            <strong class="block mb-1 text-gray-800">รายละเอียดการทำงาน</strong>
                            <p>สมัครอีเมลพนักงานให้พนักงานใหม่ และแจ้งเจ้าหน้าที่ HR.</p>
                            <p class="text-xs text-gray-500 mt-2">คลิกอีกครั้งเพื่อปิด</p>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- ✅ ปุ่มรับงาน / ปฏิเสธ -->
            <div class="flex justify-center mt-6 gap-3">
                <button onclick="closePopup()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">
                    ปฏิเสธ
                </button>
                <button onclick="acceptWork()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    รับงาน
                </button>
            </div>

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
                รายละเอียดใบสั่งงาน <span class="text-gray-400 text-base font-normal">#HR-680003</span>
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
                    <span class="font-semibold">ผู้ส่ง :</span> {{ session('users')->user_fname }}
                    {{ session('users')->user_lname }}
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
                        <span
                            class="mt-2 inline-block text-xs bg-blue-100 text-blue-600 rounded-full px-2 py-0.5 font-medium">รอดำเนินการ</span>
                    </div>
                @endfor
            </div>

            <!-- ปุ่ม -->
            <div class="flex justify-center mt-6 gap-3">
                <button onclick="closePopup()"
                    class="px-4 py-2 border border-black text-black rounded-md hover:bg-black hover:text-white transition">ปฏิเสธ</button>
                <button
                    class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white transition">ส่งคืนงาน</button>
                <button id="submit"
                    class="px-4 py-2 border border-green-600 text-green-600 rounded-md hover:bg-green-600 hover:text-white transition">เสร็จสิ้น</button>
            </div>


        </div>
    </div>
    <!-- ป๊อปอัพยืนยันส่งงาน-->
    <div id="confirmSubmitModal"
        class="modal-overlay fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="modal-container bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
            <div class="modal-header flex justify-between items-center border-b pb-4 mb-4">
                <div class="modal-title text-xl font-semibold text-gray-800">ยืนยันการส่งาน</div>
                <button class="modal-close text-gray-500 text-xl" id="close-popup">&times;</button>
            </div>
            <div class="modal-body text-center mb-6">
                <p class="text-left text-lg text-gray-600 mb-4">หมายเหตุ <span style="color: red;">*</span> :</p>
                <p class="text-right text-xs text-gray-400"><span id="charCount">0</span>/100</p>
                <textarea id="notation" class="border border-gray-300 rounded-lg w-full p-2 mb-4"
                    style="height: 100px; resize: none;" maxlength="100" oninput="updateCounter()"
                    placeholder="กรุณากรอกหมายเหตุ"></textarea>
                <hr>
                <br>
                <div class="modal-buttons flex justify-center gap-4">
                    <button class="btn btn-confirm text-white bg-blue-600 px-6 py-2 rounded-full hover:bg-blue-700"
                        id="confirmSubmit">ยืนยัน</button>
                    <button
                        class="btn btn-cancel text-gray-700 border border-gray-300 px-6 py-2 rounded-full hover:bg-gray-100"
                        id="cancelSubmit">ยกเลิก</button>
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
        document.getElementById('submit').addEventListener('click', function () {
            // เปิดป๊อปอัพยืนยันการออกจากระบบ
            document.getElementById('logoutModal').style.display = 'none';
        });
        document.getElementById('confirmLogout').addEventListener('click', function () {
            // เปิดป๊อปอัพยืนยันการออกจากระบบ
            document.getElementById('logoutModal').style.display = 'none';
        });

        // เมื่อคลิกปุ่มปิดป๊อปอัพ
        document.getElementById('close-popup').addEventListener('click', function () {
            // ปิดป๊อปอัพ
            document.getElementById('confirmSubmitModal').style.display = 'none';
            document.body.classList.remove('popup-open');
        });

        // เมื่อคลิกปุ่มยกเลิก
        document.getElementById('cancelSubmit').addEventListener('click', function () {
            // ปิดป๊อปอัพ
            document.getElementById('confirmSubmitModal').style.display = 'none';
        });

        // เมื่อคลิกปุ่มยืนยัน
        document.getElementById('confirmSubmit').addEventListener('click', function () {
            // แจ้งเตือนการส่งงาน
            (response => {
                document.getElementById('workItemPopupDoing').style.display = 'none';
                alert('ส่งงานสำเร็จ');
            });

            // ปิดป๊อปอัพ
            document.getElementById('confirmSubmitModal').style.display = 'none';
        });


        // ปิดป๊อปอัพเมื่อคลิกพื้นหลัง
        window.addEventListener('click', function (event) {
            if (event.target === document.getElementById('confirmSubmitModal')) {
                document.getElementById('confirmSubmitModal').style.display = 'none';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const workItems = document.querySelectorAll('.work-item');
            const workItemsDoing = document.querySelectorAll('.work-item-doing');
            const popup = document.getElementById('workItemPopup');
            const popupDoing = document.getElementById('workItemPopupDoing');
            const closeButtons = document.querySelectorAll('.close-popup, .close-popup-btn');
            const popupTitle = document.getElementById('popup-title');
            const popupDate = document.getElementById('popup-date');

            workItems.forEach(item => {
                item.addEventListener('click', function () {
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
                item.addEventListener('click', function () {
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
                button.addEventListener('click', function () {
                    popup.style.display = 'none';
                    popupDoing.style.display = 'none';
                    document.body.classList.remove('popup-open');
                });
            });

            // Close popup when clicking outside the content
            popup.addEventListener('click', function (e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    popupDoing.style.display = 'none';
                    document.body.classList.remove('popup-open');
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
    </div>


</body>

</html>