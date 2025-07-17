<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends Model
{
    Use SoftDeletes;

    protected $fillable = [
        'order_id',
        'cutting_id',
        'embroidery_print_id',
        'wash_id',
        'factory',
        'line',
        'garment_type',
        'input',
        'total_input',
        'output',
        'total_output',
        'date'
    ];

    // production has many relation with order 
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // production has many relation with cutting 
    public function cuttings()
    {
        return $this->hasMany(Cutting::class);
    }

    // production has many relation with embroider_prints 
    public function embroideryPrints()
    {
        return $this->hasMany(EmbroideryPrint::class);
    }

    // production has many relation with wash 
    public function washes()
    {
        return $this->hasMany(Wash::class);
    }
}
