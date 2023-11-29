<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $data = ExchangeRate::with("currency")
            ->orderBy("created_at", "desc")
            ->get();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "currency_id" => "required|integer",
            "rate" => "required|numeric",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        $exchange = ExchangeRate::create($request->all());
        $exchange->load("currency");

        $ok = true;
        $data = $exchange;
        $message = "Tipo de cambio creado correctamente";
        return response()->json(compact("ok", "data", "message"));
    }
}
