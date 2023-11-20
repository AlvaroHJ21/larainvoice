<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityDocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'abbreviated',
        'is_active',
    ];
}
