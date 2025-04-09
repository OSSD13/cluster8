<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
    <div class="ml-80 p-6 ">
        <h2 class="font-[Lato] text-[25.63px] font-bold text-[#0012E1] ">รายงานการดำเนินงาน</h2><br>

        <form method="get" action="{{ url('report') }}">
            <div class="flex justify-between">

                <div>
                    <div x-data="{ openMonth: false, selectedMonth: '', selectedMonthText: '' }" @click.outside="openMonth = false" class="relative inline-block mr-6">

                        {{-- กล่อง dropdown เดือน--}}
                        <div @click="openMonth = !openMonth"
                            class="appearance-none pl-8 border border-gray-300 mt-2 rounded-[8px] w-[330px] h-[46px] mr required font-[Lato] text-[14.22px] text-gray-500 flex items-center justify-between cursor-pointer relative hover:shadow-lg transition-shadow">
                            <i class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-[#0012E1]"></i>
                            <span x-text="selectedMonthText || 'เดือน'"></span>
                            <i :class="openMonth ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                                class="absolute right-10 top-1/2 transform -translate-y-1/2 text-[#0012E1]"></i>
                        </div>

                        {{-- รายการเดือนแบบไม่มี scrollbar --}}
                        <ul x-show="openMonth" x-transition
                            class="absolute z-10 mt-1 w-[330px] bg-white border border-gray-300 rounded-[8px] shadow-lg overflow-hidden">
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
                                    /*
                            relative คือให้วางองค์ประกอบอันอื่นๆใน กล่องนี้ได้ ซึ่งทำให้เราสามารถวางไอคอนไว้ตรงไหนก็ได้ภายใน container
                            inline-block แสดงผลแบบ inline อยู่ในบรรทัดเดียวกับข้อความอื่นได้
                            mr-8 ทำให้เว้นระยะด้านขวาไป 8
                            left-3	เลื่อนจากขอบซ้ายเข้ามา ประมาณ 12px
                            top-1/2	จัดให้ไอคอนอยู่ตรงกลางแนวแกนตั้ง (50% จากด้านบนของกล่องแม่)
                            -translate-y-[6px]	ขยับขึ้นไปเล็กน้อยอีก 6px เพื่อให้ ดูอยู่ตรงกลางแบบเนียนๆ (เพราะไอคอนไม่ได้สูงพอดีกับ select box)
                                                        */
                                ];
                            @endphp

                            @foreach ($thaiMonths as $key => $month)
                                <li @click="selectedMonth = '{{ $key }}'; selectedMonthText = '{{ $month }}'; openMonth = false"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    {{ $month }}
                                </li>
                            @endforeach
                        </ul>

                        {{-- input สำหรับส่งค่า --}}
                        <input type="hidden" name="month" x-model="selectedMonth">
                    </div>



                    <div x-data="{ openYear: false, selectedYear: '', selectedYearText: '' }" @click.outside="openYear = false" class="relative inline-block">

                        {{-- กล่อง dropdown ปี --}}
                        <div @click="openYear = !openYear"
                            class="appearance-none pl-8 border border-gray-300 mt-2 rounded-[8px] w-[199px] h-[46px] font-[Lato] text-[14.22px] text-gray-500 flex items-center justify-between cursor-pointer relative hover:shadow-lg transition-shadow">
                            <i class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-[#0012E1]"></i>
                            <span x-text="selectedYearText || 'ปี'"></span>
                            <i :class="openYear ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[#0012E1]"></i>
                        </div>

                        {{-- รายการปีแบบไม่มี scrollbar --}}
                        <ul x-show="openYear" x-transition
                            class="absolute z-10 mt-1 w-[199px] bg-white border border-gray-300 rounded-[8px] shadow-lg overflow-hidden">
                            @php
                                $currentYear = now()->year + 543;
                                $startYear = $currentYear - 1;
                            @endphp

                            @foreach (range($currentYear, $startYear) as $y)
                                <li @click="selectedYear = '{{ $y }}'; selectedYearText = '{{ $y }}'; openYear = false"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    {{ $y }}
                                </li>
                            @endforeach
                        </ul>

                        {{-- input hidden สำหรับส่งค่า --}}
                        <input type="hidden" name="year" x-model="selectedYear">
                    </div>
                </div>

                <button type="submit" @click="isOpen = true"
                    class="w-[111px] h-[32px] bg-white-500 text-[#0012E1]
                rounded-lg border border-[#0012E1] mt-1 font-[Lato] text-[12.64px] hover:bg-[#0012E1] hover:text-white ">ค้นหา</button>


            </div>
        </form>
        <div class="text-sm text-gray-500 font-[Lato] text-[14px]"><br>สรุปรายงานการดำเนินงาน</div>

        <div class="bg-white shadow-md rounded-xl mt-6 p-4 w-[1077px]"><br>


            @php
                /* ถ้าพบข้อมูลสิ่งที่ดึงมาในตาราง countตัวนับจำนวน ไม่เป็น 0  */
            @endphp


                <div class="p=1 font-inter text-[24px] text-gray-800 ml-4 ">



                @if ($requests->count() > 0)

                    @if ($selectedMonth && $selectedYear)
                        {{-- แสดงเดือนและปีที่เลือก --}}
                        สรุปรายการ Work Request ประจำเดือน {{ $thaiMonths[$selectedMonth] }} ปี {{ $selectedYear }}
                    @elseif ($selectedMonth)
                        {{-- แสดงเดือนที่เลือกเท่านั้น --}}
                        สรุปรายการ Work Request ประจำเดือน {{ $thaiMonths[$selectedMonth] }}
                    @elseif ($selectedYear)
                        {{-- แสดงปีที่เลือกเท่านั้น --}}
                        สรุปรายการ Work Request ประจำปี {{ $selectedYear }}
                    @else
                        สรุปรายการ Work Request ทั้งหมด
                    @endif

                    </div>


                <div class="overflow-x-auto px-4 ">
                    @php
                        /*
                        overflow-x-auto ถ้าเนื้อหาภายในกล่อง (div) กว้างเกินกว่าขนาดกล่อง ก็จะมีแถบเลื่อนแนวนอนให้เลื่อนดูเนื้อหา
                        px-4 เพิ่มระยะห่าง 16px ที่ขอบซ้ายและขวาของกล่อง ทำให้เนื้อหาภายในไม่ชิดขอบเกินไป

                        */
                    @endphp

                    <table
                        class="mt-4 border border-gray-300 border-separate border-spacing-0 text-sm rounded-xl overflow-hidden  min-w-[982px] mr-1 ">
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



                        @php
                            $filteredRequests = $requests->filter(function ($tasks) {
                                return $tasks->where('task_status', 'C')->isNotEmpty();
                            });
                            $rowCount = $filteredRequests->count();

                            /* เพื่อกรองเฉพาะค่าที่ไม่ว่างใส่ตัวแปร rowCount*/

                        @endphp



                        <div class="text-sm text-gray-500 ml font-[Inter] text-[14px]"><br>จำนวนทั้งสิ้น
                            {{ $rowCount }}
                            รายการ
                        </div>
                        @php
                            /* แสดง  rowCount*/
                        @endphp


                        @php
                            /*
                            unique('work_request_id') ใช้เพื่อกรองรายการที่มี work_request_id ซ้ำกันใน $requests ออก
                            count() นับจำนวนรายการที่เหลือหลังจากกรอง
                            */
                        @endphp

                        <thead style="height: 43px" class=" text-[12px] font-bold">
                            <tr class="bg-[#0012E1] text-white text-xs">
                                <th class="p-2 border border-gray-300 w-[48px]">#</th>
                                <th class="p-2 border border-gray-300 w-[91px]">เลขที่</th>
                                <th class="p-2 border border-gray-300 w-[74px] whitespace-nowrap text-[11px]">
                                    วันที่ร้องขอ
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
                            @php
                                $rowCount = 0;
                            @endphp

                            @foreach ($requests as $workRequestId => $tasks)
                                @php
                                    // ดึง Task ที่สถานะเป็น 'C' (เสร็จสิ้น) เท่านั้น
                                    $completedTasks = $tasks->where('task_status', 'C');
                                    $firstTask = $tasks->first(); // ยังคงใช้ข้อมูล requester/dept จาก task แรก

                                @endphp

                                @if ($completedTasks->isNotEmpty())
                                    @php
                                        $rowCount++;
                                    @endphp



                                    <tr class="border text-xs font-[Inter] text-[12px]">
                                        <td class="p-2 border border-gray-300 text-center whitespace-nowrap">
                                            {{ $rowCount }}</td>
                                        <td class="p-2 border border-gray-300 text-center whitespace-nowrap">
                                            {{ $firstTask->work_request_id }}</td>
                                        <td class="p-2 border border-gray-300 text-center whitespace-nowrap">
                                            @php
                                                //whitespace-nowrap เพื่อป้องกันข้อความขึ้นบรรทัดใหม่
                                            @endphp
                                            {{ \Carbon\Carbon::parse($firstTask->work_create_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}
                                            {{-- แปลงวันที่ work_create_date ให้เป็นรูปแบบภาษาไทย และเปลี่ยนจาก ค.ศ. เป็น พ.ศ. --}}
                                            {{-- ถ้าในฐานข้อมูลเป็น 2025-01-10 จะแสดงเป็น 10 มี.ค. 2568 --}}
                                            {{-- แสดงวันที่ส่งงานในรูปแบบ พ.ศ. และเป็นภาษาไทย --}}
                                        </td>
                                        <td class="p-2 border border-gray-300 text-center whitespace-nowrap">
                                            {{ $firstTask->requester_name }}</td>

                                        <td class="p-2 border border-gray-300 text-center">
                                            {{ $firstTask->department_name }}</td>

                                        <td class="p-0 border border-gray-300 text-left align-top">
                                            <div class="divide-y divide-gray-300">
                                                @foreach ($completedTasks as $task)
                                                    <div class="p-2">{{ $task->task_name }}</div>
                                                @endforeach
                                            </div>
                                        </td>
                                        {{-- divide-y divide-gray-300: เป็น Tailwind class ที่สร้างเส้นคั่น (เหมือน border-b) เฉพาะระหว่างบรรทัด ยกเว้นบรรทัดสุดท้าย --}}
                                        {{-- p-0 บน <td> แล้วใส่ p-2 ใน div ภายในแทน เพื่อควบคุม padding แบบละเอียด --}}

                                        <td class="p-0 border border-gray-300 text-center align-top">
                                            <div class="divide-y divide-gray-300">
                                                @foreach ($completedTasks as $task)
                                                    <div class="p-2">
                                                        @if ($task->task_recipient_type == 'P')
                                                            บุคคล
                                                        @elseif ($task->task_recipient_type == 'D')
                                                            แผนก
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>


                                        <td class="p-0 border border-gray-300 text-center align-top">
                                            <div class="divide-y divide-gray-300">
                                                @foreach ($completedTasks as $task)
                                                    <div class="p-2">
                                                        {{ \Carbon\Carbon::parse($task->task_deadline)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>

                                        {{-- แปลงวันที่ task_submit_date ให้เป็นรูปแบบภาษาไทย และเปลี่ยนจาก ค.ศ. เป็น พ.ศ. --}}
                                        {{-- ถ้าในฐานข้อมูลเป็น 2025-04-07 จะแสดงเป็น 7 เม.ย. 2568 --}}
                                        {{-- แสดงวันที่ส่งงานในรูปแบบ พ.ศ. และเป็นภาษาไทย --}}


                                        <td class="p-0 border border-gray-300 text-center align-top">
                                            <div class="divide-y divide-gray-300">
                                                @foreach ($completedTasks as $task)
                                                    <div class="p-2">เสร็จสิ้น</div>
                                                @endforeach
                                            </div>
                                        </td>



                                        {{--
                                             สร้างตารางจับคู่สถานะงาน (ที่เป็นตัวอักษรย่อ) กับคำอธิบายแบบภาษาไทย
                                            เช่น ถ้า work_status เป็น 'R' จะแสดงว่า "รอดำเนินการ"
                                            ถ้าเจอสถานะที่ไม่มีในรายการ ก็แสดงค่าตัวเดิมไว้ (กันไว้กรณีมีค่าผิดปกติ)
                                        --}}
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                @else
                    @php
                        /* ถ้าไม่พบข้อมูลสิ่งที่ดึงมาในตาราง countตัวนับจำนวน เป็น 0  */
                    @endphp

                    <div class="p-4 text-center text-gray-500 font-[Lato] text-[32px] top-1/2 -translate-y-[6px]">
                        ไม่มีข้อมูล</div>

            @endif
        </div>
    </div>

    </div>
</body>

</html>
