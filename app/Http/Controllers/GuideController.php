<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GuideController extends Controller
{
    public function index()
    {
        $data = Guide::orderBy("created_at", "desc")->get();
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function show(Guide $guide)
    {
        $data = $guide->load([
            "details",
            "details.product",
            "details.inventory",
        ]);
        $ok = true;
        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "sale_id" => "nullable|integer",
            "issue_date" => "required|date",
            "serial" => "required|string",
            "correlative" => "required|integer",

            "receiver_id" => "required|integer",
            "receiver_name" => "required|string",
            "receiver_document_type" => "required|string",
            "receiver_document_number" => "required|string",
            "receiver_address" => "required|string",

            "type" => "required|string",
            "reason_code" => "required|string",
            "reason_description" => "required|string",

            "gross_weight" => "required|numeric",
            "load_unit_quantity" => "required|integer",

            "delivery_ubigeo" => "required|string",
            "delivery_ubigeo_name" => "required|string",
            "delivery_address" => "required|string",

            "origin_ubigeo" => "required|string",
            "origin_ubigeo_name" => "required|string",
            "origin_address" => "required|string",

            "transport_mode" => "required|string",

            "start_date" => "required|date",

            "details" => "required|array|min:1",
            "details.*.quantity" => "required|integer",
            "details.*.description" => "required|string",
            "details.*.product_id" => "required|integer",
            "details.*.inventory_id" => "required",
            "details.*.unit_code" => "required",
            "details.*.unit_name" => "required",
            "details.*.product_code" => "required",
            "details.*.sunat_product_code" => "required",
        ]);

        $driverValidator = Validator::make($request->all(), [
            "vehicle_m1_l" => "boolean",

            "driver_id" => "required|integer",
            "driver_name" => "required|string",
            "driver_document_number" => "required|string",
            "driver_document_type" => "required|string",
            "driver_license_number" => "required|string",
        ]);

        $transportistValidator = Validator::make($request->all(), [
            "transportist_id" => "required|integer",
            "transportist_name" => "required|string",
            "transportist_document_number" => "required|string",
            "transportist_document_type" => "required|string",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        if ($request->input("transport_mode") === "01" && $driverValidator->fails()) {
            $ok = false;
            $errors = $driverValidator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        if ($request->input("transport_mode") === "02" && $transportistValidator->fails()) {
            $ok = false;
            $errors = $transportistValidator->errors()->all();
            return response()->json(compact("ok", "errors"));
        }

        try {
            DB::beginTransaction();

            $guide = Guide::create($request->all());

            $details = $request->input("details");
            foreach ($details as $detail) {
                $guide->details()->create($detail);
            }

            DB::commit();

            $ok = true;
            $data = $guide;
            $message = "Guia creada con exito";

            return response()->json(compact("ok", "data", "message"));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollBack();

            $ok = false;
            $errors = ["Error al crear la guia"];
            return response()->json(compact("ok", "errors"));
        }
    }
}
