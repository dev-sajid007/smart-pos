<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_uploads', function (Blueprint $table) {
            $table->string('expire_date')->nullable();
            $table->string('barcode_id')->nullable();
            $table->string('generic_id')->nullable();
            $table->string('product_rak_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_uploads', function (Blueprint $table) {
            $table->dropColumn('expire_date');
            $table->dropColumn('barcode_id');
            $table->dropColumn('generic_id');
            $table->dropColumn('product_rak_no');
        });
    }
}
