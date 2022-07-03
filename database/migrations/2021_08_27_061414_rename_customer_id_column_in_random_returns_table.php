<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameCustomerIdColumnInRandomReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::table('random_returns', function (Blueprint $table) {
            $table->renameColumn('fk_customer_id', 'fk_customer_id');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::table('random_returns', function (Blueprint $table) {
            $table->renameColumn('fk_customer_id', 'customer_id');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
