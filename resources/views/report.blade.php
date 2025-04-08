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
            <img src="{{ asset('public/wrslogo.png') }}" alt="Logo" class="h-30 mx-auto">
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
                <div id="profileButton" class="bg-blue-700 text-white px-4 py-3 rounded-lg flex items-center justify-between hover:bg-blue-800" style="cursor: pointer;">
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
            <div id="logoutModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
                <div class="modal-container bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
                    <div class="modal-header flex justify-between items-center border-b pb-4 mb-4">
                        <div class="modal-title text-xl font-semibold text-gray-800">ยืนยันการออกจากระบบ</div>
                        <button class="modal-close text-gray-500 text-xl" id="closeModal">&times;</button>
                    </div>
                    <div class="modal-body text-center mb-6">
                        <p class="text-lg text-gray-600 mb-4">คุณแน่ใจว่าต้องการออกจากระบบหรือไม่?</p>
                        <div class="modal-buttons flex justify-center gap-4">
                            <button class="btn btn-confirm text-white bg-blue-600 px-6 py-2 rounded-full hover:bg-blue-700" id="confirmLogout">ยืนยัน</button>
                            <button class="btn btn-cancel text-gray-700 border border-gray-300 px-6 py-2 rounded-full hover:bg-gray-100" id="cancelLogout">ยกเลิก</button>
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
            window.location.href = 'login';  // หรือ URL ที่ต้องการ
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
    <!-- จบส่วน Sidebar -->

    <!-- Main Content -->
    <div class="ml-80 p-6 ">
        <h2 class="font-[Lato] text-[25.63px] font-bold text-[#0012E1] ">รายงานการดำเนินงาน</h2><br>

        <form method="get" action="{{ url('report') }}">
            <div class="flex justify-between">
                <div>
                    <div class="relative inline-block mr-8">


                        <i class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-[6px] text-[#0012E1]"></i>
                        @php
                            /*
                            relative คือให้วางองค์ประกอบอันอื่นๆใน กล่องนี้ได้ ซึ่งทำให้เราสามารถวางไอคอนไว้ตรงไหนก็ได้ภายใน container
                            inline-block แสดงผลแบบ inline อยู่ในบรรทัดเดียวกับข้อความอื่นได้
                            mr-8 ทำให้เว้นระยะด้านขวาไป 8
                            left-3	เลื่อนจากขอบซ้ายเข้ามา ประมาณ 12px
                            top-1/2	จัดให้ไอคอนอยู่ตรงกลางแนวแกนตั้ง (50% จากด้านบนของกล่องแม่)
                            -translate-y-[6px]	ขยับขึ้นไปเล็กน้อยอีก 6px เพื่อให้ ดูอยู่ตรงกลางแบบเนียนๆ (เพราะไอคอนไม่ได้สูงพอดีกับ select box)
                                                        */
                        @endphp
                        <select name="month"
                            class="pl-7 border border-gray-300 mt-1 rounded-[8px] w-[330px] h-[46px] mr-1 required font-[Lato] text-[14.22px] text-gray-500 ">

                            @php
                                // padding-left (pl)คือ เว้นระยะห่างด้านซ้าย ขององค์ประกอบ เว้นจากไอคอนปฏิทิน
                            @endphp
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

                            <option value="">เดือน</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                    {{ $thaiMonths[$m] }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="relative inline-block">
                        <i class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-[6px] text-[#0012E1]"></i>
                        <select name="year"
                            class=" pl-7 rounded-[8px] w-[199px] h-[46px] border border-gray-300 mt-1 font-[Lato] text-[14.22px] text-gray-500">
                            @php
                                $currentYear = now()->year + 543; // ปี พ.ศ.
                                $startYear = $currentYear - 1; // ดูย้อนหลังได้ 2 ปี

                            @endphp

                            <option value="">ปี</option>
                            @foreach (range($currentYear, $startYear) as $y)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach



                        </select>
                    </div>



                </div>


                <button type="submit"
                    class="w-[111px] h-[32px] bg-white-500 text-[#0012E1]
                rounded-lg border border-[#0012E1] mt-1 font-[Lato] text-[12.64px] ">ค้นหา</button>


            </div>
        </form>
        <div class="text-sm text-gray-500 font-[Lato] text-[14px]"><br>สรุปรายงานการดำเนินงาน</div>



        <div class="bg-white shadow-md rounded-lg mt-6 p-4 w-[1077px]"><br>
            @if($requests->count() > 0)  @php /* ถ้าพบข้อมูลสิ่งที่ดึงมาในตาราง countตัวนับจำนวน ไม่เป็น 0  */@endphp


            <h3 class="text-[Inter] text-[24px] text-gray-800 ml-4 ">
                @if ($selectedMonth && $selectedYear)
                    สรุปรายการ Work Request ประจำเดือน {{ $thaiMonths[$selectedMonth] }} ปี {{ $selectedYear }}
                @elseif ($selectedMonth)
                    สรุปรายการ Work Request ประจำเดือน {{ $thaiMonths[$selectedMonth] }}
                @elseif ($selectedYear)
                    สรุปรายการ Work Request ประจำปี {{ $selectedYear }}
                @else
                    สรุปรายการ Work Request ทั้งหมด
                @endif
            </h3>



            <div class="overflow-x-auto px-4 ">
                @php
                    /*
                        overflow-x-auto ถ้าเนื้อหาภายในกล่อง (div) กว้างเกินกว่าขนาดกล่อง ก็จะมีแถบเลื่อนแนวนอนให้เลื่อนดูเนื้อหา
                        px-4 เพิ่มระยะห่าง 16px ที่ขอบซ้ายและขวาของกล่อง ทำให้เนื้อหาภายในไม่ชิดขอบเกินไป

                        */
                @endphp

                <table
                    class="mt-4 border border-gray-300 border-separate border-spacing-0 text-sm rounded-md overflow-hidden  min-w-[982px] mr-1 ">
                    @php
                        /*
                    margin-top ซึ่งเป็นการตั้งค่าระยะห่างด้านบนของตาราง
                    border-separate แยกระหว่างเซลล์ของตาราง
                    border-spacing-ตั้งค่าระยะห่างระหว่างเซลล์ของตารางให้เป็น 0 (ไม่มีช่องว่างระหว่างเซลล์)
                    overflow-hidden  หากมีเนื้อหาที่ยาวเกินขอบตารางจะไม่แสดงออกมา
                    min-w-[982px] ความกว้างขั้นต่ำ เป็น 982px คือ ถ้าขนาดหน้าจอเล็กเกินไป ตารางจะไม่แคบกว่านี้ แต่จะสามารถขยายได้ตามขนาดหน้าจอ
                    mr ขยับตารางมาทางขวา ชิด container

                        */
                    @endphp

                    <div class="text-sm text-gray-500 ml font-[Inter] text-[14px]"><br>จำนวนทั้งสิ้น {{ $requests->count() }}
                        รายการ</div>

                    <thead style="height: 43px" class="font-[Inter] text-[12px]">
                        <tr class="bg-[#0012E1] text-white text-xs">
                            <th class="p-2 border border-gray-300 w-[48px]">#</th>
                            <th class="p-2 border border-gray-300 w-[91px]">เลขที่</th>
                            <th class="p-2 border border-gray-300 w-[74px] whitespace-nowrap text-[11px]">วันที่ร้องขอ
                            </th>
                            @php
                                //whitespace-nowrap เพื่อป้องกันข้อความขึ้นบรรทัดใหม่
                            @endphp
                            <th class="p-2 border border-gray-300 w-[103px]">ชื่อผู้ขอ</th>
                            <th class="p-2 border border-gray-300 w-[65px]">แผนก</th>
                            <th class="p-2 border border-gray-300 w-[269px]">งาน</th>
                            <th class="p-2 border border-gray-300 w-[84px]">ผู้ดำเนินการ</th>
                            <th class="p-2 border border-gray-300 w-[128px] whitespace-nowrap text-[11px]">
                                วันที่สิ้นสุดการทำงาน</th>
                            @php
                                //whitespace-nowrap เพื่อป้องกันข้อความขึ้นบรรทัดใหม่
                            @endphp
                            <th class="p-2 border border-gray-300 w-[120px]">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr class="border text-xs font-[Inter] text-[12px]">
                                <td class="p-2 border border-gray-300 text-center whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="p-2 border border-gray-300 text-center whitespace-nowrap">{{ $request->work_request_id }}</td>
                                <td class="p-2 border border-gray-300 text-center whitespace-nowrap">
                                    @php
                                    //whitespace-nowrap เพื่อป้องกันข้อความขึ้นบรรทัดใหม่
                                @endphp
                                    {{ \Carbon\Carbon::parse($request->work_create_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}
                                </td>
                                <td class="p-2 border border-gray-300 text-center whitespace-nowrap">
                                    {{ $request->requester_name }}</td>
                                <td class="p-2 border border-gray-300 text-center">
                                    {{ $request->department_name }}</td>
                                <td class="p-2 border border-gray-300 text-center">{{ $request->work_name }}</td>
                                <td class="p-2 border border-gray-300 text-center">
                                    @if ($request->work_author_type == 'P' )
                                        บุคคล
                                    @elseif ($request->work_author_type == 'D')
                                        แผนก
                                    @endif
                                </td>
                                <td class="p-2 border border-gray-300 text-center">
                                    @if ($request->work_confirm_date && $request->work_submit_date !== '0000-00-00')
                                        {{ \Carbon\Carbon::parse($request->work_submit_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}
                                    @else
                                        null
                                    @endif
                                </td>
                                <td class="p-2 border border-gray-300 text-center whitespace-nowrap text-[11px]">
                                    @if ($request->work_status == 'R' )
                                        รอดำเนินการ
                                    @elseif ($request->work_status == 'D')
                                        ถูกปฏิเสธ
                                    @elseif ($request->work_status == 'C')
                                        เสร็จสิ้น
                                    @elseif ($request->work_status == 'P')
                                        กำลังดำเนินการ
                                    @else
                                        {{ $request->work_status }}
                                    @endif


                                </td>

                            </tr>

                        @endforeach

                    </tbody>
                </table>
                @else @php /* ถ้าไม่พบข้อมูลสิ่งที่ดึงมาในตาราง countตัวนับจำนวน เป็น 0  */@endphp

                <div class="p-4 text-center text-gray-500 font-[Lato] text-[32px] top-1/2 -translate-y-[6px]">ไม่มีข้อมูล</div>

                @endif
            </div>
        </div>

    </div>
</body>

</html>
