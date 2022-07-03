<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_receives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('purchase_return_id');
            $table->date('date');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('purchase_return_id')->references('id')->on('purchase_returns');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_return_receives');
    }
}
