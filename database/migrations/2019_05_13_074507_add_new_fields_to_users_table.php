<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->unsignedBigInteger('fk_company_id');
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->string('phone')->unique();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['fk_company_id', 'fk_role_id']);
            $table->dropColumn('fk_role_id');
            $table->dropColumn('fk_role_id');
            $table->dropColumn('phone');
        });
    }
}
