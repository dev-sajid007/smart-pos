<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDamagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_damages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->unsignedBigInteger('fk_created_by');
            $table->unsignedBigInteger('fk_updated_by');
            $table->date('date');
            $table->string('reference');
            $table->decimal('amount', 10, 2)->default('0');
            $table->timestamps();

            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->foreign('fk_updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_damages');
    }
}
