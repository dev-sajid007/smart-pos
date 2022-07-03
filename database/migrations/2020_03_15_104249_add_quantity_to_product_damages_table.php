<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuantityToProductDamagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_damages', function (Blueprint $table) {
            $table->string('reference')->nullable()->change();
        });

        Schema::table('product_damage_items', function(Blueprint $table){
            $table->decimal('quantity', 20, 2)->default('0');
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
            $table->dropColumn('quantity');
        });
    }
}
