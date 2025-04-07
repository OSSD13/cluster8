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
    <div class="w-60 bg-[#ffffff] shadow-lg fixed h-full">
        <div class="p-4 border-b">
            <div class="flex items-center">
                <img src="{{ asset('public/wrslogo.png') }}" alt="WorkRequest System Logo">
            </div>
        </div>
        <!-- Sidebar Menu -->
        <div class="py-4">
            <a href="home" class="flex items-center px-4 py-3 bg-[#3b82f6] text-[#ffffff] rounded-lg mx-2 mb-2">
                <i class="fas fa-home mr-3"></i>
                <span>หน้าหลัก</span>
            </a>
            <a href="workrequest"
                class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-clipboard-list mr-3"></i>
                <span>สร้างใบสั่งงาน</span>
            </a>
            <a href="report"
                class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
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
                        <p>100</p>
                        <h2>ดำเนินการเสร็จสิ้น</h2>
                    </div>

                    <div class="card card-progress">
                        <img src="{{ asset('public\Ellipse 3 (3).png') }}" alt="WorkRequest System Logo"
                            class="card-img-left">
                        <p>20</p>
                        <h2>รอดำเนินการ</h2>
                    </div>
                    <div class="card card-rejected">
                        <img src="{{ asset('public\asset\reject.png') }}" alt="WorkRequest System Logo"
                            class="card-img-left">

                        <p>5</p>
                        <h2>ปฏิเสธ</h2>
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
            <div class="chart-section">
                <div class="chart-title">ดำเนินการเสร็จสิ้นภายในสัปดาห์</div>
                <canvas id="weekCompletionChart" style="max-width: 100%; height: 400px;"></canvas>
            </div>
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
                data: [55, 8, 40],
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
                data: [55, 8, 40],
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

        // Weekly Completion Chart
        const weekCompletionData = {
            labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
            datasets: [{
                    label: 'แผนก',
                    data: [30, 35, 12, 33, 25, 32, 45],
                    backgroundColor: '#0029FF',
                    barPercentage: 0.45,
                    categoryPercentage: 0.6
                },
                {
                    label: 'บุคคล',
                    data: [27, 25, 45, 15, 24, 28, 23],
                    backgroundColor: '#00D27F',
                    barPercentage: 0.45,
                    categoryPercentage: 0.6
                }
            ]
        };

        const weekCompletionConfig = {
            type: 'bar',
            data: weekCompletionData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true, // ใช้จุดแทนสี่เหลี่ยม
                            pointStyle: 'roundedRect', // เปลี่ยนเป็นสี่เหลี่ยมไม่มีมุม
                            padding: 20, // เพิ่มระยะห่างระหว่าง Legend
                            font: {
                                size: 14
                            }
                        }
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

        new Chart(document.getElementById('weekCompletionChart'), weekCompletionConfig);
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
