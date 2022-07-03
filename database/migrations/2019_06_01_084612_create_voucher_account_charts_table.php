<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherAccountChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_account_charts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('fk_company_id');
            $table->foreign('fk_company_id')->references('id')->on('companies');

            $table->unsignedBigInteger('fk_created_by');
            $table->foreign('fk_created_by')->references('id')->on('users');

            $table->unsignedBigInteger('fk_updated_by');
            $table->foreign('fk_updated_by')->references('id')->on('users');

            $table->unsignedBigInteger('fk_voucher_id');
            $table->foreign('fk_voucher_id')->references('id')->on('vouchers');

            $table->unsignedBigInteger('fk_account_chart_id');
            $table->foreign('fk_account_chart_id')->references('id')->on('account_charts');

            $table->decimal('payable_amount', 14, 2);
            $table->decimal('paid_amount', 14, 2)->default(0);

            $table->text('description')->nullable();
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
        Schema::dropIfExists('voucher_account_charts');
    }
}
