<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transactions', function(Blueprint $table) {
			$table->foreign('customer_id', 'transactions_customer_id')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('deal_type_id', 'transactions_deal_type_id')->references('id')->on('deal_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('invoice_id', 'transactions_invoice_id')->references('id')->on('invoices')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('purchase_id', 'transactions_purchase_id')->references('id')->on('purchases')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('transaction_type_id', 'transactions_transaction_type_id')->references('id')->on('transaction_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transactions', function(Blueprint $table)
		{
            $table->dropForeign('transactions_customer_id');
            $table->dropForeign('transactions_deal_type_id');
            $table->dropForeign('transactions_invoice_id');
            $table->dropForeign('transactions_purchase_id');
            $table->dropForeign('transactions_transaction_type_id');
		});
	}

}
