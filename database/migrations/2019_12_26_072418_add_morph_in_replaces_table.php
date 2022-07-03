<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMorphInReplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('replaces', function (Blueprint $table) {
            $table->dropForeign(['inventory_return_id']);
            $table->dropColumn('inventory_return_id');
            $table->morphs('replacable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replaces', function (Blueprint $table) {
            $table->dropMorphs('replacable');
            $table->unsignedBigInteger('inventory_return_id');
            $table->foreign('inventory_return_id')->references('id')->on('inventory_returns');
        });
    }
}
