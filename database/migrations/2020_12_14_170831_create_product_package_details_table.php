<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPackageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_package_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_package_id');
            $table->unsignedBigInteger('product_id');
            $table->float('quantity', 15, 2)->default(0.00);
            $table->decimal('price', 15, 2)->default(0.00);
            $table->decimal('amount', 15, 2)->virtualAs('quantity * price');

            $table->timestamps();

            $table->foreign('product_package_id')->references('id')->on('product_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_package_details');
    }
}
