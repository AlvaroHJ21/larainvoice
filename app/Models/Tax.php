<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        "name",
        "percentage",
        "type",
        "is_active",
    ];

    public $timestamps = false;
}
