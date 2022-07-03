<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductDamageIdInProductDamageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_damage_items', function (Blueprint $table) {
            $table->dropColumn('product_id');

            $table->unsignedBigInteger('fk_product_id');

            $table->unsignedBigInteger('product_damage_id');
            $table->foreign('product_damage_id')->references('id')->on('product_damages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_damage_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');

            $table->dropColumn('fk_product_id');

            $table->dropForeign(['product_damage_id']);
            $table->dropColumn('product_damage_id');
        });
    }
}
