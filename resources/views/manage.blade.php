<?php
// เชื่อมต่อฐานข้อมูล MySQL
$pdo = new PDO('mysql:host=10.80.6.165;dbname=cluster8;charset=utf8', 'cluster8', 'k4PL1Wqq');

// ลบค่า user_id ออกจาก session
unset($_SESSION['user_id']);

// $offset = isset($offset) ? (int) $offset : 0;
// $items_per_page = isset($items_per_page) ? (int) $items_per_page : 10;

// $userID = session('users')->user_id;
$user_role = session('users')->user_ro_id;

$sql = "SELECT user_id, CONCAT(user_fname,' ',user_lname) AS user_name, department_name
        FROM users
        JOIN departments ON user_dept_id = department_id
        WHERE user_ro_id != 0";

$stmt = $pdo->prepare($sql);

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <meta charset="UTF-8">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <!-- โปรไฟล์ผู้ใช้ -->
    <div style="padding: 0;  width: 15%; margin-left: 82%; ">
        <div id="profileButton" style="background-color: #1d4ed8; color: white; padding: 12px 16px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: background-color 0.3s;">
            <div style="display: flex; align-items: center;">
                <div style="width: 40px; height: 40px; background-color: white; color: #1d4ed8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                    <i class="fas fa-user" style="font-size: 18px;"></i>
                </div>
                <div>
                    <div style="line-height: 1.2; font-size: 12px;">
                        {{ session('users')->user_fname }}
                    </div>
                    <div style="line-height: 1.2; font-size: 12px;">

                    </div>
                </div>
            </div>
            <i class="fas fa-arrow-right" style="color: white; font-size: 14px;"></i>
        </div>
    </div>
    <title>จัดการแผนก</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&display=swap');

        body {
            background-color: #f4f4f9;
            margin: 0;
            margin-top: 2%;
            font-family: "Noto Sans Thai", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .container {
            width: 75%;
            min-height: 600px;
            margin: 20px auto;
            margin-top: 2%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            font-family: "Noto Sans Thai", sans-serif;
        }

        .container-bottom {
            width: 75%;
            height: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            font-family: "Noto Sans Thai", sans-serif;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: "Noto Sans Thai", sans-serif;
        }

        .header h1 {
            color: #0012E1;
            font-size: 30px;
            font-family: "Noto Sans Thai", sans-serif;
        }

        .tab-content h2 {
            font-size: 24px;
            font-family: "Noto Sans Thai", sans-serif;
        }

        .detail {
            color: gray;
            font-size: 14px;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .clickable .icon-blue {
            color: #0012E1;

        }

        .clickable {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 8px 16px;
            border: 2px solid #0012E1;
            border-radius: 8px;
            font-weight: bold;
            background-color: white;
            color: gray;
            cursor: pointer;
        }

        .clickable.active {
            background-color: black;
            color: white;
            border-color: black;
        }

        .clickable.active .icon-blue {
            color: white;
        }


        .section-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .submit {
            background-color: white;
            color: #0012E1;
            padding: 8px 16px;
            border: 2px solid #0012E1;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-family: "Noto Sans Thai", sans-serif;
        }

        .submit:hover {
            background-color: #0012E1;
            color: white;
        }

        .submit-delete {
            background-color: white;
            color: #E60000;
            padding: 8px 16px;
            border: 2px solid #E60000;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-family: "Noto Sans Thai", sans-serif;
        }

        .submit-delete:hover {
            background-color: #E60000;
            color: white;
        }

        tr {
            border-bottom: 1px solid #ccc;
            /* เส้นกั้นระหว่างแถว */
        }

        td {
            border: none;
            /* ไม่ให้มีเส้นระหว่างคอลัมน์ */
            padding: 8px;
        }

        th {
            background-color: #eeeeee;
            /* สีเทาอ่อน */
            color: #333;
            font-weight: bold;
            background-color: #f4f4f9;
        }

        td,
        th {
            padding: 14px;
            font-size: 14px;
        }

        .dropdown-sort {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            font-family: "Noto Sans Thai", sans-serif;
        }

        .dropdown-wrapper {
            font-family: "Noto Sans Thai", sans-serif;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            /* ระยะห่างภายใน */
            background-color: #fff;
            /* สีพื้นหลัง */
            /* ลบเส้นขอบและมุมโค้ง */
            border: none;
            border-radius: 0;
        }

        .dropdown-wrapper i.icon-sort {
            color: #0012E1;
            /* สีของไอคอน */
            margin-right: 8px;
            /* ระยะห่างระหว่างไอคอนกับ select */
        }

        .dropdown-wrapper select {
            border: none;
            /* เอาเส้นขอบของ select ออก */
            outline: none;
            /* เอาเส้นขอบเมื่อคลิกออก */
            font-size: 14px;
            /* ขนาดตัวอักษร */
            color: #7d7d7d;
            /* สีตัวอักษร */
            background: transparent;
            /* พื้นหลังโปร่งใส */
            cursor: pointer;
        }

        .icon-sort {
            color: #0012E1
        }

        .assign-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
        }

        .detail-left {
            flex: 1;
            font-size: 14px;
            color: #555;
        }

        .form-right {
            flex: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            /* จาก align-items: flex-end */
            gap: 10px;
        }

        .form-right select {
            width: 100%;
            max-width: 100%;
            /* เพิ่มอันนี้ */
            padding: 10px;
            padding-left: 40px;
            /* หรือมากกว่านี้ตามขนาดไอคอน */
            font-size: 14px;
            color: #7d7d7d;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            /* ป้องกัน padding ดันออกนอก */
            appearance: none;

        }

        .form-right input {
            width: 100%;
            max-width: 100%;
            /* เพิ่มอันนี้ */
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            /* ป้องกัน padding ดันออกนอก */
        }

        .input-icon-both {
            position: relative;
            width: 100%;
        }

        .input-icon-both i.icon-left {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #999;
            pointer-events: none;
        }

        .input-icon-both i.icon-right {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            color: #999;
            pointer-events: none;
        }

        .input-icon-both input {
            width: 100%;
            max-width: 100%;
            /* ป้องกันล้น */
            padding: 12px 36px 12px 36px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .search-button {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
            padding: 0;
            font-size: 16px;
        }

        @media screen and (max-width: 768px) {
            .assign-row {
                flex-direction: column;
                gap: 20px;
            }

            .form-right {
                width: 100%;
            }
        }

        .table-container {
            border: 1px solid rgba(128, 128, 128, 0.501);
            /* เส้นขอบรอบตาราง */
            border-radius: 12px;
            /* มุมโค้ง */
            padding: 10px;
            /* ระยะห่างภายใน */
            margin-top: 20px;
            /* ระยะห่างด้านบน */
            background-color: #fff;
            /* สีพื้นหลัง */
            overflow-y: auto;
            /* เปิดการเลื่อนในแนวตั้ง */
            max-height: 500px;
            /* กำหนดความสูงสูงสุด */
            font-family: "Noto Sans Thai", sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* รวมเส้นขอบ */
            background-color: white;
            /* border: 2px solid #000; เส้นขอบรอบตาราง */
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background-color: white;
            /* สีพื้นหลัง */
            color: black;
            /* สีตัวอักษร */
            font-size: 16px;
            /* ขนาดตัวอักษร */
            text-transform: uppercase;
            /* ตัวพิมพ์ใหญ่ทั้งหมด */
            padding: 12px;
            /* ระยะห่างภายใน */
            padding-left: 5%;
            /* ระยะห่างด้านซ้าย */
            text-align: left;
            /* จัดให้อยู่ตรงกลาง */
        }

        /* ปรับแต่งข้อความในข้อมูล */
        td {
            font-size: 14px;
            /* ขนาดตัวอักษร */
            color: #7d7c7c;
            /* สีตัวอักษร */
            padding: 12px;
            /* ระยะห่างภายใน */
            padding-left: 5%;
            /* ระยะห่างด้านซ้าย */
            text-align: left;
            /* จัดให้อยู่ตรงกลาง */
        }

        /* เพิ่มการจัดตำแหน่งและระยะห่างสำหรับรูปภาพในคอลัมน์ชื่อ */
        td img {
            margin-right: 8px;
            /* ระยะห่างระหว่างรูปภาพกับข้อความ */
            vertical-align: middle;
            /* จัดให้อยู่ตรงกลางแนวตั้ง */
        }

        #searchResults ul li {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        #searchResults ul li:hover {
            background-color: #f0f0f0;
        }

        /* เพิ่มการจัดตำแหน่งและระยะห่างสำหรับรูปภาพในคอลัมน์ชื่อ */
        td img {
            margin-right: 8px;
            /* ระยะห่างระหว่างรูปภาพกับข้อความ */
            vertical-align: middle;
            /* จัดให้อยู่ตรงกลางแนวตั้ง */
        }

        /* เพิ่ม cursor pointer ให้กับแถวในตาราง */
        tbody tr:hover {
            cursor: pointer;
            background-color: #acacac65;
            /* เพิ่มสีพื้นหลังเมื่อชี้ */

        }

        /* กล่องพื้นหลัง */
        .custom-swal-popup {
            height: 30%;
            border: 1px solid #28a745;
            background-color: #e6f9ed;
            /* เขียวอ่อนแบบในภาพ */
            border-radius: 8px;
            padding: 15px;
            /* ระยะห่างภายใน */
            display: flex;
            flex-direction: row;
            align-items: center;
            box-shadow: none;
            top: -50px;
            /* ขยับขึ้นไปด้านบน */
        }


        /* ไอคอนฝั่งซ้าย */
        .custom-swal-icon {
            font-size: 12px;
            /* ขนาดไอคอน */
            color: #28a745;
            /* สีเขียว */
            margin-right: 80%;
            align-self: flex-start;
        }

        /* ข้อความหลักและข้อความรอง */
        .custom-swal-text-container {
            display: flex;
            flex-direction: column;
            /* จัดข้อความหลักและข้อความรองในแนวตั้ง */
            justify-content: center;
        }

        /* ข้อความหลัก */
        .custom-swal-title {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
            margin-left: 20%;
            margin-bottom: 5%;
            /* ระยะห่างด้านล่าง */
            text-align: left;
        }

        /* ข้อความรอง */
        .custom-swal-text {
            font-size: 14px;
            color: #777;
            margin-top: 4px;
            margin-left: 20%;
            /* margin-bottom: 10%; */
            text-align: left;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">



</head>

<body>

    <div class="container">
        <div class="header">
            <div>
                <h1>จัดการแผนก</h1>
                <p class="detail">กำหนดหรือลบพนักงานออกจากแผนก</p>
            </div>
            <div class="button-group">
                <div class="clickable active" onclick="showTab('add', this)"><i class="fas fa-plus icon-blue"></i>
                    กำหนดแผนก</div>
                <div class="clickable" onclick="showTab('delete', this)"><i class="fas fa-minus icon-blue"></i> ลบ</div>
            </div>
        </div>
        <hr>

        <div id="add" class="tab-content">
            <form method="GET" action="{{ url('manage') }}">
                <div class="section-top-row">
                    <h2>กำหนดแผนกให้กับพนักงาน</h2>
                    <button type="button" id="assignDeptButton" class="submit mt-3">กำหนดแผนก</button>
                </div>
                <div class="assign-row">
                    <div class="detail-left">
                        กำหนดพนักงานเข้าแผนกโดยค้นหาจากชื่อหรือรหัสพนักงาน
                    </div>


                    <div class="form-right">
                        <div class="input-icon-both">
                            <i class="fas fa-user icon-left"></i>

                            {{-- <select class="js-example-tokenizer" id="userSelect" name="user_id" style="width: 100%">
                                <option value="">ค้นหาชื่อหรือรหัสพนักงาน</option>
                            </select> --}}
                            <div class="search-container">
                                <input type="text" id="searchInput" placeholder="ค้นหาชื่อหรือรหัสพนักงาน"
                                    class="search-input" style="font-family: 'Noto Sans Thai', sans-serif;">
                                <input type="hidden" id="userIdHidden">
                            </div>

                            <!-- เปลี่ยน i เป็น button -->
                            <button class="icon-right search-button" onclick="handleSearch(event)">
                                <i class="fas fa-search"></i>
                            </button>

                        </div>

                        <div class="input-icon-both">
                            <i class="fas fa-users icon-left"></i>
                            <select id="departmentSetSelect" name="department"
                                class="rounded-[8px] w-[199px] h-[46px] border border-gray-300 mt-1 font-[Lato] text-[14.22px] "
                                {{-- onchange="this.form.submit()"> ส่งค่าอัตโนมัติเมื่อเลือก --}} <option value="">เลือกแผนก</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->department_name }}"
                                        {{ $select_dept == $dept->department_name ? 'selected' : '' }}style="font-family: 'Noto Sans Thai', sans-serif;">
                                        {{ $dept->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down icon-right"></i>
                        </div>
            </form>
        </div>


    </div>

    </div>

    <div id="delete" class="tab-content" style="display: none;">
        <form method="GET" action="{{ url('manage') }}" onsubmit="return false;">

            <div class="section-top-row">
                <h2>ลบพนักงานออกจากแผนก</h2>
                <button type="button" class="submit-delete" id="deleteDeptButton"
                    style="font-family: 'Noto Sans Thai', sans-serif;">ลบพนักงาน</button>
            </div>

            <div class="assign-row">
                <div class="detail-left">
                    ลบพนักงานออกจากแผนกโดยค้นหาจากชื่อหรือรหัสพนักงาน
                </div>

                <div class="form-right">
                    <div class="input-icon-both">
                        <i class="fas fa-user icon-left"></i>
                        <input type="text" id="searchDelInput" placeholder="ค้นหาชื่อหรือรหัสพนักงาน"
                            class="search-input" style="font-family: 'Noto Sans Thai', sans-serif;">
                        <input type="hidden" id="userDelIdHidden">

                        <button type="button" class="icon-right search-button" onclick="handleDelSearch(event)">
                            <i class="fas fa-search"></i>
                        </button>
                        <!-- เปลี่ยน i เป็น button -->
                    </div>

                </div>
            </div>
        </form>
    </div>
    </div>

    <div class="container-bottom">
        <div class="section-top-row">
            <h2>รายชื่อพนักงาน</h2>
            <div class="dropdown-sort">
                <div class="dropdown-wrapper">
                    <i class="fas fa-filter icon-sort"></i>
                    <select id="sortByDepartment"
                        onchange="filterByDepartment()"style="font-family: 'Noto Sans Thai', sans-serif;">
                        <option value="">จัดเรียงโดย</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept->department_name) ?>">
                            <?= htmlspecialchars($dept->department_name) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <hr>

        <div class="table-container">
            <table class="min-w-full text-sm text-left bg-white rounded-lg overflow-hidden table-wrapper">
                <thead>
                    <tr>
                        <th class="text-left px-4 py-2">รหัสพนักงาน</th>
                        <th class="text-left px-4 py-2">ชื่อ</th>
                        <th class="text-left px-4 py-2">แผนก</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <?php
                    if (!empty($data)) {
                        foreach ($data as $row) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['user_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['department_name']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3">ไม่มีข้อมูล</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="logoutModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.4); justify-content: center; align-items: center; z-index: 50;">
            <div id="test" style="background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); width: 400px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                    <div style="font-size: 18px; font-weight: bold; color: #333;">ยืนยันการออกจากระบบ</div>
                    <button style="font-size: 20px; color: #555; background: none; border: none; cursor: pointer;" id="closeModal">&times;</button>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <p style="font-size: 16px; color: #666; margin-bottom: 20px;">คุณแน่ใจว่าต้องการออกจากระบบหรือไม่?</p>
                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <button style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 20px; cursor: pointer; font-family: 'Noto Sans Thai', sans-serif; id="id="confirmLogout">ยืนยัน</button>
                        <button style="background-color: white; color: #555; padding: 10px 20px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer; font-family: 'Noto Sans Thai', sans-serif;" id="cancelLogout">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="successAlert"
            style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); width: 300px; background-color: #e6f9ed; color: #28a745; border: 1px solid #28a745; border-radius: 8px; padding: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 1000;">
            <div style="display: flex; align-items: center;">
                <svg style="width: 24px; height: 24px; margin-right: 10px; fill: #28a745;"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <strong id="alertTitle" style="display: block; font-size: 16px;">ลบพนักงานสำเร็จ</strong>
                    <span id="alertMessage" style="font-size: 14px;">ดำเนินการลบพนักงานเสร็จสิ้น</span>
                </div>
            </div>
        </div>

    </div>


    <script>
        // ============================Logout Modal========================
        // เมื่อคลิกที่ปุ่มโปรไฟล์ผู้ใช้
        document.getElementById('profileButton').addEventListener('click', function () {
            // เปิดป๊อปอัพยืนยันการออกจากระบบ
            document.getElementById('logoutModal').style.display = 'flex';


        });

        document.getElementById('closeModal').addEventListener('click', function () {

            document.getElementById('logoutModal').style.display = 'none';
        });


        // เมื่อคลิกปุ่มยกเลิก
        document.getElementById('cancelLogout').addEventListener('click', function () {
            // ปิดป๊อปอัพ
            document.getElementById('logoutModal').style.display = 'none';

        });

        // เมื่อคลิกปุ่มยืนยัน
        document.getElementById('confirmLogout').addEventListener('click', function () {
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
        window.addEventListener('click', function (event) {
            if (event.target === document.getElementById('logoutModal')) {
                document.getElementById('logoutModal').style.backgroundColor = 'red';
            }
        });
        // ============================Logout Modal========================
        function showSuccessAlert(title, message) {
            const alertBox = document.getElementById('successAlert');
            const alertTitle = document.getElementById('alertTitle');
            const alertMessage = document.getElementById('alertMessage');

            // อัปเดตข้อความใน Alert
            alertTitle.textContent = title;
            alertMessage.textContent = message;

            // แสดง Alert
            alertBox.style.display = 'block';

            // ซ่อน Alert หลัง 15 วินาที
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 3000);
        }

        function showTab(tabId, clickedElement) {
            document.getElementById("add").style.display = "none";
            document.getElementById("delete").style.display = "none";
            document.getElementById(tabId).style.display = "block";

            document.querySelectorAll('.clickable').forEach(el => el.classList.remove('active'));
            clickedElement.classList.add('active');
        }

        $(document).ready(function() {
            // เมื่อกดปุ่ม "กำหนดแผนก"
            $('#assignDeptButton').on('click', function() {
                const userId = $('#userIdHidden').val(); // รับค่าจาก input hidden
                const departmentName = $('#departmentSetSelect').val(); // รับค่าจาก Dropdown แผนก

                console.log('User ID:', userId);
                console.log('Department Name:', departmentName);

                if (!userId || !departmentName) {
                    alert('กรุณาเลือกพนักงานและแผนก');
                    return;
                }

                // ส่งข้อมูลไปยังเซิร์ฟเวอร์ผ่าน AJAX
                $.ajax({
                    url: '{{ route('edit.dept') }}',
                    method: 'POST',
                    data: {
                        user_id: userId,
                        department_name: departmentName,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showSuccessAlert(response
                                .message, 'ดำเนินการกำหนดแผนกพนักงานเสร็จสิ้น'
                            ); // ส่งข้อความจาก response
                            setTimeout(() => {
                                location.reload(); // รีเฟรชหน้า
                            }, 3000); // 3000 มิลลิวินาที = 3 วินาที
                        } else {
                            alert(response.message); // แสดงข้อความข้อผิดพลาด
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาดในการกำหนดแผนก');
                    }
                });
            });
            // จัดการการเปลี่ยนแปลงใน Dropdown แผนก
            $('#departmentSetSelect').on('change', function() {
                const departmentName = $(this).val();
                console.log('แผนกที่เลือก:', departmentName);
                // คุณสามารถเพิ่มโค้ด AJAX เพื่อส่งข้อมูลไปยังเซิร์ฟเวอร์ได้ที่นี่
            });

        });

        $(document).ready(function() {

            $('#deleteDeptButton').on('click', function() {
                const userId = $('#userDelIdHidden').val(); // รับ user_id จาก Dropdown

                if (!userId) {
                    alert('กรุณาเลือกพนักงาน');
                    return;
                }

                // ส่งข้อมูลไปยังเซิร์ฟเวอร์ผ่าน AJAX
                $.ajax({
                    url: '{{ route('edit.dept') }}', // URL สำหรับส่งข้อมูล
                    method: 'POST',
                    data: {
                        user_id: userId,
                        department_name: '-',
                        _token: '{{ csrf_token() }}' // ส่ง CSRF Token เพื่อความปลอดภัย
                    },
                    success: function(response) {
                        if (response.success) {
                            showSuccessAlert(response
                                .message, 'ดำเนินการลบพนักงานเสร็จสิ้น'
                            ); // ส่งข้อความจาก response
                            setTimeout(() => {
                                location.reload(); // รีเฟรชหน้า
                            }, 3000); // 3000 มิลลิวินาที = 3 วินาที
                        } else {
                            alert(response.message); // แสดงข้อความข้อผิดพลาด
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาดในการกำหนดแผนก');
                    }
                });
            });

        });

        function handleSearch(event) {
            event.preventDefault(); // ป้องกันการรีเฟรชหน้าเมื่อกด Enter

            const searchValue = document.getElementById('searchInput').value;

            // ส่งคำค้นหาไปยังเซิร์ฟเวอร์ผ่าน AJAX
            fetch('manage/search-users', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        search: searchValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('employeeTableBody');
                    tableBody.innerHTML = ''; // ล้างข้อมูลเก่าในตาราง

                    if (data.length > 0) {
                        data.forEach(user => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td class="px-6 py-3">${user.user_id}</td>
                        <td class="px-6 py-3">${user.user_name}</td>
                        <td class="px-6 py-3">${user.department_name}</td>
                    `;
                            row.addEventListener('click', () => selectRow(user.user_id, user
                                .user_name)); // เพิ่ม Event Listener สำหรับการเลือกแถว
                            tableBody.appendChild(row);
                        });
                    } else {
                        const noDataRow = document.createElement('tr');
                        noDataRow.innerHTML = `<td colspan="3" style="text-align: center;">ไม่มีข้อมูล</td>`;
                        tableBody.appendChild(noDataRow);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการค้นหา');
                });
        }

        function selectRow(userId, userName) {
            // แสดงชื่อในช่องค้นหา
            document.getElementById('searchInput').value = userName;

            // เก็บ user_id ไว้ใน input hidden
            document.getElementById('userIdHidden').value = userId;

            console.log('Selected User ID:', userId); // ตรวจสอบค่า user_id
            console.log('Selected User Name:', userName); // ตรวจสอบค่า userName
        }

        function handleSearchResultSelect(userId, userName) {
            // ใส่ค่าชื่อที่เลือกไว้ในช่องค้นหา (แสดงให้ผู้ใช้เห็น)
            document.getElementById('searchInput').value = userName;

            // เก็บ user_id ไว้ใน input hidden
            document.getElementById('userIdHidden').value = userId;
            console.log('Selected User ID:', userId); // ตรวจสอบค่า userID
            console.log('Selected User Name:', userName); // ตรวจสอบค่า userName
        }
        // ===========================
        function handleDelSearch(event) {
            event.preventDefault(); // ป้องกันการรีเฟรชหน้าเมื่อกด Enter

            const searchDelValue = document.getElementById('searchDelInput').value;

            // ส่งคำค้นหาไปยังเซิร์ฟเวอร์ผ่าน AJAX
            fetch('manage/search-users', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        search: searchDelValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('employeeTableBody');
                    tableBody.innerHTML = ''; // ล้างข้อมูลเก่าในตาราง

                    if (data.length > 0) {
                        data.forEach(user => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td class="px-6 py-3">${user.user_id}</td>
                        <td class="px-6 py-3">${user.user_name}</td>
                        <td class="px-6 py-3">${user.department_name}</td>
                    `;
                            row.addEventListener('click', () => selectDelRow(user.user_id, user
                                .user_name)); // เพิ่ม Event Listener สำหรับการเลือกแถว
                            tableBody.appendChild(row);
                        });
                    } else {
                        const noDataRow = document.createElement('tr');
                        noDataRow.innerHTML = `<td colspan="3" style="text-align: center;">ไม่มีข้อมูล</td>`;
                        tableBody.appendChild(noDataRow);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการค้นหา');
                });
        }
        document.getElementById('searchDelInput').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // ป้องกันการรีเฟรชหน้า
                handleDelSearch(event); // เรียกฟังก์ชันค้นหา
            }
        });

        function selectDelRow(userId, userName) {
            // แสดงชื่อในช่องค้นหา
            document.getElementById('searchDelInput').value = userName;

            // เก็บ user_id ไว้ใน input hidden
            document.getElementById('userDelIdHidden').value = userId;

            console.log('Selected User ID:', userId); // ตรวจสอบค่า user_id
            console.log('Selected User Name:', userName); // ตรวจสอบค่า userName
        }

        function handleDelSearchResultSelect(userId, userName) {
            // ใส่ค่าชื่อที่เลือกไว้ในช่องค้นหา (แสดงให้ผู้ใช้เห็น)
            document.getElementById('searchDelInput').value = userName;

            // เก็บ user_id ไว้ใน input hidden
            document.getElementById('userDelIdHidden').value = userId;
            console.log('Selected User ID:', userId); // ตรวจสอบค่า userID
            console.log('Selected User Name:', userName); // ตรวจสอบค่า userName
        }

        function filterByDepartment() {
            const departmentName = document.getElementById('sortByDepartment').value;

            if (departmentName === "") {
                console.log('No department selected');
                alert('กรุณาเลือกแผนก');
                return; // หยุดการทำงานหากไม่มีการเลือกแผนก
            }

            console.log('Department Name Selected:', departmentName);

            // ส่งคำขอ AJAX ไปยังเซิร์ฟเวอร์
            fetch('manage/filter-by-department', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        department_name: departmentName
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // แปลง response เป็น JSON
                })
                .then(data => {
                    console.log('Data Received:', data);

                    const tableBody = document.getElementById('employeeTableBody');
                    tableBody.innerHTML = ''; // ล้างข้อมูลเก่าในตาราง

                    if (data.length > 0) {
                        data.forEach(user => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                    <td class="px-6 py-3">${user.user_id}</td>
                    <td class="px-6 py-3">${user.user_name}</td>
                    <td class="px-6 py-3">${user.department_name}</td>
                `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        const noDataRow = document.createElement('tr');
                        noDataRow.innerHTML = `<td colspan="3" style="text-align: center;">ไม่มีข้อมูล</td>`;
                        tableBody.appendChild(noDataRow);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการกรองข้อมูล');
                });
        }
    </script>

</body>

</html>