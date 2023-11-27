<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovementController extends Controller
{
    public function index()
    {
        $data = Movement::with("inventory.product", "inventory.storehouse")->get();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function byInventory($inventory_id)
    {
        $data = Movement::with("inventory.product:id,name,code", "inventory.storehouse:id,name")
            ->where("inventory_id", $inventory_id)
            ->get();
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
