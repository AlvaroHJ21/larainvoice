<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    use HasFactory;

    protected $fillable = [
        "serial",
        "correlative",
        "description",
        "document_type_code",
        "is_active",
    ];

    public $casts = [
        "is_active" => "boolean",
    ];

    protected $attributes = [
        "is_active" => true,
        "correlative" => 1,
    ];

    public function next_correlative()
    {
        $correlative = $this->correlative;
        $this->correlative = $correlative + 1;
        $this->save();
        return $correlative;
    }
}
