<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการแผนก</title>
</head>
<body>
<body>
    <div class="container">
    <style>
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            font-weight: normal;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0012E1;

        }
        /* กลุ่มปุ่มให้อยู่ติดกัน */
        .button-group {
            display: flex;
            gap: 10px; /* ปรับระยะห่างระหว่างปุ่ม */
        }
        .clickable {
            display: inline-block;
            background-color: white;
            color: gray;
            padding: 8px 12px;
            border-radius: 5px;
            border: 2px solid #0012E1;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s;
        }
        /* .clickable:hover {
            background-color: darkblue;
        } */

        /* ปุ่มที่ถูกเลือก */
        .clickable.active {
            border: 2px solid black;
            color: white;
            background-color: black; /* สีเขียว */
        }

        .tab-content {
            margin-top: 20px;
        }
        .detail {
            color: gray;
        }
        .submit {
            display: inline-block;
            background-color: white;
            color: #0012E1;
            padding: 8px 12px;
            border-radius: 5px;
            border: 2px solid #0012E1;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s;
        }

        .submit:hover {
            display: inline-block;
            background-color: #0012E1;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            border: 2px solid #0012E1;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s;
        }

        .search-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-bar input, .dropdown select{
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 48%;
        }
        .section-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f9;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-col"><h1 class="title">จัดการแผนก</h1><p class="detail">กำหนดหรือลบพนักงานออกจากแผนก</p></div>

            <div class="button-group">
                <div class="clickable active" onclick="showTab('add', this)">+ กำหนดแผนก</div>

                <div class="clickable" onclick="showTab('delete', this)">- ลบ</div>
            </div>

        </div>

        <div id="add" class="tab-content">
            <div class="section-top-row">
                <h2>กำหนดหรือลบพนักงานออกจากแผนก</h2>
                <div class="btn"><button class="submit">กำหนดแผนก</button></div>
            </div>
            <p class="detail">กำหนดหรือลบพนักงานออกจากแผนก</p>
            <div class="search-bar">
                <input type="text" placeholder="ค้นหาชื่อหรือรหัสพนักงาน">
            </div>
            <div class="dropdown">
                <select>
                    <option>เลือกแผนก</option>
                    <!-- Add more options here -->
                </select>

            </div>
        </div>

        <div id="delete" class="tab-content" style="display: none;">
            <p>คุณสามารถลบแผนกได้ที่นี่!</p>
        </div>

        <script>
            function showTab(tabId, clickedElement) {
                // ซ่อนทุก tab ก่อน
                document.getElementById("add").style.display = "none";
                document.getElementById("delete").style.display = "none";

                // แสดงเฉพาะ tab ที่ถูกเลือก
                document.getElementById(tabId).style.display = "block";

                // ลบ class 'active' ออกจากทุกปุ่ม
                const buttons = document.querySelectorAll('.clickable');
                buttons.forEach(btn => btn.classList.remove('active'));

                // ใส่ class 'active' ให้ปุ่มที่ถูกกด
                clickedElement.classList.add('active');
            }
        </script>

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
                <!-- Add table rows here -->
                <tr>
                    <td>25000001</td>
                    <td>กิตติพงษ์ สุวรรณโชติ</td>
                    <td>HR</td>
                    <td>kittipong.s@gmail.com</td>
                    <td>ชาย</td>
                </tr>
                <!-- Repeat for more rows -->
            </tbody>
        </table>
    </div>

</body>
</html>
