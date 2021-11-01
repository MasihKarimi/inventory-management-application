<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_items', function(Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('invoice_id')->unsigned()->index('invoice_items_invoice_id');
			$table->bigInteger('product_id')->unsigned()->index('invoice_items_product_id');
			$table->integer('quantity');
			$table->float('price', 10, 0);
            $table->string('remark')->nullable();
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
		Schema::drop('invoice_items');
	}

}
