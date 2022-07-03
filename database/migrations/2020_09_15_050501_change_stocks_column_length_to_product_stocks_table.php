<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStocksColumnLengthToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('opening_quantity', 20, 2)->default(0.00)->change();
            $table->decimal('available_quantity', 20, 2)->default(0.00)->change();
            $table->decimal('purchased_quantity', 20, 2)->default(0.00)->change();
            $table->decimal('sold_quantity', 20, 2)->default(0.00)->change();
            $table->decimal('wastage_quantity', 20, 2)->default(0.00)->change();
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
