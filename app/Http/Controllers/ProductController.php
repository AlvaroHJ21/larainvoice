<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::with(
            "category:id,name",
            "unit:id,name",
            "selling_price_currency:id,name",
            "buy_price_currency:id,name",
            "tax:id,name,percentage",
        )->get();

        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $reques)
    {
        $validator = Validator::make($reques->all(), [
            "code" => "required|string|unique:products",
            "name" => "required|string",
            "category_id" => "required|integer",
            "unit_id" => "required|integer",
            "image" => "string",
            "selling_price" => "required|numeric",
            "selling_price_currency_id" => "required|integer",
            "buy_price" => "numeric",
            "buy_price_currency_id" => "integer",
            "tax_id" => "required|integer",
            "is_active" => "boolean",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"), 400);
        }

        $data = Product::create($reques->all());
        $data->load(
            "category:id,name",
            "unit:id,name",
            "selling_price_currency:id,name",
            "buy_price_currency:id,name",
            "tax:id,name,percentage",
        );

        $ok = true;
        $message = "Producto creado correctamente";
        return response()->json(compact("ok", "data", "message"));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $ok = true;
        $message = "Producto eliminado correctamente";
        return response()->json(compact("ok", "message"));
    }
}
