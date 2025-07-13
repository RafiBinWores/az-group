<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cutting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'cutting',
    ];

    protected $casts = [
        'cutting' => 'array',
    ];

    // CUtting belong to order table
    public function order()
    {
        return $this->belongsTo(Order::class,);
    }
}
