<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        "serial_id",
        "correlative",
        "emission_date",
        "due_date",
        "document_type_code",
        "operation_type_id",

        "sent_to_sunat",
        "accepted_by_sunat",
        "rejected_by_sunat",
        "is_active",

        "digest_value",
        "sale_id",
    ];

    protected $attributes = [
        "sent_to_sunat" => false,
        "accepted_by_sunat" => false,
        "rejected_by_sunat" => false,
        'is_active' => true,
    ];

    public $casts = [
        "sent_to_sunat" => "boolean",
        "accepted_by_sunat" => "boolean",
        "rejected_by_sunat" => "boolean",
        "is_active" => "boolean",
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function serial()
    {
        return $this->belongsTo(Serial::class);
    }
}
