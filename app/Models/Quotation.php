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
        "total_pay",
    ];
}
