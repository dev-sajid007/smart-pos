<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->unsignedBigInteger('fk_created_by');
            $table->unsignedBigInteger('fk_updated_by')->nullable();
            $table->unsignedBigInteger('fk_from_account_id');
            $table->unsignedBigInteger('fk_to_account_id');
            $table->string('date');
            $table->decimal('amount', 14, 2);
            $table->text('comment')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('fk_from_account_id')->references('id')->on('accounts');
            $table->foreign('fk_to_account_id')->references('id')->on('accounts');
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->foreign('fk_updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fund_transfers');
    }
}
