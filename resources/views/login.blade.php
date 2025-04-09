
@extends('layouts.default')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System - Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #F4F5F9;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 20px;
            height: 625px;
            width: 608px;
            border: 2px solid #cecbcb;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-bottom: 30px;
            margin-top: 20px;
        }

        .logo img {
            width: 520px;
            height: 103px;
        }

        .login-title {
            width: 100%;
            text-align: left;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            width: 100%;

        }

        .input-group label {
            font-weight: bold;
            text-align: left;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .input-box {
            width: 330px;
            height: 46px;
            padding: 10px;
            border: 1px solid #dbdcde;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            margin-bottom: 25px;
            background-color: #F4F5F9;
        }

        .login-btn {
            background-color: #0044FF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 330px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 25px;
            height: 46px;
        }

        .login-btn:hover {
            background-color: #0029A3;

        }

        .content {
            width: 344px;
            height: 373px;
        }
    </style>
</head>

<body>
    <form action="{{ url('/login') }}" method = 'post'>
        @csrf
        <div class="login-container">
            <div class="logo">
                <img src="public\asset\WRS_1.png" alt="Work Request System">
            </div>
            <div>
                <h1 class="login-title">เข้าสู่ระบบ</h1>
                <div class="content">
                    <div class="input-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input name="user_username" type="text" id="user_username" class="input-box" placeholder="กรอกชื่อผู้ใช้">

                        <label for="password">รหัสผ่าน</label>
                        <input name="user_password" type="password" id="user_password" class="input-box" placeholder="กรอกรหัสผ่าน">
                    </div>

                    <button class="login-btn">เข้าสู่ระบบ</button>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
