<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work_Request_Order extends Model
{
    //
    protected $table = 'work_request_order';
    public $timestamps = false;
    protected $primaryKey = 'work_request_id';

    protected $fillable = [
        'work_request_id',
        'work_name',
        'work_create_date',
        'work_submit_date',
        'work_create_by_user_id',
        'work_author_type',
        'work_status',
        'work_created_by_department_id',
        'work_confirm_date',
        'work_decline_date',
        'work_decline'
    ];
}
