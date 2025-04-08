<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Work Request System - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#F4F5F9] flex items-center justify-center">
    <form action="{{ url('/login') }}" method="POST" class="bg-white p-8 rounded-2xl shadow border border-gray-300 w-[590px] h-[625px]" style="border-radius: 30px"> 
        @csrf
        <div class="flex flex-col items-center">
            <!-- Logo -->
            <div class="mb-8 mt-2">
                <img src="public/asset/WRS_1.png" alt="Work Request System" class="w-[520px] h-[103px]" />
            </div>

            
            <!-- Form content -->
            <div class="w-[344px]" >
                <div class="mb-6">
                    <h1 class="w-full text-left text-2xl font-bold mb-10">เข้าสู่ระบบ</h1>
                    <label for="user_username" class="block text-sm mb-2">ชื่อผู้ใช้</label>
                    <input name="user_username" id="user_username" type="text" placeholder="กรอกชื่อผู้ใช้"
                        class="w-[330px] h-[46px] rounded-lg border border-gray-300 bg-[#F4F5F9] px-4 text-sm outline-none" />
                </div>

                <div class="mb-6 relative ">
                    <label for="user_password" class="block text-sm mb-2">รหัสผ่าน</label>
                    <input name="user_password" id="user_password" type="password" placeholder="กรอกรหัสผ่าน"
                      class="w-[330px] h-[46px] rounded-lg border border-gray-300 bg-[#F4F5F9] px-4 text-sm pr-10 outline-none" />
                    <div id="togglePassword" class="absolute inset-y-7 right-2 flex items-center pr-4 cursor-pointer h-[46px] ">
                      <!-- Show icon (eye open) -->
                      <i id="eyeOpen" class="fa-regular fa-eye hidden text-gray-500 pr-[1px] "></i>
            
                      <!-- Hide icon (eye slash) -->
                      <i id="eyeClosed" class="fa-regular fa-eye-slash text-gray-500 "></i>
                    </div>
                  </div>
                  <!-- Login Button -->
                <button type="submit"
                class="mt-6 bg-blue-700 hover:bg-blue-900 text-white font-bold w-[330px] h-[46px] rounded-lg text-sm ">
                เข้าสู่ระบบ
            </button>
                </div>

               
            </div>
        </div>
    </form>
</body>

</html>

<script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("user_password");
    const eyeOpen = document.getElementById("eyeOpen");
    const eyeClosed = document.getElementById("eyeClosed");
  
    togglePassword.addEventListener("click", () => {
      const isHidden = passwordInput.type === "password";
      passwordInput.type = isHidden ? "text" : "password";
      eyeOpen.classList.toggle("hidden", !isHidden);
      eyeClosed.classList.toggle("hidden", isHidden);
    });
  </script>
