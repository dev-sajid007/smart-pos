<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeQuantityColumnsToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->float('opening_quantity', 14, 2)->default(0.00)->change();
            $table->float('available_quantity', 14, 2)->default(0.00)->change();
            $table->float('purchased_quantity', 14, 2)->default(0.00)->change();
            $table->float('sold_quantity', 14, 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            //
        });
    }
}
