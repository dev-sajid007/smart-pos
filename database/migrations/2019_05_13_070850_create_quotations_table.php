<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('fk_created_by');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_updated_by');
            $table->foreign('fk_updated_by')->references('id')->on('users');
            $table->date('quotation_date');
            $table->string('quotation_reference');
            $table->unsignedBigInteger('fk_customer_id');
            $table->foreign('fk_customer_id')->references('id')->on('customers');
            $table->unsignedBigInteger('fk_status_id');
            $table->foreign('fk_status_id')->references('id')->on('statuses');
            $table->decimal('sub_total', 14, 2)->default('0');
            $table->decimal('invoice_discount', 14, 2)->default('0');
            $table->decimal('invoice_tax', 14, 2)->default('0');
            $table->decimal('total_payable', 14, 2)->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
