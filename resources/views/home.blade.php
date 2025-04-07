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
        
        /* สไตล์สำหรับ Popup */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
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
    </style>
</head>
<body class="bg-[#f3f4f6] flex min-h-screen">
    <!-- เริ่มส่วน Sidebar -->
    <div class="w-60 h-screen fixed top-0 left-0 bg-white shadow-lg flex flex-col">
        <!-- โลโก้ -->
        <div class="py-2 border-b">
            <img src="{{ asset('public/wrslogo.png') }}" alt="Logo" class="h-10 mx-auto">
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
    
    <!-- เริ่มส่วนเนื้อหาหลัก -->
    <div class="flex-1 p-8 ml-60">
        <!-- หัวข้อและช่องค้นหา -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#0012E1]">หน้าหลัก</h1>
            
            <!-- ช่องค้นหา -->
            <div class="relative">
                <input type="text" placeholder="Search anything here..." class="pl-4 pr-10 py-2 border rounded-full w-80">
                <button class="absolute right-3 top-2.5">
                    <i class="fas fa-search text-[#9ca3af]"></i>
                </button>
            </div>
        </div>
        
        <!-- กริดแสดงข้อมูล -->
        <div class="grid grid-cols-2 gap-6">
            <!-- การ์ดแสดงใบสั่งงานตามแผนก -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">แผนก</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานตามแผนก</p>
                </div>

                <div class="space-y-4">
                    <!-- รายการงาน 1 -->
                    <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
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
                    <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
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
                    <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
                    <div class="bg-[#3b82f6] p-2 rounded-lg w-18 h-18 flex items-center justify-center">
                    <i class="fas fa-box text-white  text-2xl"></i>
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
                    <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
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
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">แผนก</h2>
                    <p class="text-sm text-[#6b7280]">กราฟแสดงการทำงานตามแผนก</p>
                </div>
                
                
                <!-- กราฟแท่งแนวตั้ง -->
                <div class="h-64 flex items-end justify-evenly">
                    <div class="flex flex-col items-center">
                        <div class="bg-[#facc15] w-8 rounded-t" style="height: 180px"></div> 
                        <div class="mt-2 text-sm">รอดำเนินการ</div>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="bg-[#fef08a] w-8 rounded-t" style="height: 40px"></div>
                        <div class="mt-2 text-sm">กำลังดำเนินการ</div>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="bg-[#22c55e] w-8 rounded-t" style="height: 160px"></div>
                        <div class="mt-2 text-sm">เสร็จสิ้น</div>
                    </div>
                </div>
            </div>
            <!-- การ์ดแสดงใบสั่งงานส่วนตัว -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">ส่วนตัว</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานส่วนตัว</p>
                </div>

                <div class="space-y-4">
                    <!-- รายการงาน 1 -->
                    <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
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
                    <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
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
                    <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
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
                    <div class="work-item flex items-center gap-3 p-3 rounded-lg bg-white hover:bg-gray-100 shadow cursor-pointer transition">
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
                
                <!-- กราฟแท่งแนวตั้ง -->
                <div class="h-64 flex items-end justify-evenly">
                    <div class="flex flex-col items-center">
                        <div class="bg-[#facc15] w-8 rounded-t" style="height: 180px"></div>
                        <div class="mt-2 text-sm">รอดำเนินการ</div>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="bg-[#fef08a] w-8 rounded-t" style="height: 40px"></div>
                        <div class="mt-2 text-sm">กำลังดำเนินการ</div>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="bg-[#22c55e] w-8 rounded-t" style="height: 140px"></div>
                        <div class="mt-2 text-sm">เสร็จสิ้น</div>
                    </div>
                </div>
            </div>
            
            
            
            <!-- การ์ดแสดงงานที่กำลังดำเนินการ (เต็มความกว้าง) -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6 col-span-2">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">กำลังดำเนินการ</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานอยู่ระหว่างการทำงาน</p>
                </div>

                <div class="space-y-4">
                    <!-- รายการงานที่กำลังดำเนินการ 1 -->
                    <div class="flex items-center justify-between border-b pb-4 cursor-pointer work-item">
                        <div class="flex items-center">
                            <div class="bg-[#dbeafe] p-2 rounded text-[#2563eb] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สมัครรับสมัพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <button class="bg-[#ffffff] border border-[#000000] text-[#000000] px-4 py-1 rounded-full text-sm mr-4 hover:bg-[#000000] hover:text-[#ffffff] transition-colors duration-200">เสร็จสิ้น</button>
                            <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                        </div>
                    </div>
                    
                    <!-- รายการงานที่กำลังดำเนินการ 2 -->
                    <div class="flex items-center justify-between border-b pb-4 cursor-pointer work-item">
                        <div class="flex items-center">
                            <div class="bg-[#dbeafe] p-2 rounded text-[#2563eb] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สมัครรับสมัพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <button class="bg-[#ffffff] border border-[#000000] text-[#000000] px-4 py-1 rounded-full text-sm mr-4 hover:bg-[#000000] hover:text-[#ffffff] transition-colors duration-200">เสร็จสิ้น</button>
                            <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                        </div>
                    </div>
                    
                    <!-- รายการงานที่กำลังดำเนินการ 3 -->
                    <div class="flex items-center justify-between border-b pb-4 cursor-pointer work-item">
                        <div class="flex items-center">
                            <div class="bg-[#dbeafe] p-2 rounded text-[#2563eb] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สมัครรับสมัพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <button class="bg-[#ffffff] border border-[#000000] text-[#000000] px-4 py-1 rounded-full text-sm mr-4 hover:bg-[#000000] hover:text-[#ffffff] transition-colors duration-200">เสร็จสิ้น</button>
                            <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                        </div>
                    </div>
                    
                    <!-- รายการงานที่กำลังดำเนินการ 4 -->
                    <div class="flex items-center justify-between cursor-pointer work-item">
                        <div class="flex items-center">
                            <div class="bg-[#dbeafe] p-2 rounded text-[#2563eb] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สมัครรับสมัพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <button class="bg-[#ffffff] border border-[#000000] text-[#000000] px-4 py-1 rounded-full text-sm mr-4 hover:bg-[#000000] hover:text-[#ffffff] transition-colors duration-200">เสร็จสิ้น</button>
                            <i class="fas fa-chevron-right text-[#9ca3af]"></i>
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
        <img src="{{ asset('public/wrslogo.png') }}" alt="WRS" class="inline-block h-5 ml-2">
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
        <span class="font-semibold">ผู้ส่ง :</span> วิรายุ คนโก้
    </div>
    <div>
        <span class="font-semibold">แผนก :</span> HR
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
    <button onclick="closePopup()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">ปฏิเสธ</button>
    <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">รับงาน</button>
    </div>

  </div>
</div>



    <script>
        
        // JavaScript for popup functionality
        document.addEventListener('DOMContentLoaded', function() {
            const workItems = document.querySelectorAll('.work-item');
            const popup = document.getElementById('workItemPopup');
            const closeButtons = document.querySelectorAll('.close-popup, .close-popup-btn');
            const popupTitle = document.getElementById('popup-title');
            const popupDate = document.getElementById('popup-date');
            
            workItems.forEach(item => {
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
                    popup.style.display = 'flex';
                    document.body.classList.add('popup-open');
                });
            });
            
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    popup.style.display = 'none';
                    document.body.classList.remove('popup-open');
                });
            });
            
            // Close popup when clicking outside the content
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    document.body.classList.remove('popup-open');
                }
            });
        });
    </script>
</body>
</html>
