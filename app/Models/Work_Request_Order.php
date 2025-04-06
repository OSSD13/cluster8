<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work_Request_Order extends Model
{
    //
    protected $fillable = [
        'work_request_id',
        'work_name',
        'work_create_date',
        'work_submit_date',
        'work_create_by_user_id',
        'work_author_type',
        'work_sub_task_id',
        'work_status',
        'work_create_by_department_id'
    ];
}
