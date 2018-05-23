<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->default(0);
            $table->unsignedBigInteger('owner_id')->default(0);
            $table->string('owner_type');
            $table->unsignedBigInteger('warehouse_id')->default(0);
            $table->unsignedBigInteger('product_id')->default(0);
            $table->unsignedBigInteger('base_product_unit_id')->default(0);
            $table->unsignedBigInteger('display_product_unit_id')->default(0);
            $table->boolean('is_current');
            $table->decimal('quantity_in', 19, 2)->default(0);
            $table->decimal('quantity_out', 19, 2)->default(0);
            $table->decimal('quantity_current', 19, 2)->default(0);
            $table->unsignedBigInteger('created_by')->default(0);
            $table->unsignedBigInteger('updated_by')->default(0);
            $table->unsignedBigInteger('deleted_by')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
