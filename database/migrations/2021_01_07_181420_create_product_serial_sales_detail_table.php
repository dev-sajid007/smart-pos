<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSerialSalesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_serial_sales_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('sales_details_id');
            $table->unsignedBigInteger('product_serial_id');

            $table->foreign('sales_details_id')->references('id')->on('sales_details')->onDelete('cascade');
            $table->foreign('product_serial_id')->references('id')->on('product_serials');

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
        Schema::dropIfExists('product_serial_sales_details');
    }
}
