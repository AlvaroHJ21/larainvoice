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
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->smallInteger("type");
            $table->string("name");
            $table->foreignId("document_type_id")->constrained("entity_document_types");
            $table->string("document_number")->unique();
            $table->string("address");
            $table->char("ubigeo", 6);

            $table->string("phone", 11)->nullable();
            $table->string("email")->nullable();

            $table->boolean("is_retention_agent")->default(false);
            $table->float("discount_percentage")->default(0);
            $table->boolean("is_active")->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
