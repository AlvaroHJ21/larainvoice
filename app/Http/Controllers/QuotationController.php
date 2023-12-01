<?php

namespace App\Http\Controllers;

use App\Helpers\Num2Letters;
use App\Models\Business;
use App\Models\Company;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    public function index()
    {
        $ok = true;
        $data = Quotation::with(
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
        $data = Quotation::with(
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

            "discount" => 'numeric',
            "discount_type" => 'numeric',
            "discount_percent" => 'numeric',

            "note" => 'string|nullable',
            "observations" => 'string|nullable',

            "subtotal" => 'required|numeric',
            "total_igv" => 'required|numeric',
            "total_pay" => 'required|numeric',


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
            // Crear la cotizacion
            $quotation = new Quotation();
            $quotation->number = Quotation::generateNextNumber();
            $quotation->currency_id = $request->currency_id;
            $quotation->entity_id = $request->entity_id;
            $quotation->user_id = 1; // TODO: cambiar por el usuario logueado
            $quotation->subtotal = $request->subtotal;
            $quotation->total_igv = $request->total_igv;
            $quotation->total_pay = $request->total_pay;
            $quotation->note = $request->note;
            $quotation->observations = $request->observations;

            if ($request->discount && $request->discount_type && $request->discount_percent) {
                $quotation->discount = $request->discount;
                $quotation->discount_type = $request->discount_type;
                $quotation->discount_percent = $request->discount_percent;
            }

            $quotation->save();


            // Crear los detalles de la cotizacion
            foreach ($request->details as $detail) {
                $quotation->details()->create([
                    "product_id" => $detail["product_id"],
                    "tax_id" => $detail["tax_id"],
                    "quantity" => $detail["quantity"],
                    "price_base" => $detail["price_base"],
                    "code" => $detail["code"],
                    "unit_id" => $detail["unit_id"],
                    "description" => $detail["description"],
                    "description_add" => $detail["description_add"],
                    "discount_percent" => $detail["discount_percent"],
                ]);
            }

            DB::commit();

            $quotation->load(
                "currency:id,symbol",
                "entity:id,name,document_type_id,document_number",
                "user:id,name",
            );

            $ok = true;
            $data = $quotation;
            $message = "Cotizacion creada con exito";

            return response()->json(compact("ok", "data", "message"));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollBack();

            $ok = false;
            $errors = ["Error al crear la cotizacion"];
            return response()->json(compact("ok", "errors"));
        }
    }

    public function update(Request $request, Quotation $quotation)
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
            $quotation->update($request->all());

            if ($request->has('details')) {
                foreach ($request->input('details') as $detail) {
                    $cotizacionDetalle = QuotationDetail::find($detail['id']);
                    $cotizacionDetalle->update($detail);
                }
            }

            DB::commit();

            $quotation->load(
                "currency:id,symbol",
                "entity:id,name,document_type_id,document_number",
                "user:id,name",
            );

            $ok = true;
            $data = $quotation;
            $message = "Cotizacion actualizada con exito";

            return response()->json(compact("ok", "data", "message"));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollBack();

            $ok = false;
            $errors = ["Error al actualizar la cotizacion"];
            return response()->json(compact("ok", "errors"));
        }
    }

    public function pdf(Quotation $quotation)
    {
        $company = Company::first();

        $details = $quotation->details;
        // $details->load("product", "tax", "unit");

        // dd($details);

        $entity = $quotation->entity;

        $currency = $quotation->currency;

        $subTotal = number_format($quotation->subtotal, 2);
        $totalIgv = number_format($quotation->total_igv, 2);
        $total = number_format($quotation->total_pay, 2);

        $converter = new Num2Letters();
        $totalInLetters = $converter->convert($total, $currency->id == 1 ? "SOLES" : "DOLARES");


        $pdf = Pdf::loadView("pdf.quotation", compact(
            "quotation",
            "company",
            "details",
            "entity",
            "currency",

            // "descuentos",
            // "valorVenta",

            "subTotal",
            "totalIgv",
            "total",
            "totalInLetters"
        ));

        return $pdf->stream();

        // return view("pdf.quotation", compact(
        //     "quotation",
        //     "details",
        //     "company",
        //     "entity",
        //     "currency",

        //     // "descuentos",
        //     // "valorVenta",

        //     "subTotal",
        //     "totalIgv",
        //     "total",
        //     "totalInLetters"
        // ));
    }
}
