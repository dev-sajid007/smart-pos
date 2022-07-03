<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['advance', 'due']);
            $table->double('amount', 10,2)->default(0.00);
            $table->morphs('transactionable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_reviews');
    }
}
