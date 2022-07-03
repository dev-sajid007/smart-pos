<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdColumnToGlAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gl_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_company_id')->nullable()->default(1);
            $table->foreign('fk_company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gl_accounts', function (Blueprint $table) {
            //
        });
    }
}
