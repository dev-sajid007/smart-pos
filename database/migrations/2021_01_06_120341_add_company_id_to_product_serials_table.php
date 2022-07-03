<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdToProductSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_serials', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->default(1);
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('purchase_detail_id')->nullable();

            $table->foreign('purchase_detail_id')->references('id')->on('purchase_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_serials', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('warehouse_id');
            $table->dropColumn('purchase_detail_id');
        });
    }
}
