<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('fk_created_by');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_updated_by');
            $table->foreign('fk_updated_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_product_id');
            $table->foreign('fk_product_id')->references('id')->on('products');
            $table->bigInteger('available_quantity')->default('0');
            $table->bigInteger('purchased_quantity')->default('0');
            $table->bigInteger('sold_quantity')->default('0');
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
        Schema::dropIfExists('product_stocks');
    }
}
