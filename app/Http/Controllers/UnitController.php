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
        $results = Unit::all();
        return response()->json(compact('ok', 'results'));
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
        $result = $unit;
        return response()->json(compact('ok', 'result'));
    }
}
