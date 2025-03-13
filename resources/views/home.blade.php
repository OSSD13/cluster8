@extends('layouts.app')  {{-- ใช้ layout หลักจากไฟล์ app.blade.php --}}

@section('content')  {{-- เริ่มต้น section 'content' ที่จะถูกแทรกใน layout --}}
<div class="container-fluid">  {{-- ใช้ container-fluid เพื่อให้เต็มหน้าจอ --}}
    <div class="row">  {{-- ใช้ row เพื่อแบ่ง sidebar และ main content --}}

        <!-- Sidebar -->
        <div class="col-md-2 bg-white vh-100 p-3 shadow-sm">  {{-- คอลัมน์ซ้าย 2 ส่วน, สีพื้นหลังขาว, ความสูงเต็มจอ, มีเงา --}}
            <div class="text-center">  {{-- จัดกลางเนื้อหาภายใน --}}
                <img src="{{ asset('images/WRS_Logo.png') }}" alt="Logo" width="150">  {{-- แสดงโลโก้ --}}
            </div>
            <hr>  {{-- เส้นแบ่งระหว่างโลโก้และเมนู --}}
            <ul class="nav flex-column">  {{-- รายการเมนูแบบแนวตั้ง --}}
                <li class="nav-item">
                    <a href="#" class="nav-link active text-primary fw-bold">  {{-- เมนู Home (active) --}}
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-dark">  {{-- เมนูสร้างใบสั่งงาน --}}
                        <i class="bi bi-file-earmark-plus"></i> สร้างใบสั่งงาน
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-dark">  {{-- เมนูรายงานการดำเนินงาน --}}
                        <i class="bi bi-file-bar-graph"></i> รายงานการดำเนินงาน
                    </a>
                </li>
            </ul>
            <div class="card mt-3">  {{-- การ์ดแสดงข้อมูลผู้ใช้ --}}
                <div class="card-body text-center">
                    <img src="{{ asset('images/user.png') }}" class="rounded-circle" width="50" alt="User">  {{-- รูปโปรไฟล์ --}}
                    <h6 class="mt-2">วิรายุ คนโก้</h6>  {{-- ชื่อผู้ใช้ --}}
                    <p class="text-muted">anita@commerce.com</p>  {{-- อีเมล --}}
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4 bg-light">  {{-- คอลัมน์ขวา 10 ส่วน, พื้นหลังสีเทาอ่อน --}}
            <div class="d-flex justify-content-between align-items-center">  {{-- ใช้ flexbox จัดตำแหน่ง --}}
                <h2>Home</h2>  {{-- หัวข้อหลัก --}}
                <input type="text" class="form-control w-25" placeholder="Search anything here...">  {{-- ช่องค้นหา --}}
            </div>

            <div class="row mt-3">
                <!-- Task Plans -->
                <div class="col-md-6">
                    <div class="card shadow-sm">  {{-- การ์ดแสดงแผนงาน --}}
                        <div class="card-header fw-bold">แผนในขั้นตอนการดำเนินงาน</div>  {{-- หัวข้อ --}}
                        <div class="card-body">
                            <ul class="list-group">  {{-- รายการงาน --}}
                                <li class="list-group-item">📦 เรื่องงาน : สมัครเรียนพนักงาน <br> วันสิ้นสุดการทำงาน : 30/12/2025</li>
                                <li class="list-group-item">📦 เรื่องงาน : สมัครเรียนพนักงาน <br> วันสิ้นสุดการทำงาน : 30/12/2025</li>
                                <li class="list-group-item">📦 เรื่องงาน : สมัครเรียนพนักงาน <br> วันสิ้นสุดการทำงาน : 30/12/2025</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Task Progress Chart -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">แผนกราฟแสดงการทำงาน</div>
                        <div class="card-body">
                            <canvas id="taskChart"></canvas>  {{-- พื้นที่สำหรับกราฟ --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Personal Section -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">ส่วนตัวในแผนของฉัน</div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">📦 เรื่องงาน : สมัครเรียนพนักงาน <br> วันสิ้นสุดการทำงาน : 30/12/2025</li>
                                <li class="list-group-item">📦 เรื่องงาน : สมัครเรียนพนักงาน <br> วันสิ้นสุดการทำงาน : 30/12/2025</li>
                                <li class="list-group-item">📦 เรื่องงาน : สมัครเรียนพนักงาน <br> วันสิ้นสุดการทำงาน : 30/12/2025</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Tasks -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">กำลังดำเนินการ</div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    📦 เรื่องงาน : สมัครเรียนพนักงาน
                                    <button class="btn btn-primary btn-sm">เสร็จสิ้น</button>  {{-- ปุ่มเสร็จสิ้น --}}
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    📦 เรื่องงาน : สมัครเรียนพนักงาน
                                    <button class="btn btn-primary btn-sm">เสร็จสิ้น</button>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    📦 เรื่องงาน : สมัครเรียนพนักงาน
                                    <button class="btn btn-primary btn-sm">เสร็จสิ้น</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  {{-- โหลดไลบรารี Chart.js --}}
    <script>
        var ctx = document.getElementById('taskChart').getContext('2d');  {{-- ดึง element canvas --}}
        var taskChart = new Chart(ctx, {  {{-- สร้างกราฟแท่ง --}}
            type: 'bar',  {{-- ประเภทของกราฟเป็นแท่ง --}}
            data: {
                labels: ['รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น'],  {{-- ป้ายกำกับแกน X --}}
                datasets: [{
                    label: 'จำนวนงาน',
                    data: [40, 10, 30],  {{-- ข้อมูลของกราฟ --}}
                    backgroundColor: ['orange', 'yellow', 'green']  {{-- สีของแท่งกราฟ --}}
                }]
            }
        });
    </script>
</div>

@endsection  {{-- ปิด section 'content' --}}
