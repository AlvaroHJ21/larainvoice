<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "sale_id",
        "product_id",
        "code",
        "description",
        "description_add",
        "quantity",
        "price_base",

        "tax_id",
        "unit_id",

        "discount",
        "discount_type",
        "discount_percent",
    ];

    public $casts = [
        "price_base" => "float",
        "discount" => "float",
        "discount_percent" => "float",
    ];

    protected $attributes = [
        "discount" => 0,
        "discount_type" => 1,
        "discount_percent" => 0,
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
