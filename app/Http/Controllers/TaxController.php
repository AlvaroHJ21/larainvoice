<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    public function index()
    {
        $data = Tax::all();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function update(Request $request, Tax $tax)
    {
        $validator = Validator::make($request->all(), [
            "code" => "string",
            "name" => "string",
            "percentage" => "numeric",
            "type" => "string",
            "is_active" => "boolean",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $erros = $validator->errors()->all();
            return response()->json(compact("ok", "errors"), 400);
        }

        $tax->update($request->all());

        $ok = true;
        $data = $tax;
        $message = "Impuesto actualizado exitosamente";

        return response()->json(compact("ok", "data", "message"));
    }
}
