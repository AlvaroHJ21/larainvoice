<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "user_id",
        "is_active"
    ];

    protected $attributes = [
        "is_active" => true
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
