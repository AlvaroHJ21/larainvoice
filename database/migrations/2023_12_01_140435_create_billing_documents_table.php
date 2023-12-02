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
        Schema::create('billing_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId("serial_id")->constrainted("serials");
            $table->integer("correlative");

            $table->date("emission_date");
            $table->date("due_date")->nullable();

            $table->string("document_type_code");
            $table->integer("operation_type_id");

            $table->boolean("sent_to_sunat")->default(false);
            $table->boolean("accepted_by_sunat")->default(false);
            $table->boolean("rejected_by_sunat")->default(false);
            $table->boolean("is_active")->default(true);

            $table->string("digest_value")->nullable();

            $table->foreignId("sale_id")->nullable()->constrained("sales")->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_documents');
    }
};
