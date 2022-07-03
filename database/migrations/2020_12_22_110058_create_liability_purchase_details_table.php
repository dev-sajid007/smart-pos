<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiabilityPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liability_purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('particular')->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->unsignedBigInteger('liability_purchase_id');
            $table->unsignedBigInteger('liability_id');
            $table->unsignedBigInteger('company_id');

            $table->timestamps();

            $table->foreign('liability_id')->references('id')->on('users');
            $table->foreign('liability_purchase_id')->references('id')->on('liability_purchases')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liability_purchase_details');
    }
}
