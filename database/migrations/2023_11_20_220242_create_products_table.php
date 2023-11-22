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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->string("name");
            $table->foreignId("category_id")->constrained("categories");
            $table->foreignId("unit_id")->constrained("units");
            $table->string("image")->nullable();

            $table->decimal("selling_price", 10, 4);
            $table->foreignId("selling_price_currency_id")->constrained("currencies");
            $table->decimal("buy_price", 10, 4)->nullable()->default(0);
            $table->foreignId("buy_price_currency_id")->nullable()->constrained("currencies");

            $table->foreignId("tax_id")->constrained("taxes");
            $table->smallInteger("discount_type")->default(0);
            $table->decimal("discount_value", 10, 2)->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
