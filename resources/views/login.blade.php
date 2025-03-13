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
            background-color: #f5f5f5;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 30px;
            text-align: center;
            width: 350px;
            box-shadow: 0 0 10px rgba(0, 0, 255, 0.5);
            border: 2px solid blue;
        }
        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            border-radius: 25px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 80%;
        }
        .input-group {
            text-align: left;
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-box {
            width: 100%;
            padding: 10px;
            border: 1px solid #000;
            border-radius: 5px;
            font-size: 14px;
        }
        .login-btn {
            background-color: blue;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .login-btn:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="logo">
        <img src="public/asset/wrslogo.png" alt="Work Request System">
    </div>
    <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" class="input-box" placeholder="Username">
    </div>
    <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" class="input-box" placeholder="Password">
    </div>
    <button class="login-btn">Login</button>
</div>

</body>
</html>