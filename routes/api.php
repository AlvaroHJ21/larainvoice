<?php

use App\Http\Controllers\BillingDocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SerialController;
use App\Http\Controllers\StorehouseController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource("categories", CategoryController::class);
Route::resource("currencies", CurrencyController::class);
Route::resource("taxes", TaxController::class);
Route::resource("units", UnitController::class);
Route::resource("products", ProductController::class);

Route::post("images", [ImageController::class, "uploadImage"]);
Route::get('images/{filename}', [ImageController::class, "getImage"])->where('filename', '.*');
Route::delete('images/{filename}', [ImageController::class, "deleteImage"])->where('filename', '.*');

Route::resource("storehouses", StorehouseController::class);
Route::resource("configurations", ConfigurationController::class);
Route::resource("inventories", InventoryController::class)->only(["index", "store", "update"]);
Route::get("inventories/by-storehouse/{storehouse_id}", [InventoryController::class, "byStorehouse"]);
Route::resource("movements", MovementController::class)->only(["index", "store"]);
Route::get("movements/by-inventory/{inventory_id}", [MovementController::class, "byInventory"]);
Route::get("movements/by-product/{product_id}", [MovementController::class, "byProduct"]);

Route::resource("entities", EntityController::class);

Route::get("exchange-rates/todays-change", [ExchangeRateController::class, "todaysChange"]);
Route::resource("exchange-rates", ExchangeRateController::class);

Route::resource("companies", CompanyController::class);

Route::resource("quotations", QuotationController::class);
Route::resource("serials", SerialController::class);
Route::resource("sales", SaleController::class);
Route::resource("documents", BillingDocumentController::class);

Route::resource("vehicles", VehicleController::class);

Route::resource("guides", GuideController::class);
