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
        Schema::create('ubigeos', function (Blueprint $table) {
            $table->id();
            $table->string("ubigeo", 6)->unique();
            $table->string("department", 50);
            $table->string("province", 50);
            $table->string("district", 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubigeos');
    }
};
