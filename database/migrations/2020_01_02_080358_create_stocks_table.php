<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stocks', function(Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('product_id')->unsigned()->index('stocks_product_id');
            $table->bigInteger('purchase_id')->unsigned()->index('stocks_purchase_id');
            $table->integer('quantity');
            $table->float('net_price', 10, 0);
            $table->float('sale_price', 10, 0)->nullable();
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
		Schema::drop('stocks');
	}

}
