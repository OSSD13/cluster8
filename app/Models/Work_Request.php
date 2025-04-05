<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work_Request extends Model
{
    //
    protected $fillable = [
        'wrq_id',
        'wrq_name',
        'wrq_create_date',
        'wrq_user_id'
    ];
}
