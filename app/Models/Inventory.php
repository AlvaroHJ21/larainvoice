<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'storehouse_id',
        'total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storehouse()
    {
        return $this->belongsTo(Storehouse::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
