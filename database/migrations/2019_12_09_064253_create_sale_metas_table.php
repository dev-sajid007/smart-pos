<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('delivered_by')->nullable();
            $table->unsignedBigInteger('sale_id');
            $table->timestamps();

            $table->foreign('received_by')->references('id')->on('sale_distributors');
            $table->foreign('delivered_by')->references('id')->on('sale_distributors');
            $table->foreign('sale_id')->references('id')->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_metas');
    }
}
