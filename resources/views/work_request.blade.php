<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#f3f4f6] flex min-h-screen">
    <!-- Sidebar - Fixed Position -->
    <div class="w-60 bg-[#ffffff] shadow-lg fixed h-full">
        <div class="p-4 border-b">
            <div class="flex items-center">
                <img src="{{ asset('public\wrslogo.png') }}" alt="WorkRequest System Logo" class="mr-3 h-13">
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

        <!-- User Profile -->
        <div class="absolute bottom-0 w-60 p-2">
            <div class="flex items-center bg-[#1e3a8a] text-[#ffffff] p-2 rounded-lg">
                <div class="relative">
                    <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-full w-10 h-10">
                </div>
                <div class="ml-2">
                    <div class="font-semibold">จิรายุท คนโก้</div>
                    <div class="text-xs">anita@commerce.com</div>
                </div>
                <div class="ml-auto">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
        </div>
    </div>

    <!--test -->
    @section('content')
    <form action=" {{url('/workrequest') }}" method="post">
    @csrf
    <div class="row mt-3">
        <div class="col-6">
            <label>works_request</label>
            <input type="text" name="works_requests_name" class="form-control">
        </div>
    </div>
    <button class="btn btn-primary" id="btn-add_works_requests" type="button"> + เพิ่ม </button>
    <div class="row mt-3" id="subs_works_list">
        <div class="col-6">
            <label>Sub Name <button type="button"
                class="btn btn-danger ml-2 mt-2 mb-2 btn-del-subs_works_list">ลบ</button></label>
            <input name="subs_works_name[]" type="text" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3">บันทึก</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <td>#</td>
                <td>Work Request Name</td>
                <td>Sub Work Name</td>
                <td>User Name</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($works_requests as $index => $works_requests) { ?>
                <tr class="align-middle">
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $works_requests->name }}</td>
                    <td>
                        @foreach ($subs_works as $index => $subs_works_req)
                            @if ($subs_works_req->works_requests_id == $works_requests->id)
                                <li>{{ $subs_works_req->name }}</li>
                            @endif
                        @endforeach
                    </td>

                    <td>
                        <!--
                        <?php $first = true; ?>
                        @foreach ($subs_works as $index => $subs_works_req)
                            @if ($subs_works_req->works_requests_id == $works_requests->id)
                                @foreach ($user as $index => $userItem)
                                    @if ($productItem->category_id == $category->id && $productItem->user_id == $userItem->id && $first)
                                        <li>{{ $userItem->name }}</li>
                                        <?php $first = false; ?>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        -->
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


        <!-- ยังไม่แสดง -->
        <!-- Main Content -->
        <div class="col-md-10">
            <h2 class="mt-4">สร้างใบสั่งงาน</h2>

            <div class="row mt-4">
                <!-- เสร็จสิ้น -->
                <div class="col-md-6">
                    <h4>เสร็จสิ้น</h4>
                    <div class="card card-status card-success">
                        <div class="card-body">
                            <h5>สมัครรับพัสดุ...</h5>
                            <p>สี่โลร้อย คนดี</p>
                            <span class="badge bg-dark">หมายเหตุ</span>
                        </div>
                    </div>
                </div>

                <!-- กำลังดำเนินการ -->
                <div class="col-md-6">
                    <h4>กำลังดำเนินการ</h4>
                    <div class="card card-status card-warning">
                        <div class="card-body">
                            <h5>XXXXXX</h5>
                            <p>XXXXXX</p>
                            <button class="btn btn-primary">กำลังดำเนินการ</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ประวัติ -->
            <div class="row mt-4">
                <div class="col-md-8">
                    <h4>ประวัติ</h4>
                    <div class="card card-status card-light">
                        <div class="card-body">
                            <h5>XXXXXXXXX</h5>
                            <p>XXXXXXXXX</p>
                            <button class="btn btn-success">เสร็จสิ้น</button>
                            <button class="btn btn-danger">ปฏิเสธ</button>
                        </div>
                    </div>
                </div>

                <!-- แบบร่าง -->
                <div class="col-md-4">
                    <h4>แบบร่าง</h4>
                    <div class="card card-status card-secondary">
                        <div class="card-body">
                            <h5>XXXXXXXXX</h5>
                            <button class="btn btn-light">แบบร่าง</button>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Main Content -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


@section('scripts')
<script>
       $(document).ready(function() {
            $('#btn-add_works_requests').on('click', function() {
                $('#subs_works_list').append(`
                    <div class="col-6">
                        <label>Name
                            <button type="button" class="btn btn-danger ml-3 mt-2 mb-2 btn-del-subs_works_list">ลบ</button>
                        </label>
                        <input name="subs_works_name[]" type="text" class="form-control" required>
                    </div>
                `);
            });

            $(document).on('click', '.btn-del-subs_works_list', function() {
                $(this).parent().parent().remove();
            });
        });
</script>
@endsection
