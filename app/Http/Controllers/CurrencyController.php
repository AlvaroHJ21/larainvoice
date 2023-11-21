<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function index()
    {
        $data = Currency::all();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function update(Request $request, Currency $currency)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string",
            "short_name" => "string",
            "symbol" => "string",
            "is_active" => "boolean",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $erros = $validator->errors()->all();
            return response()->json(compact("ok", "errors"), 400);
        }

        $currency->update($request->all());

        $ok = true;
        $data = $currency;
        $message = "Moneda actualizada exitosamente";

        return response()->json(compact("ok", "data", "message"));
    }
}
