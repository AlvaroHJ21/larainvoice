<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'inventory_id',
        'quantity',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->via('inventory');
    }

    public function storehouse()
    {
        return $this->belongsTo(Storehouse::class)->via('inventory');
    }

    static public function movementsByInventory($inventory_id)
    {
        $data = Movement::with("inventory.product:id,name,code", "inventory.storehouse:id,name")
            ->where("inventory_id", $inventory_id)
            ->orderBy("created_at", "desc")
            ->get();
        return $data;
    }

    static public function movementsByProduct($product_id)
    {
        $data = Movement::with("inventory.product:id,name,code", "inventory.storehouse:id,name")
            ->whereHas(
                "inventory",
                function ($query) use ($product_id) {
                    $query->where("product_id", $product_id);
                }
            )
            ->orderBy("created_at", "desc")
            ->get();
        return $data;
    }

    static public function movementsByStorehouse($storehouse_id)
    {
        $data = Movement::with("inventory.product:id,name,code", "inventory.storehouse:id,name")
            ->whereHas(
                "inventory",
                function ($query) use ($storehouse_id) {
                    $query->where("storehouse_id", $storehouse_id);
                }
            )
            ->orderBy("created_at", "desc")
            ->get();
        return $data;
    }
}
