<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'document_type_id',
        'document_number',
        'address',
        'ubigeo',
        'phone',
        'email',
        'is_retention_agent',
        'discount_percentage',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => true,
        'is_retention_agent' => false,
    ];

    public function documentType()
    {
        return $this->belongsTo(EntityDocumentType::class);
    }
}
