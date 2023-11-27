<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function index()
    {
        $data = Inventory::with("product:id,name", "storehouse:id,name")->get();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "product_id" => "required|numeric",
            "storehouse_id" => "required|numeric",
            "total" => "required|numeric|min:0",
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
            $inventory->total += $request->total;
            $inventory->save();
            $message = "Inventario actualizado correctamente";
        } else {
            // crear el inventario con la cantidad
            $inventory = Inventory::create($request->all());
            $message = "Inventario creado correctamente";
        }

        //crear el movimiento inicial
        $movement = Movement::create([
            "type" => 1,
            "inventory_id" => $inventory->id,
            "quantity" => $request->total,
        ]);

        $inventory->load("product:id,name", "storehouse:id,name");

        $ok = true;
        $data = $inventory;

        return response()->json(compact("ok", "data", "message"));
    }
}
