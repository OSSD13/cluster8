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
            padding-left: 70px;
            margin-bottom: 15px;
            justify-content: center;

        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-box {
            align-items: center;
            width: 75%;
            padding-top: 5px;
            padding-bottom: 5px;
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
            width: 40%;
            font-size: 16px;
            margin-top: 30px
        }
        .login-btn:hover {
            background-color: darkblue;
        }
        .username-input{
            margin-bottom: 60px
        }
    </style>
</head>
<body>
    <form action="{{ url('/login') }}" method="post">
        @csrf
        <div class="login-container">
            <div class="logo">
                <img src="public/wrslogo.png" alt="Work Request System">
            </div>
            <div class="content">
                <div class="input-group">

                <label for="username">Username</label>
                <input name="username" type="text" id="username" class="input-box username-input" placeholder="Username">
                <label for="password">Password</label>
                <input name="password" type="password" id="password" class="input-box" placeholder="Password">
                </div>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </div>
    </form>
</body>
</html>