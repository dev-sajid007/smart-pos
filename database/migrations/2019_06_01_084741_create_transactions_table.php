<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->unsignedBigInteger('fk_created_by');
            $table->unsignedBigInteger('fk_updated_by');
            $table->unsignedBigInteger('fk_account_id');
            $table->decimal('amount', 14, 2);
            $table->string('transactionable_type');
            $table->unsignedBigInteger('transactionable_id');
            $table->timestamps();

            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->foreign('fk_updated_by')->references('id')->on('users');
            $table->foreign('fk_account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
