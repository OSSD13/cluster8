<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace with actual authentication logic
    if ($username == 'admin' && $password == 'password') {
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System - Login</title>
    <style>
        body {

            font-family: Arial, sans-serif;


            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f8f8;
        }
        .login-container {
            width: 350px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 2px solid blue;
        }
        .login-container img {
            width: 100px;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-btn {
            background: blue;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn:hover {
            background: darkblue;
        }
        .error {
            color: red;
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

    <div class="login-container">
        <img src="YOUR_LOGO_URL_HERE" alt="Work Request System">
        <h2>Work Request System</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>


<div class="login-container">
    <div class="logo">
        <img src="public/asset/wrslogo.png" alt="Work Request System">
    </div>
    <div class="content">
        <div class="input-group">

            <label for="username">Username</label>
            <input type="text" id="username" class="input-box username-input" placeholder="Username">
            <label for="password">Password</label>
            <input type="password" id="password" class="input-box" placeholder="Password">
        </div>
    </div>
    <button class="login-btn">Login</button>
</div>

</body>
</html>
