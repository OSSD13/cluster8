<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Work Request System & Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    /* Dashboard custom styles */
    body {
        font-family: 'Noto Sans Thai',sans-serif;
      background-color: #f5f6fa;
      color: #343a40;
    }
    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
    }
    .header-container h1 {
      text-align: left;
      color: #0012E1;
      margin-bottom: 0;
    }
    .summary-container {
      margin-bottom: 40px;
      background-color: #FFFFFF;
      padding: 50px;
      border-radius: 8px;
    }
    .summary {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      gap: 20px;
    }
    .summary-title {
      flex-basis: 100%;
      text-align: left;
      font-size: 24px;
      color: #151D48;
      margin-bottom: 20px;
    }
    .card {
      border-radius: 8px;
      padding: 20px;
      width: 250px;
      height: 184px;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .card.card-completed { background-color: #DCFCE7; }
    .card.card-progress  { background-color: #FFF4DE; }
    .card.card-rejected  { background-color: #FFE2E5; }
    .card h2 {
      font-size: 18px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
      color: #151D48;
    }
    .card p {
      font-size: 28px;
      font-weight: bold;
      margin: 0;
    }
    .chart-container { margin-bottom: 40px;

     }
    .chart-row {
      display: flex;
      flex-wrap: nowrap;
      gap: 300px;
    }
    .chart-section {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      margin left: ;
    }
    .half { width: 510px;
    height: 419px; }
    .chart-title {
      text-align: left;
      margin-bottom: 20px;
      color: #0c0c0c;
      font-size: 20px;
      font-weight: bold;
    }
    canvas {
  display: block;
  width: 450px; /* หรือขนาดที่ต้องการ */
  height: auto;
  margin: 0 auto;
}

  </style>
</head>
<body class="flex min-h-screen bg-[#f3f4f6]">
  <!-- เริ่มส่วน Sidebar -->
  <div class="w-60 h-screen fixed top-0 left-0 bg-white shadow-lg flex flex-col">
    <!-- โลโก้ -->
    <div class="py-2 border-b">
        <img src="{{ asset('public/wrslogo.png') }}" alt="Logo" class="h-10 mx-auto">
    </div>
    
    <!-- เมนู -->
    <div class="flex-1 px-3 py-6 space-y-2">
        <a href="home" class="flex items-center px-4 py-3 text-gray-800 hover:bg-gray-100 rounded-lg">
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
        <div class="bg-blue-700 text-white px-4 py-3 rounded-lg flex items-center justify-between hover:bg-blue-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white text-blue-700 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-user text-lg"></i>
                </div>
                <div class="leading-tight">
                    <div class="text-sm font-semibold">จิรายุ คนโก้</div>
                    <div class="text-xs">anita@commerce.com</div>
                </div>
            </div>
            <i class="fas fa-arrow-right text-white text-sm"></i>
        </div>
    </div>
</div>
<!-- จบส่วน Sidebar -->

  <!-- Main Content; เพิ่มคลาส ml-60 เพื่อขยับออกจาก sidebar -->
  <div class="ml-60 w-full">
    <div class="container">
      <!-- Header -->
      <div class="header-container">
        <h1 style="font-size : 25.63px">แดชบอร์ด</h1>
      </div>

      <!-- Summary Section -->
      <div class="summary-container">
        <h2 class="text-xl font-semibold mb-4">สรุปผล

            <span class="text-gray-500 text-sm font-normal">สรุปผลการทำงานของสัปดาห์นี้
            </span>
        </h2>
        <div class="summary">
          <div class="card card-completed">
            <p>100</p>
            <h2><span class="icon">&#x2713;</span>ดำเนินการเสร็จสิ้น</h2>
          </div>
          <div class="card card-progress">
            <p>20</p>
            <h2><span class="icon">&#x23F0;</span> รอดำเนินการ</h2>
          </div>
          <div class="card card-rejected">
            <p>5</p>
            <h2><span class="icon">&#x1F4CB;</span> ปฏิเสธ</h2>
          </div>
        </div>
      </div>

      <!-- Chart Section: Personal & Department -->
      <div class="chart-container">
        <div class="chart-row">
          <div class="chart-section half">
            <div class="chart-title mb-0">ส่วนตัว</div>
            <div class="text-gray-500 text-sm font-normal">กราฟแสดงการทำงานภายในสัปดาห์ของส่วนตัว</div>
            <canvas id="personalChart"></canvas>
          </div>
          <div class="chart-section half">
            <div class="chart-title mb-0">แผนก</div>
            <div class="text-gray-500 text-sm font-normal">กราฟแสดงการทำงานภายในสัปดาห์ของแผนก</div>
            <canvas id="departmentChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Chart Section: Weekly Completion -->
      <div class="chart-container">
        <div class="chart-section">
          <div class="chart-title">ดำเนินการเสร็จสิ้นภายในสัปดาห์</div>
          <canvas id="weekCompletionChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart.js Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Personal Chart
    const personalData = {
      labels: ['รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น'],
      datasets: [{
        label: 'ส่วนตัว',
        data: [50, 10, 40],
        backgroundColor: ['#ff6384', '#36a2eb', '#4bc0c0'],
        borderRadius: 8,
        borderSkipped: false,
        barPercentage: 0.6,
        categoryPercentage: 0.7
      }]
    };
    const personalConfig = {
      type: 'bar',
      data: personalData,
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false } },
          y: { grid: { color: '#eee' }, beginAtZero: true }
        }
      }
    };
    new Chart(document.getElementById('personalChart'), personalConfig);

    // Department Chart
    const departmentData = {
      labels: ['รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น'],
      datasets: [{
        label: 'แผนก',
        data: [50, 10, 40],
        backgroundColor: ['#ff9f40', '#ffcd56', '#4bc0c0'],
        borderRadius: 8,
        borderSkipped: false,
        barPercentage: 0.6,
        categoryPercentage: 0.7
      }]
    };
    const departmentConfig = {
      type: 'bar',
      data: departmentData,
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false } },
          y: { grid: { color: '#eee' }, beginAtZero: true }
        }
      }
    };
    new Chart(document.getElementById('departmentChart'), departmentConfig);

    // Weekly Completion Chart
    const weekCompletionData = {
      labels: ['จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.', 'อา.'],
      datasets: [{
        label: 'งานเสร็จสิ้น',
        data: [12, 18, 22, 15, 27, 13, 18],
        backgroundColor: '#4bc0c0',
        borderRadius: 8,
        borderSkipped: false,
        barPercentage: 0.6,
        categoryPercentage: 0.7
      }]
    };
    const weekCompletionConfig = {
      type: 'bar',
      data: weekCompletionData,
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false } },
          y: { grid: { color: '#eee' }, beginAtZero: true }
        }
      }
    };
    new Chart(document.getElementById('weekCompletionChart'), weekCompletionConfig);
  </script>
</body>
</html>
