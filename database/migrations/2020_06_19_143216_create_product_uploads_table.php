<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_name')->unique();
            $table->string('product_code')->nullable();
            $table->string('fk_category_id');
            $table->string('brand_id')->nullable();
            $table->string('fk_product_unit_id');
            $table->text('product_description')->nullable();
            $table->decimal('product_cost', 10, 2)->default('0.00');
            $table->decimal('product_price', 10, 2)->default('0.00');
            $table->integer('product_alert_quantity')->default('0.00');
            $table->integer('tax')->nullable()->default(0);
            $table->integer('opening_quantity')->nullable()->default(0);
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
        Schema::dropIfExists('product_uploads');
    }
}
