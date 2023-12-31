<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntityController extends Controller
{
    public function index()
    {
        $data = Entity::where('is_active', true)
            ->orderBy('created_at', 'desc')->get();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|numeric|in:1,2,3',
            'name' => 'required|string',
            'document_type_id' => 'required|numeric',
            'document_number' => 'required|string|unique:entities,document_number',
            'address' => 'required|string',
            'ubigeo' => 'required|string|size:6',
            'phone' => 'nullable|string|max:11',
            'email' => 'nullable|email',
            'is_retention_agent' => 'boolean',
            'discount_percentage' => 'numeric',
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"), 400);
        }

        $entity = Entity::create($request->all());

        $ok = true;
        $data = $entity;
        $message = "Entidad creada correctamente";
        return response()->json(compact("ok", "data", "message"));
    }

    public function update(Request $request, Entity $entity)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'numeric|in:1,2,3',
            'name' => 'string',
            'document_type_id' => 'numeric',
            'document_number' => 'string',
            'address' => 'string',
            'ubigeo' => 'string|size:6',
            'phone' => 'string|max:11',
            'email' => 'email',
            'is_retention_agent' => 'boolean',
            'discount_percentage' => 'numeric',
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        $entity->update($request->all());

        $ok = true;
        $data = $entity;
        $message = "Entidad actualizada correctamente";
        return response()->json(compact("ok", "data", "message"));
    }

    public function destroy(Entity $entity)
    {
        $entity->is_active = false;
        $entity->save();

        $ok = true;
        $message = "Entidad eliminada correctamente";
        return response()->json(compact("ok", "message"));
    }
}
