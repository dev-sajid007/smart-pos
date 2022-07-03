<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGlAccountsColumnToAccountChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_charts', function (Blueprint $table) {
            $table->unsignedBigInteger('gl_account_id')->nullable();

            $table->foreign('gl_account_id')->references('id')->on('gl_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_charts', function (Blueprint $table) {
            $table->dropColumn('gl_account_id');
        });
    }
}
