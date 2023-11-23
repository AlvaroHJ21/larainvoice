<?php

namespace App\Http\Controllers;

use App\Models\Storehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StorehouseController extends Controller
{
    public function index()
    {
        $data = Storehouse::all();
        $ok = true;
        return response()->json(compact("data", "ok"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string"
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        $request->merge(["user_id" => 1]); //TODO: get user_id from auth

        $data = Storehouse::create($request->all());
        $ok = true;
        $message = "Almacén creado correctamente";

        return response()->json(compact("data", "ok", "message"));
    }

    public function update(Request $request, Storehouse $storehouse)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string",
            "is_active" => "boolean"
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        $storehouse->update($request->all());

        $ok = true;
        $data = $storehouse;
        $message = "Almacén actualizado correctamente";

        return response()->json(compact("ok", "message", "data"));
    }

    public function destroy(Storehouse $storehouse)
    {
        $storehouse->delete();
        $ok = true;
        $message = "Almacén eliminado correctamente";

        return response()->json(compact("ok", "message"));
    }
}
