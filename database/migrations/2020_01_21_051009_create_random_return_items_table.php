<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRandomReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('random_return_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('random_return_id');
            $table->text('description')->nullable();
            $table->enum('condition', ['1', '0'])->comment('1 = Good[+inventory], 0 = Damaged[!+inventory]');
            $table->integer('quantity')->default(0);
            $table->double('amount', 14,2)->default(0.00);
            $table->timestamps();

            $table->foreign('random_return_id')->references('id')->on('random_returns');
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
        Schema::dropIfExists('random_return_items');
    }
}
