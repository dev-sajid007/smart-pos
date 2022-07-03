<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToSoftwarePayments extends Migration
{

    public function up()
    {
        Schema::table('software_payments', function (Blueprint $table) {
            $table->text('alert')->nullable();
            $table->string('amount')->nullable();
            $table->string('paid_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('software_payments', function (Blueprint $table) {
            $table->dropColumn('alert');
            $table->dropColumn('amount');
            $table->dropColumn('paid_date');
        });
    }
}
