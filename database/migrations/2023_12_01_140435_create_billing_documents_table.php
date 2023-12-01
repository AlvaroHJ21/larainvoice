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

            $table->string("serial");
            $table->integer("correlative");

            $table->date("emission_date");
            $table->date("due_date")->nullable();

            $table->foreignId("document_type_id")->constrained("document_types");
            $table->string("operation_type");

            $table->string("client_name");
            $table->string("client_document_type");
            $table->string("client_document_number");
            $table->string("client_address");

            $table->foreignId("user_id")->constrained("users");

            $table->string("currency_code");
            $table->string("currency_symbol");

            $table->string("amount_in_letters");
            $table->foreignId("payment_method_id")->constrained("payment_methods");


            $table->decimal("discount", 10, 4)->default(0);
            $table->smallInteger("discount_type")->default(1);
            $table->decimal("discount_percent", 10, 4)->default(0);

            $table->decimal("subtotal", 10, 4);
            $table->decimal("total_igv", 10, 4);
            $table->decimal("total_pay", 10, 4);

            $table->string("note")->nullable();
            $table->string("observations")->nullable();

            $table->boolean("sent_to_sunat")->default(false);
            $table->boolean("accepted_by_sunat")->default(false);
            $table->boolean("rejected_by_sunat")->default(false);
            $table->boolean("is_active")->default(true);

            $table->string("digest_value")->nullable();

            $table->string("purchase_order_number")->nullable();

            $table->foreignId("sale_id")->nullable()->constrained("sales");

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
