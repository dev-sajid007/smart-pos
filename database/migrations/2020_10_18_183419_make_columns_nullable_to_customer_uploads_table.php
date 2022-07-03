<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeColumnsNullableToCustomerUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_uploads', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
            $table->text('customer_category')->nullable()->change();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_category_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_uploads', function (Blueprint $table) {
            //
        });
    }
}
