<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedColumnToAccountReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_created_by')->nullable()->default(1);
            $table->foreign('fk_created_by')->references('id')->on('users');
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
            //
        });
    }
}
