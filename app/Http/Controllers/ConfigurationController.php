<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Serial;
use App\Models\Storehouse;
use App\Models\Tax;
use App\Models\Unit;

class ConfigurationController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        $categories = Category::orderBy('created_at', 'desc')->get();
        $units = Unit::all();
        $taxes = Tax::all();
        $serials = Serial::orderBy('created_at', 'desc')->get();
        $storehouses = Storehouse::orderBy('created_at', 'desc')->get();

        return response()->json([
            "ok" => true,
            "data" => compact('currencies', 'categories', 'units', 'taxes', "serials", "storehouses")
        ]);
    }
}
