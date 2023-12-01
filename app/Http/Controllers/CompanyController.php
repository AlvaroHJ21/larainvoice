<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $data = Company::all();
        $ok = true;

        return response()->json(compact("ok", "data"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "ruc" => "required|string|max:11",
            "business_name" => "required|string",
            "trade_name" => "required|string",
            "fiscal_address" => "required|string",
            "ubigeo" => "required|string",
            "district" => "required|string",
            "province" => "required|string",
            "department" => "required|string",

            "phone" => "nullable|string",
            "email" => "nullable|string",
            "website" => "nullable|string",
            "logo" => "nullable|string",

            "secondary_user_username" => "nullable|string",
            "secondary_user_password" => "nullable|string",
            "client_id" => "nullable|string",
            "client_secret" => "nullable|string",
            "access_token" => "nullable|string",
            "in_production" => "boolean",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $data = $validator->errors()->all();
            return response()->json(compact("ok", "data"));
        }

        $company = Company::create($request->all());

        $ok = true;
        $data = $company;
        $message = "Empresa creada correctamente";

        return response()->json(compact("ok", "data", "message"));
    }

    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            "ruc" => "string|max:11",
            "business_name" => "string",
            "trade_name" => "string",
            "fiscal_address" => "string",
            "ubigeo" => "string",
            "district" => "string",
            "province" => "string",
            "department" => "string",

            "phone" => "nullable|string",
            "email" => "nullable|string",
            "website" => "nullable|string",
            "logo" => "nullable|string",

            "secondary_user_username" => "nullable|string",
            "secondary_user_password" => "nullable|string",
            "client_id" => "nullable|string",
            "client_secret" => "nullable|string",
            "access_token" => "nullable|string",
            "in_production" => "boolean",
        ]);

        if ($validator->fails()) {
            $ok = false;
            $data = $validator->errors()->all();
            return response()->json(compact("ok", "data"));
        }

        $oldImage = $company->logo;
        if ($request->has("logo") && $request->logo != $oldImage) {
            Storage::delete('public/images/' . $oldImage);
        }

        $company->update($request->all());

        $ok = true;
        $data = $company;
        $message = "Empresa actualizada correctamente";

        return response()->json(compact("ok", "data", "message"));
    }
}
