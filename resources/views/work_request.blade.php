<?php
// 1. เชื่อมต่อฐานข้อมูล MySQL
$pdo = new PDO("mysql:host=10.80.6.165;dbname=cluster8;charset=utf8", "cluster8", "k4PL1Wqq");

// 2. ดึงข้อมูลคำขอที่สร้างภายใน 5 วันที่ผ่านมา
$sql = "SELECT work_request_id, work_name, work_create_date, work_submit_date, work_create_by_user_id FROM work_request_order
        WHERE work_submit_date >= NOW() - INTERVAL 5 DAY";

$stmt = $pdo->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900" x-data="{ isOpen: false, detailModal: false, currentItem: null}">
    <!-- Sidebar - Fixed Position -->
    <div class="w-60 bg-[#ffffff] shadow-lg fixed h-full">
        <div class="p-4 border-b">
            <div class="flex items-center">
                <img src="{{ asset('public/wrslogo.png') }}" alt="WorkRequest System Logo" class="mr-3 h-13">
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="py-4">
            <a href="home" class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-home mr-3"></i>
                <span>หน้าหลัก</span>
            </a>
            <a href="workrequest" class="flex items-center px-4 py-3 bg-[#3b82f6] text-[#ffffff] rounded-lg mx-2 mb-2">
                <i class="fas fa-clipboard-list mr-3"></i>
                <span>สร้างใบสั่งงาน</span>
            </a>
            <a href="report" class="flex items-center px-4 py-3 text-[#374151] hover:bg-[#f3f4f6] rounded-lg mx-2 mb-2">
                <i class="fas fa-chart-line mr-3"></i>
                <span>รายงานการดำเนินงาน</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 ml-60">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#0012E1]">สร้างใบสั่งงาน</h1>
        </div>
        <main class="container mx-auto p-4">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold">รายการคำขอใน 5 วันที่ผ่านมา</h2>
                <div class="mt-4">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-2">ID</th>
                                <th class="border p-2">ชื่อคำขอ</th>
                                <th class="border p-2">วันที่สร้าง</th>
                                <th class="border p-2">วันที่เสร็จ</th>
                                <th class="border p-2">ผู้ใช้</th>
                            </tr>
                        </thead>
                        <tbody >
                            <?php foreach ($data as $row): ?>
                            <tr class="border">
                                <td class="p-2 text-center"><?= htmlspecialchars($row['work_request_id']) ?></td>
                                <td
                                    @click="detailModal = true; currentItem = {
                                        id: '<?= $row['work_request_id'] ?>',
                                        name: '<?= $row['work_name'] ?>',
                                        createDate: '<?= $row['work_create_date'] ?>',
                                        submitDate: '<?= $row['work_submit_date'] ?>',
                                        userId: '<?= $row['work_create_by_user_id'] ?>'
                                    }"class="p-2 cursor-pointer text-blue-600 hover:text-blue-800 hover:underline"
                                >
                                <?= htmlspecialchars($row['work_name']) ?></td>
                                <td class="p-2 text-center"><?= htmlspecialchars($row['work_create_date']) ?></td>
                                <td class="p-2 text-center"><?= htmlspecialchars($row['work_submit_date']) ?></td>
                                <td class="p-2 text-center"><?= htmlspecialchars($row['work_create_by_user_id']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- ปุ่มเพิ่มคำขอ -->
                <button @click="isOpen = true" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus"></i> สร้างคำขอใหม่
                </button>

            </div>
        </main>
    </div>

        <!-- Modal -->
<div x-show="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <!-- Modal Box -->
    <div class="bg-white p-4 rounded-xl shadow-xl w-full max-w-xl max-h-[80vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <div class="text-blue-600 font-semibold text-lg">รายละเอียดใบสั่งงาน</div>
            <div class="text-gray-500">#</div>
            <button @click="isOpen = false" class="text-gray-600 hover:text-black text-xl">
                <i class="fas fa-times-circle"></i>
            </button>
        </div>

            <!-- FORM -->
            <form action="{{ url('/work_request') }}" method="POST">
                @csrf

                <!-- ชื่อเรื่อง / วันที่ร้องขอ -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 mb-4">
    <!-- ชื่อเรื่อง -->
    <div class="flex items-center space-x-2">
        <label for="work_name" class="text-sm font-semibold text-gray-700 whitespace-nowrap">ชื่อเรื่อง :</label>
        <input type="text" name="work_name" id="work_name"
            class="border px-3 py-2 rounded w-full" placeholder="กรุณากรอกชื่อเรื่อง" required>
    </div>

    <!-- วันที่ร้องขอ -->
    <div class="flex items-center justify-end space-x-2">
        <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">วันที่ร้องขอ :</label>
        <span name="create_date" class="text-gray-900 font-medium">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
    </div>
</div>

<!-- ผู้ส่ง / แผนก -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- ผู้ส่ง -->
    <div class="flex items-center space-x-2">
        <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">ผู้ส่ง :</span>

        <span class="text-gray-900 font-semibold">


        </span>
    </div>

    <!-- แผนก -->
    <div class="flex items-center justify-end space-x-4">
        <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">แผนก <span class="text-red-500">*</span> :</label>
        <div class="flex items-center space-x-4">
            <label class="flex items-center space-x-1">
                <input type="radio" name="work_author_type" value="D" checked class="accent-blue-600">
                <span class="text-gray-700 text-sm">ระบุ</span>
            </label>
            <label class="flex items-center space-x-1">
                <input type="radio" name="work_author_type" value="P" class="accent-blue-600">
                <span class="text-gray-700 text-sm">ไม่ระบุ</span>
            </label>
        </div>
    </div>
</div>

                <hr class="mb-4">

                <!-- งานย่อย -->
                <div
                    x-data="{
                        tasks: [{ id: 1, name: '', description: '' }],
                        addTask() {
                            this.tasks.push({ id: this.tasks.length + 1, name: '', description: '' });
                        },
                        removeTask(index) {
                            this.tasks.splice(index, 1);
                        }
                    }"
                >

                    <!-- ปุ่มเพิ่ม -->
                    <div class="flex justify-end items-center mt-3">
                        <button type="button" @click="addTask" class="button-button5 bg-green-500 text-white px-4 py-1 rounded hover:bg-green-700 transition">
                            <i class="fas fa-plus"></i> เพิ่มรายการ
                        </button>
                    </div>

                    <!-- งานย่อย template -->
                    <template x-for="(task, index) in tasks" :key="task.id">
                        <div class="mt-4 border border-gray-300 rounded-lg p-4 space-y-3">

                            <!-- ชื่องาน วันที่ -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <input type="text" name="task_name[]" placeholder="ชื่องาน" class="w-full border rounded px-3 py-2" required>
                                <div class="flex items-center border rounded px-3 py-2 w-full space-x-2">
                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                <input type="date" name="task_deadline[]" class="flex-1 outline-none" required>
                                </div>
                            </div>

                            <!-- ผู้รับงาน (บุคคล / แผนก) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <!-- บุคคล -->
                                <label class="flex items-center border rounded px-3 py-2 space-x-2">
                                    <input type="radio" :name="'task_recipient_type_' + index" value="P" checked class="accent-blue-500">
                                    <span class="flex items-center w-full space-x-2">
                                        <input type="text" name="task_recipient_user_id[]" placeholder="บุคคล" class="flex-1 outline-none" required>
                                        <i class="fas fa-search text-gray-500"></i>
                                    </span>
                                </label>

                                <!-- แผนก -->
                                <label class="flex items-center border rounded px-3 py-2 space-x-2">
                                    <input type="radio" :name="'task_recipient_type_' + index" value="D" class="accent-blue-500">
                                    <span class="flex items-center w-full space-x-2">
                                        <input type="text" name="task_recipient_department_id[]" placeholder="แผนก" class="flex-1 outline-none" required>
                                        <i class="fas fa-search text-gray-500"></i>
                                    </span>
                                </label>
                            </div>



                            <!-- ปุ่มลบ -->
                            <div class="flex justify-end">
                                <button type="button" @click="removeTask(index)" class="text-red-500 hover:text-red-700 text-sm">
                                    <i class="fas fa-trash-alt"></i> ลบ
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <hr class="semibold my-6">


                <!-- ปุ่มส่ง -->
                <div class="flex justify-end space-x-4">
                    <button type="submit" class="px-6 py-2 text-blue border border-blue  rounded">ส่ง</button>
                    <button type="submit" name="save_draft" class="px-6 py-2 border border-black rounded">แบบร่าง</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal test-->
    <div x-show="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <!-- Modal Box -->
        <div class="bg-white p-4 rounded-xl shadow-xl w-full max-w-xl max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-lg font-semibold">รายละเอียดคำขอ</h2>
                <button @click="detailModal = false" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-4 space-y-2">
                <p><strong>รหัสคำขอ:</strong> <span x-text="currentItem?.id"></span></p>
                <p><strong>ชื่อคำขอ:</strong> <span x-text="currentItem?.name"></span></p>
                <p><strong>วันที่สร้าง:</strong> <span x-text="currentItem?.createDate"></span></p>
                <p><strong>วันที่เสร็จ:</strong> <span x-text="currentItem?.submitDate"></span></p>
                <p><strong>ผู้สร้างคำขอ:</strong> <span x-text="currentItem?.userId"></span></p>
            </div>
            <div class="flex justify-end mt-6">
                <button @click="detailModal = false" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 mr-2">ปิด</button>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">ดำเนินการ</button>
            </div>
        </div>
    </div>

        <!-- Modal -->
        <div x-show="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <!-- Modal Box -->
            <div class="bg-white p-4 rounded-xl shadow-xl w-full max-w-xl max-h-[80vh] overflow-y-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <div class="text-blue-600 font-semibold text-lg">รายละเอียดใบสั่งงาน</div>
                    <div class="text-gray-500">#</div>
                    <button @click="isOpen = false" class="text-gray-600 hover:text-black text-xl">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>

                    <!-- FORM -->
                    <form action="{{ url('/work_request') }}" method="POST">
                        @csrf

                        <!-- ชื่อเรื่อง / วันที่ร้องขอ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 mb-4">
            <!-- ชื่อเรื่อง -->
            <div class="flex items-center space-x-2">
                <label for="work_name" class="text-sm font-semibold text-gray-700 whitespace-nowrap">ชื่อเรื่อง :</label>
                <input type="text" name="work_name" id="work_name"
                    class="border px-3 py-2 rounded w-full" placeholder="กรุณากรอกชื่อเรื่อง" required>
            </div>

            <!-- วันที่ร้องขอ -->
            <div class="flex items-center justify-end space-x-2">
                <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">วันที่ร้องขอ :</label>
                <span name="create_date" class="text-gray-900 font-medium">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
            </div>
        </div>

        <!-- ผู้ส่ง / แผนก -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- ผู้ส่ง -->
            <div class="flex items-center space-x-2">
                <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">ผู้ส่ง :</span>

                <span class="text-gray-900 font-semibold">


                </span>
            </div>

            <!-- แผนก -->
            <div class="flex items-center justify-end space-x-4">
                <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">แผนก <span class="text-red-500">*</span> :</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-1">
                        <input type="radio" name="work_author_type" value="D" checked class="accent-blue-600">
                        <span class="text-gray-700 text-sm">ระบุ</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="radio" name="work_author_type" value="P" class="accent-blue-600">
                        <span class="text-gray-700 text-sm">ไม่ระบุ</span>
                    </label>
                </div>
            </div>
        </div>

                        <hr class="mb-4">

                        <!-- งานย่อย -->
                        <div
                            x-data="{
                                tasks: [{ id: 1, name: '', description: '' }],
                                addTask() {
                                    this.tasks.push({ id: this.tasks.length + 1, name: '', description: '' });
                                },
                                removeTask(index) {
                                    this.tasks.splice(index, 1);
                                }
                            }"
                        >

                            <!-- ปุ่มเพิ่ม -->
                            <div class="flex justify-end items-center mt-3">
                                <button type="button" @click="addTask" class="button-button5 bg-green-500 text-white px-4 py-1 rounded hover:bg-green-700 transition">
                                    <i class="fas fa-plus"></i> เพิ่มรายการ
                                </button>
                            </div>

                            <!-- งานย่อย template -->
                            <template x-for="(task, index) in tasks" :key="task.id">
                                <div class="mt-4 border border-gray-300 rounded-lg p-4 space-y-3">

                                    <!-- ชื่องาน วันที่ -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <input type="text" name="task_name[]" placeholder="ชื่องาน" class="w-full border rounded px-3 py-2" required>
                                        <div class="flex items-center border rounded px-3 py-2 w-full space-x-2">
                                        <i class="fas fa-calendar-alt text-blue-500"></i>
                                        <input type="date" name="task_deadline[]" class="flex-1 outline-none" required>
                                        </div>
                                    </div>

                                    <!-- ผู้รับงาน (บุคคล / แผนก) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <!-- บุคคล -->
                                        <label class="flex items-center border rounded px-3 py-2 space-x-2">
                                            <input type="radio" :name="'task_recipient_type_' + index" value="P" checked class="accent-blue-500">
                                            <span class="flex items-center w-full space-x-2">
                                                <input type="text" name="task_recipient_user_id[]" placeholder="บุคคล" class="flex-1 outline-none" required>
                                                <i class="fas fa-search text-gray-500"></i>
                                            </span>
                                        </label>

                                        <!-- แผนก -->
                                        <label class="flex items-center border rounded px-3 py-2 space-x-2">
                                            <input type="radio" :name="'task_recipient_type_' + index" value="D" class="accent-blue-500">
                                            <span class="flex items-center w-full space-x-2">
                                                <input type="text" name="task_recipient_department_id[]" placeholder="แผนก" class="flex-1 outline-none" required>
                                                <i class="fas fa-search text-gray-500"></i>
                                            </span>
                                        </label>
                                    </div>



                                    <!-- ปุ่มลบ -->
                                    <div class="flex justify-end">
                                        <button type="button" @click="removeTask(index)" class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-trash-alt"></i> ลบ
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <hr class="semibold my-6">


                        <!-- ปุ่มส่ง -->
                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="px-6 py-2 text-blue border border-blue  rounded">ส่ง</button>
                            <button type="submit" name="save_draft" class="px-6 py-2 border border-black rounded">แบบร่าง</button>
                        </div>
                    </form>
                </div>
            </div>
</body>
</html>


