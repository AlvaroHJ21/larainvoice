<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        "number",
        "currency_id",
        "entity_id",
        "user_id",

        "discount",
        "discount_type",
        "discount_percent",

        "subtotal",
        "total_igv",
        "total_pay",

        "note",
        "observations",

        "is_active",

        "quotation_id",
        "purchase_order_number",
    ];

    public $casts = [
        "discount" => "float",
        "discount_percent" => "float",
        "subtotal" => "float",
        "total_igv" => "float",
        "total_pay" => "float",
        "is_active" => "boolean",
    ];

    protected $attributes = [
        "discount" => 0,
        "discount_type" => 1,
        "discount_percent" => 0,
        "is_active" => true,
        "quotation_id" => null,
        "purchase_order_number" => null,
    ];

    static function generateNextNumber()
    {
        $last = Sale::orderBy('created_at', 'desc')->first();
        if ($last) {
            $number = $last->number;
            $number++;
        } else {
            $number = 1;
        }
        return $number;
    }



    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function document()
    {
        return $this->hasOne(BillingDocument::class);
    }
}
