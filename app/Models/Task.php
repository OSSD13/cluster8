<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $table = 'task';
    protected $fillable = [
        'task_id',
        'task_name',
        'task_deadline',
        'task_status',
        'task_recipient_user_id',
        'task_recipient_department_id',
        'task_notation',
        'task_recipient_type',
        'task_submit_date',
        'task_work_request_id'
    ];
}
