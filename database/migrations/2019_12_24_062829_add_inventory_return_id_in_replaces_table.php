<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInventoryReturnIdInReplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('replaces', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_return_id');
            $table->foreign('inventory_return_id')->references('id')->on('inventory_returns')->onDelete('cascade');
            $table->dropForeign(['sale_return_id']);
            $table->dropColumn('sale_return_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replaces', function (Blueprint $table) {
            $table->dropForeign(['inventory_return_id']);
            $table->dropColumn('inventory_return_id');
            $table->unsignedBigInteger('sale_return_id');
            $table->foreign('sale_return_id')->references('id')->on('sale_returns');
        });
    }
}
