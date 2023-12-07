<?php

namespace App\Http\Controllers;

use App\Models\Ubigeo;
use Illuminate\Http\Request;

class UbigeoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->has("search")) {
            $ok = false;
            $errors = ["No se ha enviado un criterio de búsqueda"];
            return response()->json(compact("ok", "errors"), 400);
        }
        //Buscar por ubigeo, departamento, provincia o distrito, limitando a 20 resultados
        // que inicie con el criterio de búsqueda
        $ubigeos = Ubigeo::where("ubigeo", "like", "{$request->search}%")
            ->orWhere("department", "like", "{$request->search}%")
            ->orWhere("province", "like", "{$request->search}%")
            ->orWhere("district", "like", "{$request->search}%")
            ->limit(20)
            ->get();

        $ok = true;
        $data = $ubigeos;

        return response()->json(compact("ok", "data"), 200);
    }
}
