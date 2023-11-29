<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "quotation_id",
        "product_id",
        "code",
        "description",
        "description_add",
        "quantity",
        "price_base",
        "price_taxed",
        "tax_id",
        "unit_id",
    ];
}
