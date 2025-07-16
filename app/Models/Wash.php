<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wash extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'wash',
        'garment_type',
        'date',
    ];

    protected $casts = [
        'wash' => 'array',
    ];

    // Wash belong to order table
    public function order()
    {
           return $this->belongsTo(Order::class,);
    }
}
