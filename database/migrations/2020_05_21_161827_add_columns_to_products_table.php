<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_group_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->tinyInteger('has_serial')->default(0);

            $table->foreign('product_group_id')->references('id')->on('product_groups');
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_group_id');
            $table->dropColumn('brand_id');
            $table->dropColumn('has_serial');
        });
    }
}
