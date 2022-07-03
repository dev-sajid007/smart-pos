<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('fk_created_by');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_updated_by');
            $table->foreign('fk_updated_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_party_id');
            $table->foreign('fk_party_id')->references('id')->on('parties');
            $table->unsignedBigInteger('fk_approved_by');
            $table->foreign('fk_approved_by')->references('id')->on('users');
            $table->date('voucher_date');
            $table->enum('voucher_type', ['debit', 'credit']);
            $table->string('voucher_reference')->uniuqe()->nullable();
            $table->decimal('payable_amount', 14, 2);
            $table->decimal('paid_amount', 14, 2);
            $table->decimal('due_amount', 14, 2);
            $table->string('cheque_number')->uniuqe()->nullable();
            $table->dateTime('approved_at');
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
        Schema::dropIfExists('vouchers');
    }
}
