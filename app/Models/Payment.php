<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'date',
        'amount',
        'observations',
        'payment_method_id',
    ];

    protected $attributes = [
        'observations' => null,
    ];

    public $casts = [
        "amount" => "float",
    ];


    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
