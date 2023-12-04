<?php

namespace App\Http\Controllers;

use App\Models\Serial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SerialController extends Controller
{
    public function index()
    {
        $data = Serial::orderBy('created_at', 'desc')->get();
        $ok = true;
        return response()->json(compact('ok', 'data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type_code' => 'required|string',
            'serial' => 'required|string|unique:serials,serial',
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact('ok', 'errors'));
        }

        $serial = Serial::create($request->all());

        $ok = true;
        $data = $serial;
        $message = 'Serie creado correctamente';

        return response()->json(compact('ok', 'data', 'message'));
    }

    public function update(Request $request, Serial $serial)
    {
        $validator = Validator::make($request->all(), [
            'document_type_code' => 'string',
            'serial' => 'string|unique:serials,serial',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact('ok', 'errors'));
        }

        $serial->update($request->all());

        $ok = true;
        $data = $serial;
        $message = 'Serie actualizado correctamente';

        return response()->json(compact('ok', 'data', 'message'));
    }
}
