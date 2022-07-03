<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fk_created_by');
            $table->foreign('fk_created_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_updated_by');
            $table->foreign('fk_updated_by')->references('id')->on('users');
            $table->unsignedBigInteger('fk_company_id');
            $table->foreign('fk_company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('fk_billing_cycle_id');
            $table->foreign('fk_billing_cycle_id')->references('id')->on('billing_cycles');
            $table->date('trial_ends_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->decimal('amount', 10, 2)->default('0');
            $table->tinyInteger('trial_period')->default('0');
            $table->tinyInteger('alert_before')->default('0');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default('0');
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
        Schema::dropIfExists('company_packages');
    }
}
