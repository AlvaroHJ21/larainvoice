<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Serial;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        $categories = Category::all();
        $units = Unit::all();
        $taxes = Tax::all();
        $serials = Serial::with('document_type')->get();

        return response()->json([
            "ok" => true,
            "data" => compact('currencies', 'categories', 'units', 'taxes', "serials")
        ]);
    }
}
