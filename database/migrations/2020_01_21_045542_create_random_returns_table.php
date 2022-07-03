<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRandomReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('random_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_company_id');
            $table->unsignedBigInteger('fk_customer_id');
            $table->unsignedBigInteger('fk_created_by');
            $table->unsignedBigInteger('fk_updated_by')->nullable();
            $table->date('date');
            $table->string('reference')->nullable();
            $table->text('comment')->nullable();
            $table->double('amount', 10,1)->default(0);
            $table->timestamps();

            $table->foreign('fk_customer_id')->references('id')->on('customers');
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
        Schema::dropIfExists('random_returns');
    }
}
