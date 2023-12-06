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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();

            $table->foreignId("sale_id")->nullable()->constrained("sales");
            $table->date("issue_date");

            $table->string("serial");
            $table->integer("correlative");

            $table->foreignId("receiver_id")->constrained("entities");
            $table->string("receiver_name");
            $table->string("receiver_document_type");
            $table->string("receiver_document_number");
            $table->string("receiver_address");

            $table->string("type");
            $table->string("reason_code");
            $table->string("reason_description");

            $table->decimal("gross_weight", 10, 2);
            $table->unsignedInteger("load_unit_quantity");

            $table->string("delivery_ubigeo");
            $table->string("delivery_ubigeo_name");
            $table->string("delivery_address");

            $table->string("origin_ubigeo");
            $table->string("origin_ubigeo_name");
            $table->string("origin_address");

            $table->string("transport_mode");
            $table->boolean("vehicle_m1_l")->default(false);

            $table->date("start_date");

            $table->foreignId("transportist_id")->nullable()->constrained("entities");
            $table->string("transportist_name");
            $table->string("transportist_document_number");
            $table->string("transportist_document_type");

            $table->foreignId("driver_id")->nullable()->constrained("entities");
            $table->string("driver_name");
            $table->string("driver_document_number");
            $table->string("driver_document_type");
            $table->string("driver_license_number");

            $table->boolean("is_active")->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
