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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string("ruc", 11);
            $table->string("business_name");
            $table->string("trade_name");
            $table->string("fiscal_address");
            $table->string("ubigeo");
            $table->string("district");
            $table->string("province");
            $table->string("department");
            $table->string("phone");
            $table->string("email");
            $table->string("website");
            $table->string("logo");
            $table->string("secondary_user_username");
            $table->string("secondary_user_password");
            $table->string("client_id");
            $table->string("client_secret");
            $table->string("access_token");
            $table->boolean("in_production")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
