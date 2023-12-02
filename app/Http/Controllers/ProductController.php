<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::with(
            "category:id,name",
            "unit:id,name",
            "selling_price_currency:id,name,symbol",
            "buy_price_currency:id,name",
            "tax:id,name,percentage",
            "inventories:id,product_id,storehouse_id,total",
            "inventories.storehouse:id,name"
        )->orderBy("created_at", "desc")->get();

        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        $data = Product::create($request->all());
        $data->load(
            "category:id,name",
            "unit:id,name",
            "selling_price_currency:id,name,symbol",
            "buy_price_currency:id,name",
            "tax:id,name,percentage",
            "inventories:id,product_id,storehouse_id,total",
            "inventories.storehouse:id,name"
        );

        $ok = true;
        $message = "Producto creado correctamente";
        return response()->json(compact("ok", "data", "message"));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            "code" => "string|unique:products,code," . $product->id,
            "name" => "string",
            "category_id" => "integer",
            "unit_id" => "integer",
            "image" => "string",
            "selling_price" => "numeric",
            "selling_price_currency_id" => "integer",
            "buy_price" => "numeric",
            "buy_price_currency_id" => "integer",
            "tax_id" => "integer",
            "is_active" => "boolean",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"), 400);
        }

        $oldImage = $product->image;
        if ($request->has("image") && $request->image != $oldImage) {
            Storage::delete('public/images/' . $oldImage);
        }

        $product->update($request->all());
        $data = $product;
        $data->load(
            "category:id,name",
            "unit:id,name",
            "selling_price_currency:id,name,symbol",
            "buy_price_currency:id,name",
            "tax:id,name,percentage",
            "inventories:id,product_id,storehouse_id,total",
            "inventories.storehouse:id,name"
        );

        $ok = true;
        $message = "Producto actualizado correctamente";
        return response()->json(compact("ok", "data", "message"));
    }

    public function destroy(Product $product)
    {

        $product->is_active = false;
        $product->save();

        // $product->delete();

        // eliminar imagen
        if ($product->image) {
            Storage::delete('public/images/' . $product->image);
        }

        $ok = true;
        $message = "Producto eliminado correctamente";
        return response()->json(compact("ok", "message"));
    }
}
