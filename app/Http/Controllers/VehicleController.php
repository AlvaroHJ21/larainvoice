<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function index()
    {
        $data = Vehicle::all();
        $ok = true;
        return response()->json(compact("ok", "data",));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plate' => 'required|unique:vehicles|max:255',
            'brand' => 'nullable|max:255',
            'model' => 'nullable|max:255',
            'category_m1_l' => 'boolean',
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("errors", "ok"));
        }

        $vehicle = Vehicle::create($request->all());

        $ok = true;
        $data = $vehicle;
        $message = "Vehículo creado correctamente";
        return response()->json(compact("data", "ok", "message"));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'plate' => 'unique:vehicles,plate,' . $vehicle->id . '|max:255',
            'brand' => 'nullable|max:255',
            'model' => 'nullable|max:255',
            'category_m1_l' => 'boolean',
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("errors", "ok"));
        }

        $vehicle->update($request->all());

        $ok = true;
        $data = $vehicle;
        $message = "Vehículo actualizado correctamente";
        return response()->json(compact("data", "ok", "message"));
    }
}
