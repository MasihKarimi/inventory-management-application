<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInvoiceItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoice_items', function(Blueprint $table) {
			$table->foreign('invoice_id', 'invoice_items_invoice_id')->references('id')->on('invoices')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('product_id', 'invoice_items_product_id')->references('id')->on('products')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoice_items', function(Blueprint $table)
		{
			$table->dropForeign('invoice_items_invoice_id');
			$table->dropForeign('invoice_items_product_id');
		});
	}

}
