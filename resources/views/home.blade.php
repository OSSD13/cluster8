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
            <a href="home" class="flex items-center px-4 py-3 bg-[#3b82f6] text-[#ffffff] rounded-lg mx-2 mb-2">
                <i class="fas fa-home mr-3"></i>
                <span>หน้าหลัก</span>
            </a>
            
            
            <a href="workrequest" class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
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
    
    <!-- Main Content - Add left margin to accommodate fixed sidebar -->
    <div class="flex-1 p-8 ml-60">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#0012E1]">หน้าหลัก</h1>
            
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" placeholder="Search anything here..." class="pl-4 pr-10 py-2 border rounded-full w-80">
                <button class="absolute right-3 top-2.5">
                    <i class="fas fa-search text-[#9ca3af]"></i>
                </button>
            </div> 
        </div>
        
        <!-- Dashboard Grid -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Top Left Card -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">แผนก</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานตามแผนก</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center">
                            <div class="bg-[#2563eb] p-2 rounded text-[#ffffff] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สร้างอีเมลพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                    </div>
                    
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center">
                            <div class="bg-[#2563eb] p-2 rounded text-[#ffffff] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สร้างอีเมลพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                    </div>
                    
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center">
                            <div class="bg-[#2563eb] p-2 rounded text-[#ffffff] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สร้างอีเมลพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-[#2563eb] p-2 rounded text-[#ffffff] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สร้างอีเมลพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                    </div>
                </div>
            </div>
            
            <!-- Top Right Card -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">แผนก</h2>
                    <p class="text-sm text-[#6b7280]">กราฟแสดงการทำงานตามแผนก</p>
                </div>
                
                <div class="h-64 flex items-end justify-around">
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
            
            <!-- Bottom Left Card -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">ส่วนตัว</h2>
                    <p class="text-sm text-[#6b7280]">กราฟแสดงการทำงานของส่วนตัว</p>
                </div>
                
                <div class="h-64 flex items-end justify-around">
                    <div class="flex flex-col items-center">
                        <div class="bg-[#facc15] w-16 rounded-t" style="height: 180px"></div>
                        <div class="mt-2 text-sm">รอดำเนินการ</div>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="bg-[#fef08a] w-16 rounded-t" style="height: 40px"></div>
                        <div class="mt-2 text-sm">กำลังดำเนินการ</div>
                    </div>
                    
                    <div class="flex flex-col items-center">
                        <div class="bg-[#22c55e] w-16 rounded-t" style="height: 140px"></div>
                        <div class="mt-2 text-sm">เสร็จสิ้น</div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Right Card -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">ส่วนตัว</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานส่วนตัว</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center">
                            <div class="bg-[#D7FFC3] p-2 rounded text-[#2563eb] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สร้างอีเมลพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                    </div>
                    
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center">
                            <div class="bg-[#D7FFC3] p-2 rounded text-[#2563eb] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สร้างอีเมลพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                    </div>
                    
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center">
                            <div class="bg-[#D7FFC3] p-2 rounded text-[#2563eb] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สมัครรับสมัพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-[#D7FFC3] p-2 rounded text-[#2563eb] mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div>ชื่องาน : สมัครรับสมัพนักงาน</div>
                                <div class="text-sm text-[#6b7280]">วันสิ้นสุดการทำงาน : 30/12/2025</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-[#9ca3af]"></i>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Full-Width Card -->
            <div class="bg-[#ffffff] rounded-lg shadow p-6 col-span-2">
                <div class="border-b pb-2 mb-4">
                    <h2 class="text-lg font-bold">กำลังดำเนินการ</h2>
                    <p class="text-sm text-[#6b7280]">ใบสั่งงานอยู่ระหว่างการทำงาน</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b pb-4">
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
                    
                    <div class="flex items-center justify-between border-b pb-4">
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
                    
                    <div class="flex items-center justify-between border-b pb-4">
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
                    
                    <div class="flex items-center justify-between">
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
        </div>
    </div>
</body>
</html>
