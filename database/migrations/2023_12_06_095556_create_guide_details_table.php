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
        Schema::create('guide_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained("guides")->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('description');
            $table->foreignId('product_id')->constrained("products");
            $table->foreignId('inventory_id')->constrained("inventories");
            $table->string("unit_code");
            $table->string("unit_name");
            $table->string("product_code");
            $table->string("sunat_product_code");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_details');
    }
};
