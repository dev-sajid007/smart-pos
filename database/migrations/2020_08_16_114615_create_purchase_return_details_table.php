<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('purchase_return_id');
            $table->text('description')->nullable();
            $table->enum('condition', ['1', '0'])->comment('1 = Good[+inventory], 0 = Damaged[!+inventory]');
            $table->integer('quantity')->default(0);
            $table->double('amount', 14,2)->default(0.00);
            $table->timestamps();

            $table->foreign('purchase_return_id')->references('id')->on('purchase_returns');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_return_details');
    }
}
