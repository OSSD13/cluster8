<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการแผนก</title>
    <style>
        body {
            background-color: #f4f4f9;
            margin: 0;
            font-family: sans-serif;
        }
        .container {
            width: 75%;
            min-height: 600px;
            margin: 20px auto;
            margin-top: 5%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }
        .container-bottom {
            width: 75%;
            height: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #0012E1;
            font-size: 30px;
        }

        .tab-content h2{
            font-size: 24px;
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

        .clickable .icon-blue{
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
        }

        .submit:hover {
            background-color: #0012E1;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 14px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f4f4f9;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .dropdown-sort select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 160px;
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
            align-items: stretch; /* จาก align-items: flex-end */
            gap: 10px;
        }

        .form-right select {
            width: 100%;
            max-width: 100%; /* เพิ่มอันนี้ */
            padding: 10px;
            padding-left: 40px; /* หรือมากกว่านี้ตามขนาดไอคอน */
            font-size: 14px;
            color: #7d7d7d;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box; /* ป้องกัน padding ดันออกนอก */
            appearance: none;

        }

        .form-right input {
            width: 100%;
            max-width: 100%; /* เพิ่มอันนี้ */
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box; /* ป้องกัน padding ดันออกนอก */
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
            max-width: 100%;  /* ป้องกันล้น */
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
            <div class="clickable active" onclick="showTab('add', this)"><i class="fas fa-plus icon-blue"></i> กำหนดแผนก</div>
            <div class="clickable" onclick="showTab('delete', this)"><i class="fas fa-minus icon-blue"></i> ลบ</div>
        </div>
    </div>
    <hr>

    <div id="add" class="tab-content">
        <div class="section-top-row">
            <h2>กำหนดแผนกให้กับพนักงาน</h2>
            <button class="submit">กำหนดแผนก</button>
        </div>

        <div class="assign-row">
            <div class="detail-left">
                กำหนดพนักงานเข้าแผนกโดยค้นหาจากชื่อหรือรหัสพนักงาน
            </div>

            <div class="form-right">
                <div class="input-icon-both">
                    <i class="fas fa-user icon-left"></i>
                    <input type="text" id="searchInput" placeholder="ค้นหาชื่อหรือรหัสพนักงาน">

                    <!-- เปลี่ยน i เป็น button -->
                    <button class="icon-right search-button" onclick="handleSearch()">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>


                <div class="input-icon-both">
                    <i class="fas fa-users icon-left"></i>
                    <select>
                      <option>เลือกแผนก</option>
                      <option>HR</option>
                      <option>IT</option>
                      <option>MKT</option>
                    </select>
                    <i class="fas fa-chevron-down icon-right"></i>
                  </div>

            </div>
          </div>

    </div>

    <div id="delete" class="tab-content" style="display: none;">
        <p>คุณสามารถลบแผนกได้ที่นี่</p>
    </div>
</div>

<div class="container-bottom">
    <div class="section-top-row">
        <h2>รายชื่อพนักงาน</h2>
        <div class="dropdown-sort">
            <select>
                <option>จัดเรียงโดย</option>
                <option>ชื่อ</option>
                <option>แผนก</option>
                <option>เพศ</option>
            </select>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th>รหัสพนักงาน</th>
            <th>ชื่อ</th>
            <th>แผนก</th>
            <th>อีเมล</th>
            <th>เพศ</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>25000001</td>
            <td>กิตติพงษ์ สุวรรณโชติ</td>
            <td>HR</td>
            <td>kittipong.s@gmail.com</td>
            <td>ชาย</td>
        </tr>
        <tr>
            <td>25000002</td>
            <td>วรพล มณี</td>
            <td>IT</td>
            <td>woraphon.s@gmail.com</td>
            <td>ชาย</td>
        </tr>
        <tr>
            <td>25000003</td>
            <td>สุทธชา ศักดิ์ไกร</td>
            <td>ACC</td>
            <td>sutchada.p@gmail.com</td>
            <td>ชาย</td>
        </tr>
        <!-- เพิ่มแถวอื่น ๆ ได้ตามต้องการ -->
        </tbody>
    </table>
</div>

<script>
    function showTab(tabId, clickedElement) {
        document.getElementById("add").style.display = "none";
        document.getElementById("delete").style.display = "none";
        document.getElementById(tabId).style.display = "block";

        document.querySelectorAll('.clickable').forEach(el => el.classList.remove('active'));
        clickedElement.classList.add('active');
    }
    function handleSearch() { //ไปใส่โค้ดค้นหามานะตรงนี้
        const input = document.getElementById('searchInput').value;
        console.log("ค้นหา:", input);
        // ตรงนี้สามารถเอาไปใช้กรองรายชื่อ หรือส่งไป backend ก็ได้
        alert("กำลังค้นหา: " + input);
    }
</script>

</body>
</html>
