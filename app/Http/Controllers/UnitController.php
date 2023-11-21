<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index()
    {
        $ok = true;
        $data = Unit::all();
        return response()->json(compact('ok', 'data'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'boolean',
        ]);
        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact('ok', 'errors'));
        }
        $ok = true;
        $unit->update($request->all());
        $data = $unit;
        $message = 'Unidad actualizada exitosamente';
        return response()->json(compact('ok', 'data', 'message'));
    }
}
