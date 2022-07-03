<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketersTable extends Migration
{


    public function up()
    {
        Schema::create('marketers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('marketers_name')->nullable();
            $table->string('marketers_mobile')->unique();
            $table->string('designation')->nullable();
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
        Schema::dropIfExists('marketers');
    }
}
