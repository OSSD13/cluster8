<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Work Request System - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#F4F5F9] flex items-center justify-center">
    <form action="{{ url('/login') }}" method="POST" class="bg-white p-8 rounded-2xl shadow border border-gray-300 w-[608px] h-[625px]">
        @csrf
        <div class="flex flex-col items-center">
            <!-- Logo -->
            <div class="mb-8 mt-2">
                <img src="public/asset/WRS_1.png" alt="Work Request System" class="w-[520px] h-[103px]" />
            </div>

            <!-- Title -->
            <h1 class="w-full text-left text-2xl font-bold mb-10">เข้าสู่ระบบ</h1>

            <!-- Form content -->
            <div class="w-[344px]">
                <div class="mb-6">
                    <label for="user_username" class="block text-sm mb-2">ชื่อผู้ใช้</label>
                    <input name="user_username" id="user_username" type="text" placeholder="กรอกชื่อผู้ใช้"
                        class="w-[330px] h-[46px] rounded-lg border border-gray-300 bg-[#F4F5F9] px-4 text-sm outline-none" />
                </div>

                <div class="mb-6 relative">
                    <label for="user_password" class="block text-sm mb-2">รหัสผ่าน</label>
                    <input name="user_password" id="user_password" type="password" placeholder="กรอกรหัสผ่าน"
                        class="w-[330px] h-[46px] rounded-lg border border-gray-300 bg-[#F4F5F9] px-4 text-sm pr-10 outline-none" />
                    <!-- Eye icon for password toggle -->
                    <div class="absolute inset-y-0 right-2 flex items-center pr-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.023.152-2.005.437-2.925M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c1.405 0 2.742.292 3.957.818M21.542 12c-1.274 4.057-5.065 7-9.542 7-1.405 0-2.742-.292-3.957-.818" />
                        </svg>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="bg-blue-700 hover:bg-blue-900 text-white font-bold w-[330px] h-[46px] rounded-lg text-sm">เข้าสู่ระบบ</button>
            </div>
        </div>
    </form>
</body>

</html>
