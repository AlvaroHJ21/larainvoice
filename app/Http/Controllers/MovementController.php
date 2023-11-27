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
        $data = Movement::with("product", "storehouse")->get();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "type" => "required|numeric|in:0,1",
            "product_id" => "required|numeric",
            "storehouse_id" => "required|numeric",
            "quantity" => "required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        // validar si existe un inventario para el producto y almacen
        $inventory = Inventory::where("product_id", $request->product_id)
            ->where("storehouse_id", $request->storehouse_id)
            ->first();

        if ($inventory) {
            // actualizar el inventario

            if ($request->type == 0) {
                // salida
                $inventory->total -= $request->quantity;
            } else {
                // entrada
                $inventory->total += $request->quantity;
            }
            $inventory->save();
        } else {
            // crear el inventario con la cantidad
            $inventory = Inventory::create([
                "product_id" => $request->product_id,
                "storehouse_id" => $request->storehouse_id,
                "total" => $request->quantity,
            ]);
        }

        // crear el movimiento
        $data = Movement::create($request->all());
        $ok = true;
        return response()->json(compact("ok", "data"));
    }
}
