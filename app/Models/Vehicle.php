<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate',
        'brand',
        'model',
        'category_m1_l',
    ];

    public $casts = [
        'category_m1_l' => 'boolean',
    ];

    protected $attributes = [
        'category_m1_l' => false,
    ];
}
