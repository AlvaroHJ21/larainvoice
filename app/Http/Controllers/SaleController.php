<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Serial;
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
            "document",
            "document.serial:id,serial",
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
            "details.product.inventories",
            "details.product.inventories.storehouse:id,name",
            "details.tax",
            "details.unit",
            "document",
            "document.serial",
            "payments",
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

            "details" => 'required|array',
            "details.*.product_id" => 'required|integer',
            "details.*.inventory_id" => 'required|integer',
            "details.*.tax_id" => 'required|integer',
            "details.*.unit_id" => 'required|integer',
            "details.*.quantity" => 'required|integer',
            "details.*.price_base" => 'required|numeric',
            "details.*.code" => 'required|string',
            "details.*.description" => 'required|string',
            "details.*.description_add" => 'string|nullable',

            "payments" => 'required|array',
            "payments.*.date" => 'required|date',
            "payments.*.amount" => 'required|numeric',
            "payments.*.observations" => 'string|nullable',
            "payments.*.payment_method_id" => 'required|integer',

            "document" => 'required|array',
            "document.serial_id" => 'required|integer',
            "document.document_type_code" => 'required|string',
            "document.emission_date" => 'required|date',
            "document.due_date" => 'required|date',
            "document.operation_type_id" => 'required|integer',
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
            $sale = Sale::create($request->all());

            $sale->save();


            // Crear los detalles de la venta
            foreach ($request->details as $detail) {
                $sale->details()->create($detail);
                // Actualizar el inventario
                $inventory = Inventory::find($detail['inventory_id']);
                $inventory->total -= $detail['quantity'];
                $inventory->save();
                // Crear el movimiento de inventario
                $inventory->movements()->create([
                    "quantity" => $detail['quantity'],
                    "type" => 0,
                ]);
            }

            // Crear los pagos de la venta
            foreach ($request->payments as $payment) {
                $sale->payments()->create($payment);
            }

            // Crear el documento
            $correlative = Serial::find($request->document['serial_id'])->next_correlative();
            $document = array_merge($request->document, ['correlative' => $correlative]);
            $sale->document()->create($document);





            DB::commit();

            $sale->load(
                "currency:id,symbol",
                "entity:id,name,document_type_id,document_number",
                "user:id,name",
                "document",
                "document.serial:id,serial",
            );

            $ok = true;
            $data = $sale;
            $message = "Venta creada con exito";

            return response()->json(compact("ok", "data", "message"));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollBack();

            $ok = false;
            $errors = ["Error al crear la venta"];
            return response()->json(compact("ok", "errors"));
        }
    }

    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            "note" => 'string|nullable',
            "observations" => 'string|nullable',

            "purchase_order_number" => 'string|nullable',

            "is_active" => 'boolean',
        ]);

        $paymentsValidator = Validator::make($request->all(), [
            "payments.*.id" => 'integer|required',
            "payments.*.date" => 'date',
            "payments.*.amount" => 'numeric',
            "payments.*.observations" => 'string|nullable',
            "payments.*.payment_method_id" => 'integer',
        ]);


        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        if ($request->has('payments') && $paymentsValidator->fails()) {
            $ok = false;
            $errors = $paymentsValidator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        DB::beginTransaction();

        try {
            // Actualizar la cotizacion
            $sale->update($request->all());

            if ($request->has('payments')) {

                //* Eliminar los pagos que no estan en la peticion
                foreach ($sale->payments as $payment) {
                    if (!in_array($payment->id, array_column($request->input('payments'), 'id'))) {
                        $payment->delete();
                    }
                }

                //* Actualizar o crear los pagos
                foreach ($request->input('payments') as $payment) {
                    $salePayment = Payment::find($payment['id']);
                    if ($salePayment) {
                        $salePayment->update($payment);
                    } else {
                        $sale->payments()->create($payment);
                    }
                }
            }

            DB::commit();

            $sale->load(
                "currency:id,symbol",
                "entity:id,name,document_type_id,document_number",
                "user:id,name",
                "document",
                "document.serial:id,serial",
            );

            $ok = true;
            $data = $sale;
            $message = "Venta actualizada con exito";

            return response()->json(compact("ok", "data", "message"));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollBack();

            $ok = false;
            $errors = ["Error al actualizar la venta"];
            return response()->json(compact("ok", "errors"));
        }
    }
}
