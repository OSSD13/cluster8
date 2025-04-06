<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<script>
    setTimeout(() => {
        document.getElementById("rowToHide").style.display = "none";
    }, 2000);
</script>
<body class="bg-[#f3f4f6] flex min-h-screen">
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
            <a href="report" class="flex items-center px-4 py-3 bg-blue-500 text-white rounded-lg">
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

    <!-- Main Content -->
    <div class="ml-64 p-6 w-full">
        <h2 class="text-2xl font-bold text-gray-800">รายงานการดำเนินงาน</h2>
        <div class="flex space-x-4 mt-4">
            <select class="border p-2 rounded">
                <option>เดือน</option>
            </select>
            <select class="border p-2 rounded">
                <option>ปี</option>
            </select>
            <button class="px-4 py-2 bg-blue-500 text-white rounded">ค้นหา</button>
            <button class="text-gray-600">Apply Filter</button>
        </div>
        <div class="bg-white shadow-md rounded-lg mt-6 p-4">
            <h3 class="font-bold text-gray-800">สรุปรายการ Work Request ประจำเดือน กุมภาพันธ์ ปี 2568</h3>
            <table  class="w-full mt-4 border">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-2">#</th>
                        <th class="p-2">เลขที่</th>
                        <th class="p-2">วันที่ร้องขอ</th>
                        <th class="p-2">ชื่อผู้ขอ</th>
                        <th class="p-2">แผนก</th>
                        <th class="p-2">งาน</th>
                        <th class="p-2">ผู้ดำเนินการ</th>
                        <th class="p-2">วันที่เสร็จ</th>
                        <th class="p-2">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="rowToHide" class="border">
                        <td class="p-2 text-center">1</td>
                        <td class="p-2">HR-680003</td>
                        <td class="p-2">27 ก.พ. 68</td>
                        <td class="p-2">สี่โลร้อย คนดี</td>
                        <td class="p-2">HR</td>
                        <td class="p-2">xxxxxxxxxx</td>
                        <td class="p-2">xxxxxxxxx</td>
                        <td class="p-2">xx xx xxxx</td>
                        <td class="p-2">xxxxxx</td>
                    </tr>
                    <tr class="border">
                        <td class="p-2 text-center">2</td>
                        <td class="p-2">HR-680004</td>
                        <td class="p-2">27 ก.พ. 68</td>
                        <td class="p-2">สี่โลร้อย คนดี</td>
                        <td class="p-2">HR</td>
                        <td class="p-2">xxxxxxxx</td>
                        <td class="p-2">xxxxxxxxx</td>
                        <td class="p-2">xx xx xxxx</td>
                        <td class="p-2">xxxxxx</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
