<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'quantity',
        'description',
        'product_id',
        'inventory_id',
        'unit_code',
        'unit_name',
        'product_code',
        'sunat_product_code',
    ];

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

}
