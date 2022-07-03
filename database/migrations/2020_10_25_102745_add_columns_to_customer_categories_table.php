<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCustomerCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_categories', function (Blueprint $table) {
            $table->decimal('amount_of', 14, 2)->default(0.00);
            $table->decimal('amount', 14, 2)->default(0.00);
            $table->enum('type', ['amount', 'percent']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_categories', function (Blueprint $table) {
            $table->dropColumn('amount_of');
            $table->dropColumn('amount');
            $table->dropColumn('type');
        });
    }
}
