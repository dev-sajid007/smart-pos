<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('particular')->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->unsignedBigInteger('asset_purchase_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('company_id');

            $table->timestamps();

            $table->foreign('asset_id')->references('id')->on('users');
            $table->foreign('asset_purchase_id')->references('id')->on('asset_purchases')->onDelete('cascade');
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
        Schema::dropIfExists('asset_purchase_details');
    }
}
