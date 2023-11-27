<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovementController extends Controller
{
    public function index()
    {
        $data = Movement::with("inventory.product", "inventory.storehouse")
            ->orderBy("created_at", "desc")
            ->get();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function byInventory($inventory_id)
    {
        $data = Movement::movementsByInventory($inventory_id);
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function byProduct($product_id)
    {
        $data = Movement::movementsByProduct($product_id);
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "type" => "required|numeric|in:0,1",
            "inventory_id" => "required|numeric",
            "quantity" => "required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        // crear el movimiento
        $data = Movement::create($request->all());
        $ok = true;
        return response()->json(compact("ok", "data"));
    }
}
