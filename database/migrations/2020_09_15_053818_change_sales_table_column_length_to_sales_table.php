<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSalesTableColumnLengthToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total_payable', 20, 2)->default(0.00)->change();
            $table->decimal('sub_total', 20, 2)->default(0.00)->change();
            $table->decimal('balance', 20, 2)->default(0.00)->change();
            $table->decimal('receive_amount', 20, 2)->default(0.00)->change();
            $table->decimal('previous_due', 20, 2)->default(0.00)->change();
            $table->decimal('paid_amount', 20, 2)->default(0.00)->change();
            $table->decimal('change_amount', 20, 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            //
        });
    }
}
