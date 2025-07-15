<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmbroideryPrint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'emb_or_print',
        'date',
    ];

    protected $casts = [
        'emb_or_print' => 'array',
    ];

    // Embroidery or print belong to order table
    public function order()
    {
        return $this->belongsTo(Order::class,);
    }
}
