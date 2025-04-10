<?php
use Illuminate\Support\Facades\Log;
session_start(); // เริ่มต้น session
$userID = session('users')->user_id;
$completedTasks = 0;
$processedTasks = 0;
$rejectedTasks = 0;
$Userclick = session('Userclick', false);

if ($userID) {
    $result = DB::select(
        "
        SELECT
            SUM(CASE WHEN task_status = 'C' THEN 1 ELSE 0 END) AS completed_tasks,
            SUM(CASE WHEN task_status = 'D' THEN 1 ELSE 0 END) AS decryding_tasks,
            SUM(CASE WHEN task_status = 'R' THEN 1 ELSE 0 END) AS rejected_tasks,
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
        $rejectedTasks = $result[0]->rejected_tasks ?? 0;
        $decrydingTasks = $result[0]->decryding_tasks ?? 0;
    }
    $departmentUserID = $userID; // รหัสผู้ใช้สำหรับแผนก
    $completedDepartmentTasks = 0;
    $processedTaskDepartmentTasks = 0;
    $waitingDepartmentTasks = 0;
    $decrydingDepartmentTasks = 0;

    // ดึงข้อมูลจากฐานข้อมูล
    $result = DB::select(
        "
    SELECT
        task_recipient_user_id,
        task_recipient_type,
        SUM(CASE WHEN task_status = 'C' THEN 1 ELSE 0 END) AS completedDepartment_tasks,
        SUM(CASE WHEN task_status = 'R' THEN 1 ELSE 0 END) AS waitingDepartment_tasks,
        SUM(CASE WHEN task_status = 'D' THEN 1 ELSE 0 END) AS decryDepartment_tasks,
        SUM(CASE WHEN task_status = 'P' THEN 1 ELSE 0 END) AS processDepartment_tasks

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
        $processedTaskDepartmentTasks = $result[0]->processDepartment_tasks ?? 0; // แก้ไขจาก pendingtedDepartment_tasks
        $waitingDepartmentTasks = $result[0]->waitingDepartment_tasks ?? 0; // แก้ไขจาก waitdingDepartment_tasks
        $decrydingDepartmentTasks = $result[0]->decryDepartment_tasks ?? 0;
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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            <a href="home" class="flex items-center px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg" >
                <i class="fas fa-home mr-3"></i><span>หน้าหลัก</span>
            </a>
            <a href="workrequest" class="flex items-center px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-clipboard-list mr-3"></i><span>สร้างใบสั่งงาน</span>
            </a>
            <a href="report" class="flex items-center px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-file-alt mr-3"></i><span>รายงานการดำเนินงาน</span>
            </a>
            <a href="dashboard" class="flex items-center px-4 py-3 bg-blue-500 text-white rounded-lg">
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
                        window.location.href = 'login'; // หรือ URL ที่ต้องการ
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
                        window.location.href = 'login'; // หรือ URL ที่ต้องการ
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
                        <input type="checkbox" id="customSwitch" class="switch-input" {{ $Userclick ? 'checked' : '' }}>
                        <label for="customSwitch" class="switch-label" id="switchLabel">
                            <span class="switch-inner-text" style="display: flex; justify-content: center; align-items: center; height: 100%;">{{ $Userclick ? 'แผนก' : 'ส่วนตัว' }}</span>
                        </label>
                    </div>


                    </button>
                </div>
                <div class="summary">
                    <div class="card card-completed">
                        <img src="{{ asset('public/asset/success/Icon (5).png') }}" alt="WorkRequest System Logo"
                            class="card-img-left">
                        <p id="completedTasksDisplay">
                            {{ $Userclick ? $completedDepartmentTasks : $completedTasks }}
                        </p>
                        <h2>ดำเนินการเสร็จสิ้น</h2>
                    </div>

                    <div class="card card-progress">
                        <img src="{{ asset('public/Ellipse 3 (3).png') }}" alt="WorkRequest System Logo" class="card-img-left">
                        <p id="pendingTasksDisplay"> <!-- เพิ่ม id -->
                            {{ $Userclick ? $waitingDepartmentTasks : $rejectedTasks }}
                        </p>
                        <h2>รอดำเนินการ</h2>
                    </div>
                    <div class="card card-rejected">
                        <img src="{{ asset('public\asset\reject.png') }}" alt="WorkRequest System Logo"
                            class="card-img-left">

                       <p id="decrydingTasksDisplay">
    {{ $Userclick ? $decrydingDepartmentTasks : $decrydingTasks }}
</p>
                        <h2>ปฏิเสธงาน</h2>

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


        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const checkbox = document.getElementById('customSwitch');
        const completedTasksDisplay = document.getElementById('completedTasksDisplay');
        const pendingTasksDisplay = document.getElementById('pendingTasksDisplay'); // เพิ่มตัวแปรนี้
        const decrydingTasksDisplay = document.getElementById('decrydingTasksDisplay');

        checkbox.addEventListener('change', () => {
    const isChecked = checkbox.checked;

    // Send request to the server to update the value of Userclick
    fetch('update-userclick', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ Userclick: isChecked })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the displayed task counts without refreshing
            completedTasksDisplay.textContent = data.Userclick ? data.completedDepartmentTasks : data.completedTasks;
            pendingTasksDisplay.textContent = data.Userclick ? data.pendingDepartmentTasks : data.pendingTasks;
            decrydingTasksDisplay.textContent = data.Userclick ? data.decrydingDepartmentTasks : data.decrydingTasks;
        } else {
            alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
    });
});
    </script>
    <script>
        // Personal Chart
        const personalData = {
            labels: ['รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น'],
            datasets: [{
                label: 'ส่วนตัว',
                data: [{{ $rejectedTasks }}, {{ $processedTasks }}, {{ $completedTasks }}],
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
                data: [{{ $waitingDepartmentTasks }}, {{ $processedTaskDepartmentTasks  }},{{ $completedDepartmentTasks }}
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.querySelector('.custom-button');
            button.addEventListener('click', () => {
                button.classList.toggle('clicked'); // เพิ่มหรือลบคลาส clicked
            });
        });
    </script>
    <script>
        const checkbox = document.getElementById('customSwitch');
        const completedTasksDisplay = document.getElementById('completedTasksDisplay');

        checkbox.addEventListener('change', () => {
            const isChecked = checkbox.checked;

            // ส่งคำขอไปยังเซิร์ฟเวอร์เพื่ออัปเดตค่า Userclick
            fetch('update-userclick', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        Userclick: isChecked
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // อัปเดตข้อความใน <p> แบบไม่ต้องรีเฟรช
                        completedTasksDisplay.textContent = data.Userclick ? data.completedDepartmentTasks :
                            data.completedTasks;
                        pendingTasks.textContent = data.Userclick ? data.pendingDepartmentTasks :
                            data.pendingTasks;

                    } else {
                        alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
                });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('customSwitch');
            const labelText = document.querySelector('.switch-inner-text');

            checkbox.addEventListener('change', function () {
                labelText.textContent = checkbox.checked ? 'แผนก' : 'ส่วนตัว';
            });
        });
    </script>

</body>

</html>