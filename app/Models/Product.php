<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        "name",
        "image",
        "category_id",
        "unit_id",
        "tax_id",

        "selling_price",
        "selling_price_currency_id",
        "buy_price",
        "buy_price_currency_id",

        "is_active",
    ];

    public $casts = [
        "selling_price" => "float",
        "buy_price" => "float",
        "is_active" => "boolean",
    ];

    protected $attributes = [
        "is_active" => true,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function selling_price_currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function buy_price_currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

}
