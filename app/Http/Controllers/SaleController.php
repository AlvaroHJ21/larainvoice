<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function index()
    {
        $ok = true;
        $data = Sale::with(
            "currency:id,symbol",
            "entity:id,name,document_type_id,document_number",
            "user:id,name",
        )
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(compact("ok", "data"));
    }

    public function show($id)
    {
        $ok = true;
        $data = Sale::with(
            "currency",
            "entity",
            "user",
            "details",
            "details.product",
            "details.tax",
            "details.unit"
        )
            ->findOrFail($id);
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency_id' => 'required|integer',
            'entity_id' => 'required|integer',

            "discount" => 'numeric|nullable',
            "discount_type" => 'numeric|nullable',
            "discount_percent" => 'numeric|nullable',

            "note" => 'string|nullable',
            "observations" => 'string|nullable',

            "subtotal" => 'required|numeric',
            "total_igv" => 'required|numeric',
            "total_pay" => 'required|numeric',

            "quotation_id" => 'integer|nullable',
            "purchase_order_number" => 'string|nullable',


            "details.*.product_id" => 'required|integer',
            "details.*.tax_id" => 'required|integer',
            "details.*.quantity" => 'required|integer',
            "details.*.price_base" => 'required|numeric',
            "details.*.code" => 'required|string',
            "details.*.description" => 'required|string',
            "details.*.description_add" => 'string|nullable',
            "details.*.discount_percent" => 'numeric',
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        DB::beginTransaction();

        try {
            // Crear la orden de venta
            $request->merge([
                "number" => Sale::generateNextNumber(),
                "user_id" => 1,
            ]);
            $saleOrder = Sale::create($request->all());

            $saleOrder->save();


            // Crear los detalles de la cotizacion
            foreach ($request->details as $detail) {
                $saleOrder->details()->create($detail);
            }

            DB::commit();

            $saleOrder->load(
                "currency:id,symbol",
                "entity:id,name,document_type_id,document_number",
                "user:id,name",
            );

            $ok = true;
            $data = $saleOrder;
            $message = "Orden de venta creada con exito";

            return response()->json(compact("ok", "data", "message"));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollBack();

            $ok = false;
            $errors = ["Error al crear la orden de venta"];
            return response()->json(compact("ok", "errors"));
        }
    }

    public function update(Request $request, Sale $saleOrder)
    {
        $validator = Validator::make($request->all(), [
            'currency_id' => 'integer',
            'entity_id' => 'integer',

            "discount" => 'numeric',
            "discount_type" => 'numeric',
            "discount_percent" => 'numeric',

            "subtotal" => 'numeric',
            "total_igv" => 'numeric',
            "total_pay" => 'numeric',

            "note" => 'string|nullable',
            "observations" => 'string|nullable',

            "is_active" => 'boolean',
        ]);


        $detailsValidator = Validator::make($request->all(), [
            "details.*.id" => 'integer|required',
            "details.*.product_id" => 'integer',
            "details.*.tax_id" => 'integer',
            "details.*.quantity" => 'integer',
            "details.*.price_base" => 'numeric',
            "details.*.code" => 'string',
            "details.*.description" => 'string',
            "details.*.description_add" => 'string|nullable',
            "details.*.discount_percent" => 'numeric',
        ]);


        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        if ($request->has('details') && $detailsValidator->fails()) {
            $ok = false;
            $errors = $detailsValidator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        DB::beginTransaction();

        try {
            // Actualizar la cotizacion
            $saleOrder->update($request->all());

            if ($request->has('details')) {
                foreach ($request->input('details') as $detail) {
                    $cotizacionDetalle = SaleDetail::find($detail['id']);
                    $cotizacionDetalle->update($detail);
                }
            }

            DB::commit();

            $saleOrder->load(
                "currency:id,symbol",
                "entity:id,name,document_type_id,document_number",
                "user:id,name",
            );

            $ok = true;
            $data = $saleOrder;
            $message = "Orden de venta actualizada con exito";

            return response()->json(compact("ok", "data", "message"));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollBack();

            $ok = false;
            $errors = ["Error al actualizar la orden de venta"];
            return response()->json(compact("ok", "errors"));
        }
    }
}
