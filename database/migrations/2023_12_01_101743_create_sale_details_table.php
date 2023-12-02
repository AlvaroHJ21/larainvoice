<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sale_id")->constrained("sales")->cascadeOnDelete();
            $table->string("code");
            $table->string("description");
            $table->string("description_add")->nullable();

            $table->foreignId("product_id")->constrained("products");
            $table->foreignId("tax_id")->constrained("taxes");
            $table->foreignId("unit_id")->constrained("units");
            $table->foreignId("inventory_id")->constrained("inventories");
            $table->integer("quantity");

            $table->decimal("price_base", 10, 4);

            $table->decimal("discount", 10, 4)->default(0);
            $table->smallInteger("discount_type")->default(1);
            $table->decimal("discount_percent", 10, 4)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
