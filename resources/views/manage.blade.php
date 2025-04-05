{{-- <!DOCTYPE html>
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
 --}}

{{-- โค้ดทดสอบ Controller --}}
 {{-- <!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ค้นหาผู้ใช้</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }
        #results div {
            padding: 5px;
            border-bottom: 1px solid #ccc;
        }
        #results div:hover {
            background-color: #f0f0f0;
        }
        input {
            width: 300px;
            padding: 8px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <h2>ค้นหาผู้ใช้</h2>

    <input type="text" id="search" placeholder="พิมพ์ชื่อ / นามสกุล / ID..." autocomplete="off" />
    <div id="results"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const resultsDiv = document.getElementById('results');

            searchInput.addEventListener('input', function () {
                const query = this.value;

                if (query.length >= 2) {
                    fetch(`/manage?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(user => {
                                    const div = document.createElement('div');
                                    div.textContent = `ID: ${user.user_id}, ชื่อ: ${user.user_fname} ${user.user_lname}`;
                                    resultsDiv.appendChild(div);
                                });
                            } else {
                                resultsDiv.innerHTML = '<div>ไม่พบข้อมูล</div>';
                            }
                        })
                        .catch(error => {
                            console.error('เกิดข้อผิดพลาดในการดึงข้อมูล:', error);
                        });
                } else {
                    resultsDiv.innerHTML = '';
                }
            });
        });
    </script>

</body>
</html> --}}

