<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('fk_created_by');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_updated_by');
            $table->foreign('fk_updated_by')->references('id')->on('users');
            $table->date('purchase_date');
            $table->string('purchase_reference');
            $table->unsignedBigInteger('fk_supplier_id');
            $table->foreign('fk_supplier_id')->references('id')->on('suppliers');
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
        Schema::dropIfExists('purchases');
    }
}
