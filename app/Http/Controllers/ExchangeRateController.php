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

    public function todaysChange(Request $request)
    {
        //obtenemos el proveedor desde los parametros de la url
        $provider = $request->get("provider");

        // 1: EMPRESA
        // 2: SUNAT
        if (!$provider) {
            $provider = 1;
        }

        $exchange = ExchangeRate::with("currency")
            ->orderBy("created_at", "desc")
            ->where("provider", $provider)
            ->first();

        //validar si la fecha de hoy es igual a la fecha de creacion del ultimo registro
        if (!$exchange || $exchange->created_at->format("Y-m-d") != date("Y-m-d")) {
            $errors = ["No se ha registrado el tipo de cambio de hoy"];
            $ok = false;
            return response()->json(compact("ok", "errors"));
        }

        $ok = true;
        $data = $exchange;
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
