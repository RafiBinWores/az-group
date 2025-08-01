<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'buyer_name',
        'style_no',
        'order_qty',
        'color_qty'
    ];

    protected $casts = [
        'color_qty' => 'array',
    ];

    protected $dates = ['deleted_at'];

    // Order Has many cutting report
    public function cuttings()
    {
        return $this->hasMany(Cutting::class);
    }

    public function garmentTypes()
    {
        return $this->belongsToMany(GarmentType::class);
    }
}
