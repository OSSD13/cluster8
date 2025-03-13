@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 bg-primary vh-100 p-3 text-white">
            <div class="text-center">
                <img src="{{ asset('public\wrslogo.png') }}" alt="Logo" width="150">
            </div>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="#" class="nav-link active text-white fw-bold">
                        <i class="bi bi-house-door"></i> หน้าหลัก
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-file-earmark-plus"></i> สร้างใบสั่งงาน
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-file-bar-graph"></i> รายงานการดำเนินงาน
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Home</h2>
                <input type="text" class="form-control w-25" placeholder="Search anything here...">
            </div>

            <div class="row mt-3">
                <!-- Task Plans -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">แผนก</div>
                        <div class="card-body">
                            <p class="text-muted">ใบสั่งงานภายในแผน</p>
                            <ul class="list-group">
                                @for ($i = 0; $i < 4; $i++)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded bg-primary p-2 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <img src="{{ asset('images/box-icon-white.png') }}" width="30">
                                        </div>
                                        <div>
                                            <span class="fw-bold">ชื่องาน :</span> <span class="text-secondary">สมัครอีเมลพนักงาน</span><br>
                                            <span class="text-muted">วันสิ้นสุดการทำงาน : 30/12/2025</span>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-secondary"></i>
                                </li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Task Progress Chart 1 -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">กราฟแสดงการทำงานในแผน</div>
                        <div class="card-body">
                            <canvas id="taskChart1"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Task Progress Chart 2 -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">กราฟแสดงการทำงานของส่วนตัว</div>
                        <div class="card-body">
                            <canvas id="taskChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function createChart(canvasId) {
            var ctx = document.getElementById(canvasId).getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น'],
                    datasets: [{
                        label: 'จำนวนงาน',
                        data: [50, 15, 40],
                        backgroundColor: ['orange', 'yellow', 'green']
                    }]
                }
            });
        }
        createChart('taskChart1');
        createChart('taskChart2');
    </script>
</div>
@endsection