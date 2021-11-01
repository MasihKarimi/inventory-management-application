<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoices', function(Blueprint $table) {
			$table->foreign('customer_id', 'invoices_customer_id')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('invoice_type_id', 'invoices_invoice_type_id')->references('id')->on('invoice_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('payment_type_id', 'invoices_payment_type_id')->references('id')->on('payment_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoices', function(Blueprint $table)
		{
			$table->dropForeign('invoices_customer_id');
			$table->dropForeign('invoices_invoice_type_id');
			$table->dropForeign('invoices_payment_type_id');
		});
	}

}
