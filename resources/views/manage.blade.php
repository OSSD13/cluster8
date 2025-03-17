<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#f3f4f6] flex min-h-screen">
    <!-- Sidebar - Fixed Position -->
    <div class="w-60 bg-[#ffffff] shadow-lg fixed h-full">
        <div class="p-4 border-b">
            <div class="flex items-center">
                <img src="{{ asset('public\wrslogo.png') }}" alt="WorkRequest System Logo" class="mr-3 h-13">
            </div>
        </div>
    
        <!-- Sidebar Menu -->
        <div class="py-4">
            <a href="home" class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-home mr-3"></i>
                <span>หน้าหลัก</span>
            </a>
            
            
            <a href="workrequest" class="flex items-center px-4 py-3 bg-[#3b82f6] text-[#ffffff] rounded-lg mx-2 mb-2">
                <i class="fas fa-clipboard-list mr-3"></i>
                <span>สร้างใบสั่งงาน</span>
            </a>
            
            <a href="report" class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
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
        <!-- Main Content -->
        <div class="col-md-10">
            <h2 class="mt-4">สร้างใบสั่งงาน</h2>
            
            <div class="row mt-4">
                <!-- เสร็จสิ้น -->
                <div class="col-md-6">
                    <h4>เสร็จสิ้น</h4>
                    <div class="card card-status card-success">
                        <div class="card-body">
                            <h5>สมัครรับพัสดุ...</h5>
                            <p>สี่โลร้อย คนดี</p>
                            <span class="badge bg-dark">หมายเหตุ</span>
                        </div>
                    </div>
                </div>

                <!-- กำลังดำเนินการ -->
                <div class="col-md-6">
                    <h4>กำลังดำเนินการ</h4>
                    <div class="card card-status card-warning">
                        <div class="card-body">
                            <h5>XXXXXX</h5>
                            <p>XXXXXX</p>
                            <button class="btn btn-primary">กำลังดำเนินการ</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ประวัติ -->
            <div class="row mt-4">
                <div class="col-md-8">
                    <h4>ประวัติ</h4>
                    <div class="card card-status card-light">
                        <div class="card-body">
                            <h5>XXXXXXXXX</h5>
                            <p>XXXXXXXXX</p>
                            <button class="btn btn-success">เสร็จสิ้น</button>
                            <button class="btn btn-danger">ปฏิเสธ</button>
                        </div>
                    </div>
                </div>

                <!-- แบบร่าง -->
                <div class="col-md-4">
                    <h4>แบบร่าง</h4>
                    <div class="card card-status card-secondary">
                        <div class="card-body">
                            <h5>XXXXXXXXX</h5>
                            <button class="btn btn-light">แบบร่าง</button>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Main Content -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>