<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliver_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->default(0);
            $table->unsignedBigInteger('deliver_id')->default(0);
            $table->unsignedBigInteger('item_id')->default(0);
            $table->unsignedBigInteger('selected_product_unit_id')->default(0);
            $table->unsignedBigInteger('base_product_unit_id')->default(0);
            $table->decimal('conversion_value', 19, 2)->default(0);
            $table->decimal('brutto', 19, 2)->default(0);
            $table->decimal('base_brutto', 19, 2)->default(0);
            $table->decimal('netto', 19, 2)->default(0);
            $table->decimal('base_netto', 19, 2)->default(0);
            $table->decimal('tare', 19, 2)->default(0);
            $table->decimal('base_tare', 19, 2)->default(0);
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
        Schema::dropIfExists('deliver_details');
    }
}
