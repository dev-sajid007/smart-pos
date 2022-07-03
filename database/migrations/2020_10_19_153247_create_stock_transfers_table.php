<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no')->nullable();
            $table->string('remarks')->nullable();
            $table->date('date');
            $table->unsignedBigInteger('from_company_id');
            $table->unsignedBigInteger('to_company_id');
            $table->unsignedBigInteger('from_warehouse_id')->nullable();
            $table->unsignedBigInteger('to_warehouse_id')->nullable();
            $table->float('total_quantity', 14, 2)->default(0.00);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->tinyInteger('is_received')->default(0);
            $table->timestamps();


            $table->foreign('from_company_id')->references('id')->on('companies');
            $table->foreign('to_company_id')->references('id')->on('companies');
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses');
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transfers');
    }
}
