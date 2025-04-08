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
        .submit-delete{
            background-color: white;
            color: #E60000;
            padding: 8px 16px;
            border: 2px solid #E60000;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-delete:hover {
            background-color: #E60000;
            color: white;
        }

        tr {
            border-bottom: 1px solid #ccc; /* เส้นกั้นระหว่างแถว */
        }

        td {
            border: none; /* ไม่ให้มีเส้นระหว่างคอลัมน์ */
            padding: 8px;
        }

        th {
            background-color: #eeeeee; /* สีเทาอ่อน */
            color: #333;
            font-weight: bold;
            background-color: #f4f4f9;
        }

        td, th {
            padding: 14px;
            font-size: 14px;
        }

        .dropdown-sort {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .dropdown-wrapper {
            display: flex;
            align-items: center;
            padding: 5px 10px; /* ระยะห่างภายใน */
            background-color: #fff; /* สีพื้นหลัง */
            /* ลบเส้นขอบและมุมโค้ง */
            border: none;
            border-radius: 0;
        }

        .dropdown-wrapper i.icon-sort {
            color: #0012E1; /* สีของไอคอน */
            margin-right: 8px; /* ระยะห่างระหว่างไอคอนกับ select */
        }

        .dropdown-wrapper select {
            border: none; /* เอาเส้นขอบของ select ออก */
            outline: none; /* เอาเส้นขอบเมื่อคลิกออก */
            font-size: 14px; /* ขนาดตัวอักษร */
            color: #7d7d7d; /* สีตัวอักษร */
            background: transparent; /* พื้นหลังโปร่งใส */
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
        .table-container {
            border: 1px solid rgba(128, 128, 128, 0.501); /* เส้นขอบรอบตาราง */
            border-radius: 12px; /* มุมโค้ง */
            padding: 10px; /* ระยะห่างภายใน */
            margin-top: 20px; /* ระยะห่างด้านบน */
            background-color: #fff; /* สีพื้นหลัง */
            overflow-y: auto; /* เปิดการเลื่อนในแนวตั้ง */
            max-height: 500px; /* กำหนดความสูงสูงสุด */
        }
        table {
            width: 100%;
            border-collapse: collapse; /* รวมเส้นขอบ */
            background-color: white;
            /* border: 2px solid #000; เส้นขอบรอบตาราง */
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background-color: white; /* สีพื้นหลัง */
            color: black; /* สีตัวอักษร */
            font-size: 16px; /* ขนาดตัวอักษร */
            text-transform: uppercase; /* ตัวพิมพ์ใหญ่ทั้งหมด */
            padding: 12px; /* ระยะห่างภายใน */
            padding-left: 5%; /* ระยะห่างด้านซ้าย */
            text-align: left; /* จัดให้อยู่ตรงกลาง */
        }

        /* ปรับแต่งข้อความในข้อมูล */
        td {
            font-size: 14px; /* ขนาดตัวอักษร */
            color: #7d7c7c; /* สีตัวอักษร */
            padding: 12px; /* ระยะห่างภายใน */
            padding-left: 5%; /* ระยะห่างด้านซ้าย */
            text-align: left; /* จัดให้อยู่ตรงกลาง */
        }

        /* เพิ่มการจัดตำแหน่งและระยะห่างสำหรับรูปภาพในคอลัมน์ชื่อ */
        td img {
            margin-right: 8px; /* ระยะห่างระหว่างรูปภาพกับข้อความ */
            vertical-align: middle; /* จัดให้อยู่ตรงกลางแนวตั้ง */
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
        <div class="section-top-row">
            <h2>ลบพนักงานออกจากแผนก</h2>
            <button class="submit-delete">ลบพนักงาน</button>
        </div>

        <div class="assign-row">
            <div class="detail-left">
                ลบพนักงานออกจากแผนกโดยค้นหาจากชื่อหรือรหัสพนักงาน
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



            </div>
          </div>
    </div>
</div>

<div class="container-bottom">
    <div class="section-top-row">
        <h2>รายชื่อพนักงาน</h2>
        <div class="dropdown-sort">
            <div class="dropdown-wrapper">
                <i class="fas fa-filter icon-sort"></i>
                <select>
                    <option>จัดเรียงโดย</option>
                    <option>ชื่อ</option>
                    <option>แผนก</option>
                    <option>เพศ</option>
                </select>
            </div>
        </div>
    </div>
    <hr>

    <div class="overflow-x-auto mt-4 table-container">
        <table class="min-w-full text-sm text-left bg-white rounded-lg overflow-hidden table-wrapper">
            <thead>
                <tr>
                    <th class="text-left px-4 py-2">รหัสพนักงาน</th>
                    <th class="text-left px-4 py-2">ชื่อ</th>
                    <th class="text-left px-4 py-2">แผนก</th>
                </tr>
            </thead>
              <tbody>
            <tr>
              <td class="px-6 py-3">25020003</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                สุชาดา พักกิจไทย
              </td>
              <td class="px-6 py-3">ACC</td>
            </tr>
            <tr>
              <td class="px-6 py-3">25030004</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                ปรเมศว์ วิริยะกุล
              </td>
              <td class="px-6 py-3">MKT</td>
            </tr>
            <tr>
              <td class="px-6 py-3">25050005</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                พิชญณ์ ธรรมธงศัย
              </td>
              <td class="px-6 py-3">CS</td>
            </tr>
            <tr>
              <td class="px-6 py-3">25990006</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                ชัยวัฒน์ โคตรสมบูรณ์
              </td>
              <td class="px-6 py-3">-</td>
            </tr>
            <tr>
              <td class="px-6 py-3">25030007</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                ภูริชิต วัฒนานนท์
              </td>
              <td class="px-6 py-3">MKT</td>
            </tr>
            <tr>
              <td class="px-6 py-3">25010008</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                ธันยวรรณ โพธิ์แก้ว
              </td>
              <td class="px-6 py-3">IT</td>
            </tr>
            <tr>
              <td class="px-6 py-3">25010009</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;"  />
                วศิน ทองพูล
              </td>
              <td class="px-6 py-3">IT</td>
            </tr>
            <tr>
              <td class="px-6 py-3">25010010</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;"  />
                พิศา ธรรมสนุกร
              </td>
              <td class="px-6 py-3">IT</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-3">25010011</td>
              <td class="px-6 py-3 flex items-center gap-2">
                <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                น้ำตาล อู๋
              </td>
              <td class="px-6 py-3">IT</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">25010011</td>
                <td class="px-6 py-3 flex items-center gap-2">
                  <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                  น้ำตาล อู๋
                </td>
                <td class="px-6 py-3">IT</td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">25010011</td>
                <td class="px-6 py-3 flex items-center gap-2">
                  <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                  น้ำตาล อู๋
                </td>
                <td class="px-6 py-3">IT</td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">25010011</td>
                <td class="px-6 py-3 flex items-center gap-2">
                  <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                  น้ำตาล อู๋
                </td>
                <td class="px-6 py-3">IT</td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">25010011</td>
                <td class="px-6 py-3 flex items-center gap-2">
                  <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                  น้ำตาล อู๋
                </td>
                <td class="px-6 py-3">IT</td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">25010011</td>
                <td class="px-6 py-3 flex items-center gap-2">
                  <img src="https://www.svgrepo.com/show/452030/avatar-default.svg" class="w-6 h-6 rounded-full" style="width: 24px; height: 24px;" />
                  น้ำตาล อู๋
                </td>
                <td class="px-6 py-3">IT</td>
              </tr>
          </tbody>
        </table>
      </div>

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
