<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        "ruc",
        "business_name",
        "trade_name",
        "fiscal_address",
        "ubigeo",
        "district",
        "province",
        "department",

        "phone",
        "email",
        "website",
        "logo",

        "secondary_user_username",
        "secondary_user_password",
        "client_id",
        "client_secret",
        "access_token",

        "in_production",
    ];

    protected $attributes = [
        'in_production' => false,
    ];

    public $casts = [
        "in_production" => "boolean",
    ];
}
