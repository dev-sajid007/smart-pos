<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductDiscountColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('discount', 14, 2)->default(0.00);
        });

        Schema::table('sales_details', function (Blueprint $table) {
            $table->decimal('product_discount', 14, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('discount');
        });

        Schema::table('sales_details', function (Blueprint $table) {
            $table->dropColumn('product_discount');
        });
    }
}
