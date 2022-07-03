<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fk_company_id');
            $table->integer('fk_created_by');
            $table->integer('fk_updated_by');
            $table->integer('fk_group_id');
            $table->string('group_name');
            $table->text('group_mobiles');
            $table->integer('status');
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
        Schema::dropIfExists('sms_groups');
    }
}
