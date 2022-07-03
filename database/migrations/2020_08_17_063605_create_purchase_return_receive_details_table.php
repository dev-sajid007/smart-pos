<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_receive_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('purchase_return_receive_id');
            $table->enum('condition', ['1', '0'])->comment('1 = Good[+inventory], 0 = Damaged[!+inventory]');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->foreign('purchase_return_receive_id', 'receive_id')->references('id')->on('purchase_return_receives');
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
        Schema::dropIfExists('purchase_return_receive_details');
    }
}
