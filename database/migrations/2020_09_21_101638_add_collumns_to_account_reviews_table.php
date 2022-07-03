<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumnsToAccountReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_reviews', function (Blueprint $table) {
            $table->decimal('previous_due', 14, 2)->nullable();
            $table->decimal('current_due', 14, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_reviews', function (Blueprint $table) {
            $table->dropColumn('previous_due');
            $table->dropColumn('current_due');
        });
    }
}
