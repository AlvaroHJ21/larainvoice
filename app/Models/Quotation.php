<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
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
    ];

    public $casts = [
        "discount" => "float",
        "discount_percent" => "float",
        "subtotal" => "float",
        "total_igv" => "float",
        "total_pay" => "float",
    ];

    protected $attributes = [
        "discount" => 0,
        "discount_type" => 1,
        "discount_percent" => 0,
    ];

    static function generateNextNumber()
    {
        $last = Quotation::orderBy('created_at', 'desc')->first();
        if ($last) {
            $number = $last->number;
            $number++;
        } else {
            $number = 1;
        }
        return $number;
    }

    public function details()
    {
        return $this->hasMany(QuotationDetail::class);
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
}