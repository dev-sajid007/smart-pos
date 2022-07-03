<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_name')->unique();
            $table->string('product_code')->nullable();
            $table->string('fk_category_id');
            $table->string('brand_id')->nullable();
            $table->string('fk_product_unit_id');
            $table->decimal('product_cost', 10, 2)->default('0.00');
            $table->integer('quantity')->nullable()->default(0);
            $table->string('company_id');
            $table->string('purchase_upload_code');
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
        Schema::dropIfExists('purchase_uploads');
    }
}
