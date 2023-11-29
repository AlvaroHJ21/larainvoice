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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->integer("number");
            $table->foreignId("currency_id")->constrained("currencies");
            $table->foreignId("entity_id")->constrained("entities");
            $table->foreignId("user_id")->constrained("users");

            $table->decimal("discount", 10, 4)->default(0);
            $table->smallInteger("discount_type")->default(1);
            $table->decimal("discount_percent", 10, 4)->default(0);

            $table->decimal("subtotal", 10, 4);
            $table->decimal("total_igv", 10, 4);
            $table->decimal("total_pay", 10, 4);

            $table->string("note")->nullable();
            $table->string("observations")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
