<?php

namespace App\Http\Controllers;

use App\Models\BillingDocument;
use Illuminate\Http\Request;

class BillingDocumentController extends Controller
{
    public function index()
    {
        $data = BillingDocument::with(
            'serial:id,serial',
            'sale',
            'sale.entity:id,name',
            'sale.currency:id,symbol'
        )
            ->orderBy('created_at', 'desc')
            ->get();
        $ok = true;
        return response()->json(compact("data", "ok"));
    }
}
