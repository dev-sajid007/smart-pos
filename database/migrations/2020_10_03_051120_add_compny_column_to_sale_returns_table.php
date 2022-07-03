<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompnyColumnToSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_company_id')->nullable()->default(1);
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('fk_created_by')->nullable()->default(1);
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_updated_by')->nullable()->default(1);
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
        Schema::table('sale_returns', function (Blueprint $table) {
            //
        });
    }
}
