<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_return_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('sale_detail_id')->nullable();
            $table->float('quantity', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 15, 2)->virtualAs('quantity * price');

            $table->timestamps();

            $table->foreign('sale_return_id')->references('id')->on('sale_returns');
            $table->foreign('product_id')->references('id')->on('products');
        });


        Schema::table('sale_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('sale_id')->nullable()->change();


            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_return_details');
    }
}
