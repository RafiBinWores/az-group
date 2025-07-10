<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable =[
        'user_id',
        'buyer_name',
        'style_no',
        'order_qty'
    ];

    protected $dates = ['deleted_at'];
}
