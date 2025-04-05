<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการแผนก</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
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
        .header button {
            background-color: #0012E1;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-bar input, .search-bar select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 48%;
        }
        .search-bar button {
            background-color: #0012E1;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
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
            <h1>จัดการแผนก</h1>
            <button>+ เพิ่ม</button>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="ค้นหาชื่อหรือรหัสพนักงาน">
            <select>
                <option>เลือกแผนก</option>
                <!-- Add more options here -->
            </select>
            <button>เพิ่มพนักงาน</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ</th>
                    <th>แผนก</th>
                    <th>อีเมล</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                <!-- Add table rows here -->
                <?php foreach ($users as $index => $user) { ?>
                    <tr class="align-middle">
                      <td>{{ $index+1 }}.</td>
                      <td>{{ $user->fname }}{{' '}}{{$user->lname}}</td>
                      <td>
                        <form>
                            <!--
                                Programmer
                                System Analyst
                                System Engineer
                                Tester
                                Project Manager
                                IT Support
                            -->
                            <select id="dropdown" name="options">
                                <option value="option1">IT Support</option>
                                <option value="option2">Project Manager</option>
                                <option value="option3">Tester</option>
                                <option value="option3">System Engineer</option>
                                <option value="option3">System Analyst</option>
                                <option value="option3">Programmer</option>
                            </select>
                        </form>
                      </td>
                      <td>{{ $user->email }}</td>

                    </tr>
                    <?php } ?>
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

