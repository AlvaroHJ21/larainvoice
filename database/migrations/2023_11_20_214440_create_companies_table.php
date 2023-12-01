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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string("ruc", 11);
            $table->string("business_name");
            $table->string("trade_name");
            $table->string("fiscal_address");
            $table->string("ubigeo");
            $table->string("district");
            $table->string("province");
            $table->string("department");

            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("website")->nullable();
            $table->string("logo")->nullable();

            $table->string("secondary_user_username")->nullable();
            $table->string("secondary_user_password")->nullable();
            $table->string("client_id")->nullable();
            $table->string("client_secret")->nullable();
            $table->string("access_token")->nullable();

            $table->boolean("in_production")->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
