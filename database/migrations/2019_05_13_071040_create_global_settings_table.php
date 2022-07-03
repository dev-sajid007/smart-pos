<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('fk_created_by');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_updated_by');
            $table->foreign('fk_updated_by')->references('id')->on('users');
            $table->string('currency_code');
            $table->unsignedBigInteger('fk_product_tax_id');
            $table->foreign('fk_product_tax_id')->references('id')->on('tax_rates');
            $table->unsignedBigInteger('fk_invoice_tax_id');
            $table->foreign('fk_invoice_tax_id')->references('id')->on('tax_rates');
            $table->unsignedBigInteger('fk_default_discount_id');
            $table->foreign('fk_default_discount_id')->references('id')->on('discounts');
            $table->string('purchase_prefix');
            $table->string('sales_prefix');
            $table->string('quotation_prefix');
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
        Schema::dropIfExists('global_settings');
    }
}
