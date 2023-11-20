<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categoies = Category::all();
        return response()->json([
            "ok" => true,
            "data" => $categoies
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "errors" => $validator->errors()->all()
            ], 400);
        }

        $category = Category::create($request->all());

        return response()->json([
            "ok" => true,
            "data" => $category
        ]);
    }
}
