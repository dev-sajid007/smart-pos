<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrentBalanceToSuppliers extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('suppliers', 'current_balance')) {

        }
        else{
            Schema::table('suppliers', function (Blueprint $table) {
                $table->decimal('current_balance', 14, 2)->default(0.00);
            });
        }
    }

    public function down(){
        if (Schema::hasColumn('suppliers', 'current_balance')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('current_balance');
            });
        }
    }
}
