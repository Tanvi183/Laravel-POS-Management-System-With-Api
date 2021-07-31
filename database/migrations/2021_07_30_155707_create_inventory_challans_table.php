<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_challans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inventory_id')->unsigned();
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
            $table->bigInteger('purchase_id')->nullable()->unsigned();
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('big_unit_sales_price')->unsigned()->nullable();
            $table->bigInteger('small_unit_sales_price')->unsigned();
            $table->bigInteger('big_unit_cost_price')->unsigned()->nullable();
            $table->bigInteger('small_unit_cost_price')->unsigned();
            $table->bigInteger('big_unit_qty')->unsigned()->nullable();
            $table->bigInteger('small_unit_qty')->unsigned();
            $table->bigInteger('available_big_unit_qty')->unsigned()->nullable();
            $table->bigInteger('available_small_unit_qty')->unsigned();
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
        Schema::dropIfExists('inventory_challans');
    }
}
