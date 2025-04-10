<?php
use Illuminate\Support\Facades\Log;
session_start(); // เริ่มต้น session
$userID = session('users')->user_id;


if ($userID) {
    $result = DB::select(
        "
        SELECT
            SUM(CASE WHEN task_status = 'C' THEN 1 ELSE 0 END) AS completed_tasks,
            SUM(CASE WHEN task_status = 'R' THEN 1 ELSE 0 END) AS pending_tasks,
            SUM(CASE WHEN task_status = 'D' THEN 1 ELSE 0 END) AS rejected_tasks
        FROM task
        WHERE task_recipient_user_id = ?
          AND task_recipient_type = 'P'
        GROUP BY task_recipient_user_id, task_recipient_type
    ",
        [$userID],
    );

    if (!empty($result)) {
        $completedTasks = $result[0]->completed_tasks ?? 0;
        $pendingTasks = $result[0]->pending_tasks ?? 0;
        $rejectedTasks = $result[0]->rejected_tasks ?? 0;
    }
    $departmentUserID = $userID; // รหัสผู้ใช้สำหรับแผนก
    $completedDepartmentTasks = 0;
    $pendingDepartmentTasks = 0;
    $rejectedDepartmentTasks = 0;

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/button.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/dashboard.css') }}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System & Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body class="flex min-h-screen bg-[#f3f4f6]">
    <!-- Sidebar - Fixed Position -->
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
        <!-- ป๊อปอัพยืนยันการออกจากระบบ -->
        <div id="logoutModal"
            class="modal-overlay fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
            <div class="modal-container bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
                <div class="modal-header flex justify-between items-center border-b pb-4 mb-4">
                    <div class="modal-title text-xl font-semibold text-gray-800">ยืนยันการออกจากระบบ</div>
                    <button class="modal-close text-gray-500 text-xl" id="closeModal">&times;</button>
                </div>
                <div class="modal-body text-center mb-6">
                    <p class="text-lg text-gray-600 mb-4">คุณแน่ใจว่าต้องการออกจากระบบหรือไม่?</p>
                    <div class="modal-buttons flex justify-center gap-4">
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

            // เมื่อคลิกปุ่มปิดป๊อปอัพ
            document.getElementById('closeModal').addEventListener('click', function () {
                // ปิดป๊อปอัพ
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
                    document.getElementById('logoutModal').style.display = 'none';
                }
            });
        </script>
    </div>

    <!-- Main Content; เพิ่มคลาส ml-60 เพื่อขยับออกจาก sidebar -->
    <div class="ml-60 w-full">
        <div class="container">
            <!-- Header -->
            <div class="header-container">
                <b>
                    <h1 style="font-size : 25.63px">แดชบอร์ด</h1>
                </b>
            </div>

            <!-- Summary Section -->
            <div class="summary-container">
                <div class="flex justify-between">
                    <h2 class="text-xl font-semibold mb-12">สรุปผล
                        <span class="text-gray-500 text-sm font-normal">สรุปผลการทำงานของสัปดาห์นี้

                        </span>
                    </h2>
                    <div class="switch-wrapper">
                        <input type="checkbox" id="customSwitch" class="switch-input">
                        <label for="customSwitch" class="switch-label"></label>
                        <span class="switch-text text-right" id="switchText">ส่วนตัว</span>
                    </div>

                    </button>
                </div>
                <div class="summary">
                    <div class="card card-completed">
                        <img src="{{ asset('public/asset/success/Icon (5).png') }}" alt="WorkRequest System Logo"
                            class="card-img-left">
                        <p><?php echo $completedTasks; ?></p>
                        <h2>ดำเนินการเสร็จสิ้น</h2>
                    </div>

                    <div class="card card-progress">
                        <img src="{{ asset('public\Ellipse 3 (3).png') }}" alt="WorkRequest System Logo"
                            class="card-img-left">
                        <p><?php echo $pendingTasks; ?></p>
                        <h2>รอดำเนินการ</h2>
                    </div>
                    <div class="card card-rejected">
                        <img src="{{ asset('public\asset\reject.png') }}" alt="WorkRequest System Logo"
                            class="card-img-left">

                        <p><?php echo $rejectedTasks; ?></p>
                        <h2>งานที่ปฏิเสธ</h2>
                    </div>
                </div>
            </div>

            <!-- Chart Section: Personal & Department -->
            <div class="chart-container">
                <div class="chart-row">
                    <div class="chart-section half">
                        <div class="chart-title mb-0">ส่วนตัว</div>
                        <div class="text-gray-500 text-sm font-normal">กราฟแสดงการทำงานภายในสัปดาห์ของส่วนตัว</div>
                        <br>
                        <canvas id="personalChart"></canvas>
                    </div>
                    <div class="chart-section half">
                        <div class="chart-title mb-0">แผนก</div>
                        <div class="text-gray-500 text-sm font-normal">กราฟแสดงการทำงานภายในสัปดาห์ของแผนก</div>
                        <br>
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart Section: Weekly Completion -->
            {{-- <div class="chart-section">
                <div class="chart-title">ดำเนินการเสร็จสิ้นภายในสัปดาห์</div>
                <canvas id="weekCompletionChart" style="max-width: 100%; height: 400px;"></canvas>
            </div> --}}
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const checkbox = document.getElementById('customSwitch');
        const text = document.getElementById('switchText');

        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                text.textContent = 'แผนก';
                text.classList.remove('text-right');
                text.classList.add('text-left');
            } else {
                text.textContent = 'ส่วนตัว';
                text.classList.remove('text-left');
                text.classList.add('text-right');
            }
        });
    </script>
    <script>
        // Personal Chart
        const personalData = {
            labels: ['รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น'],
            datasets: [{
                label: 'ส่วนตัว',
                data: [{{ $pendingTasks }}, {{ $rejectedTasks }}, {{ $completedTasks }}],
                backgroundColor: ['#FFB300', '#FFE0B2', '#4CAF50'], // สีตรงตามภาพ
                borderSkipped: false,
                barPercentage: 0.26,
                categoryPercentage: 0.7
            }]

        };
        const personalConfig = {
            type: 'bar',
            data: personalData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            color: '#eee'
                        },
                        beginAtZero: true
                    }
                }
            }
        };
        new Chart(document.getElementById('personalChart'), personalConfig);

        // Department Chart
        const departmentData = {
            labels: ['รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น'],
            datasets: [{
                label: 'แผนก',
                data: [{{ $pendingDepartmentTasks }}, {{ $rejectedDepartmentTasks }},
                    {{ $completedDepartmentTasks }}
                ],
                backgroundColor: ['#FFB300', '#FFE0B2', '#4CAF50'], // ใช้สีเดียวกับ personal
                borderSkipped: false,
                barPercentage: 0.26,
                categoryPercentage: 0.7
            }]
        };
        const departmentConfig = {
            type: 'bar',
            data: departmentData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            color: '#eee'
                        },
                        beginAtZero: true
                    }
                }
            }
        };
        new Chart(document.getElementById('departmentChart'), departmentConfig);

        // // Weekly Completion Chart
        // const weekCompletionData = {
        //     labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
        //     datasets: [{
        //             label: 'แผนก',
        //             data: [30, 35, 12, 33, 25, 32, 45],
        //             backgroundColor: '#0029FF',
        //             barPercentage: 0.45,
        //             categoryPercentage: 0.6
        //         },
        //         {
        //             label: 'บุคคล',
        //             data: [27, 25, 45, 15, 24, 28, 23],
        //             backgroundColor: '#00D27F',
        //             barPercentage: 0.45,
        //             categoryPercentage: 0.6
        //         }
        //     ]
        // };

        // const weekCompletionConfig = {
        //     type: 'bar',
        //     data: weekCompletionData,
        //     options: {
        //         responsive: true,
        //         maintainAspectRatio: false,
        //         plugins: {
        //             legend: {
        //                 display: true,
        //                 position: 'bottom',
        //                 labels: {
        //                     usePointStyle: true, // ใช้จุดแทนสี่เหลี่ยม
        //                     pointStyle: 'roundedRect', // เปลี่ยนเป็นสี่เหลี่ยมไม่มีมุม
        //                     padding: 20, // เพิ่มระยะห่างระหว่าง Legend
        //                     font: {
        //                         size: 14
        //                     }
        //                 }
        //             }
        //         },
        //         scales: {
        //             x: {
        //                 grid: {
        //                     display: false
        //                 }
        //             },
        //             y: {
        //                 grid: {
        //                     color: '#eee'
        //                 },
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        // };

        // new Chart(document.getElementById('weekCompletionChart'), weekCompletionConfig);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.querySelector('.custom-button');
            button.addEventListener('click', () => {
                button.classList.toggle('clicked'); // เพิ่มหรือลบคลาส clicked
            });
        });
    </script>
</body>

</html>