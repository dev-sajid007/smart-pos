<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketersDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('marketers_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('marketers_id');
            $table->integer('start_amount')->default(0);
            $table->integer('end_amount')->default(0);
            $table->integer('marketers_commission')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketers_details');
    }
}
