<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockTransferToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->float('transfer_in', 14, 2)->default(0.00)->after('wastage_quantity');
            $table->float('transfer_out', 14, 2)->default(0.00)->after('wastage_quantity');
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
            $table->dropColumn('transfer_in');
            $table->dropColumn('transfer_out');
        });
    }
}
