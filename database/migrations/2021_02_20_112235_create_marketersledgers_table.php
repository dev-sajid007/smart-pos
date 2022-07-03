<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketersledgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketersledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('marketers_id');
            $table->decimal('amount',20,2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketersledgers');
    }
}
