<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        "sale_id",
        "issue_date",

        "serial",
        "correlative",

        "receiver_id",
        "receiver_name",
        "receiver_document_type",
        "receiver_document_number",
        "receiver_address",

        "type",
        "reason_code",
        "reason_description",
        "gross_weight",
        "load_unit_quantity",

        "delivery_ubigeo",
        "delivery_ubigeo_name",
        "delivery_address",

        "origin_ubigeo",
        "origin_ubigeo_name",
        "origin_address",

        "transport_mode",
        "vehicle_m1_l",
        "start_date",

        "transportist_id",
        "transportist_name",
        "transportist_document_number",
        "transportist_document_type",

        "driver_id",
        "driver_name",
        "driver_document_number",
        "driver_document_type",
        "driver_license_number",

        "is_active",
    ];

    public $casts = [
        "is_active" => "boolean",
        "vehicle_m1_l" => "boolean",
    ];

    protected $attributes = [
        "is_active" => true,
        "vehicle_m1_l" => false,
    ];

    public function details()
    {
        return $this->hasMany(GuideDetail::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function receiver()
    {
        return $this->belongsTo(Entity::class, "receiver_id");
    }

    public function transportist()
    {
        return $this->belongsTo(Entity::class, "transportist_id");
    }

    public function driver()
    {
        return $this->belongsTo(Entity::class, "driver_id");
    }

}
