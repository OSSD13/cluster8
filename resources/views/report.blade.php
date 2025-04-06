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
    <!-- Sidebar - Fixed Position -->
    <div class="w-60 bg-[#ffffff] shadow-lg fixed h-full">
        <div class="p-4 border-b">
            <div class="flex items-center">
                <img src="{{ asset('public\wrslogo.png') }}" alt="WorkRequest System Logo" class="mr-3 h-13">
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="py-4">
            <a href="home"
                class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-home mr-3"></i>
                <span>หน้าหลัก</span>
            </a>


            <a href="workrequest"
                class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-clipboard-list mr-3"></i>
                <span>สร้างใบสั่งงาน</span>
            </a>

            <a href="report" class="flex items-center px-4 py-3 bg-[#3b82f6] text-[#ffffff] rounded-lg mx-2 mb-2">
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
    <div class="ml-64 p-6 w-full">
        <h2 class="text-2xl font-bold text-[#0012E1]">รายงานการดำเนินงาน</h2><br>

        <form method="GET" action="{{ url('report') }}">
        <div class="flex justify-between">
            <div>
                <select name="month" class=" border border-gray-300 mt-1 rounded-[8px] w-[330px] h-[46px] mr-8">
                    @php
                        $thaiMonths = [
                            1 => 'มกราคม',
                            2 => 'กุมภาพันธ์',
                            3 => 'มีนาคม',
                            4 => 'เมษายน',
                            5 => 'พฤษภาคม',
                            6 => 'มิถุนายน',
                            7 => 'กรกฎาคม',
                            8 => 'สิงหาคม',
                            9 => 'กันยายน',
                            10 => 'ตุลาคม',
                            11 => 'พฤศจิกายน',
                            12 => 'ธันวาคม',
                        ];
                    @endphp

                    <option class="">เดือน</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ $thaiMonths[$m] }}
                        </option>
                    @endforeach




                </select>
                <select name="year" class="rounded-[8px] w-[199px] h-[46px] border border-gray-300 mt-1">
                    @php
                        $currentYear = now()->year + 543; // ปี พ.ศ.
                        $startYear = $currentYear - 1; // ดูย้อนหลังได้ 2 ปี

                    @endphp

                    <option value="$currentYear">ปี</option>
                    @foreach (range($currentYear, $startYear) as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach



                </select>
            </div>


                <button type="submit" class="w-[111px] h-[32px] bg-white-500 text-[#0012E1]
                rounded-lg border border-[#0012E1] mt-1 ">ค้นหา</button>

        </div>
        </form>
        <div class="text-sm text-gray-500"><br>สรุปรายงานการดำเนินงาน</div>
        <div class="bg-white shadow-md rounded-lg mt-6 p-4">
            <h3 class="font-bold text-gray-800">สรุปรายการ Work Request ประจำเดือน กุมภาพันธ์ ปี 2568</h3>
            <table class="w-full mt-4 border">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-2">#</th>
                        <th class="p-2">เลขที่</th>
                        <th class="p-2">วันที่ร้องขอ</th>
                        <th class="p-2">ชื่อผู้ขอ</th>
                        <th class="p-2">แผนก</th>
                        <th class="p-2">งาน</th>
                        <th class="p-2">ผู้ดำเนินการ</th>
                        <th class="p-2">วันที่สิ้นสุดการทำงาน</th>
                        <th class="p-2">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request) @php /*วนลูปแสดงข้อมูลแต่ละแถวจาก $requests*/ @endphp

                        <tr class="border">
                            <td class="p-2 text-center">{{ $loop->iteration }}</td>
                            @php /*แสดงลำดับตัวเลขอัตโนมัติเมื่อมีการวนลูป 1,2,3,4...... */ @endphp


                            <td class="p-2 text-center">{{ $request->work_request_id }}</td>
                            @php /*แสดงรหัสใบสั่งงาน (work_request_id)*/ @endphp

                            <td class="p-2 text-center">{{ \Carbon\Carbon::parse($request->work_create_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }} </td>
                            @php /*แปลงวันที่จากฐานข้อมูล (work_create_date) ให้อยู่ในรูปแบบ "วัน เดือน ปี เป็นไทย" โดยใช้ Carbon มี->addYear(543)เพิ่มมา เพื่อทำให้เป็น พ.ศ. */ @endphp

                            <td class="p-2 text-center">{{ $request->work_create_by_user_id }}</td>
                            @php /*แสดงรหัสผู้สร้างงาน*/ @endphp


                            <td class="p-2 text-center">{{ $request->work_created_by_department_id }}</td>
                            @php /*แสดงรหัสแผนก*/ @endphp


                            <td class="p-2 text-center">{{ $request->work_name }}</td>
                            @php /*แสดงชื่อของงานที่ร้องขอ*/ @endphp

                            <td class="p-2 text-center">{{ $request->work_author_type }}</td>
                            @php /*แสดงประเภทผู้ดำเนินการ*/ @endphp

                            <td class="p-2 text-center">
                            @if($request->work_confirm_date && $request->work_confirm_date !== '0000-00-00')
                            {{ \Carbon\Carbon::parse($request->work_confirm_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}
                            @else
                            ยังไม่เสร็จ
                            @endif
                            @php /*แสดงวันที่เสร็จงาน ถ้าไม่เป็น 0000-00-00 ที่เป็น string จะแสดงข้อความว่า "ยังไม่เสร็จ โดยมี ->addYear(543)เพิ่มมา เพื่อทำให้เป็น พ.ศ.*/ @endphp


                            <td class="p-2 text-center">{{ $request->work_status }}</td>
                            @php /*แสดงสถานะของงาน*/ @endphp
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>
