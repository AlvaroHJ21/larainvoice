<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::all();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required|string|unique:categories,name",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"), 400);
        }

        $ok = true;
        $data = Category::create($request->all());
        $message = "Categoría creada exitosamente";

        return response()->json(compact("ok", "data", "message"));
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $erros = $validator->errors()->all();
            return response()->json(compact("ok", "errors"), 400);
        }

        $category->update($request->all());

        $ok = true;
        $data = $category;
        $message = "Categoría actualizada exitosamente";

        return response()->json(compact("ok", "data", "message"));
    }

    public function destroy(Category $category)
    {
        $ok = true;
        $category->delete();
        $message = "Categoría eliminada exitosamente";

        return response()->json(compact("ok", "message"));
    }
}
