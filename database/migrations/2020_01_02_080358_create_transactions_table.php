<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customer_id')->unsigned()->index('transactions_customer_id');
			$table->bigInteger('transaction_type_id')->unsigned()->index('transactions_transaction_type_id');
            $table->bigInteger('deal_type_id')->unsigned()->index('transactions_deal_type_id');
			$table->bigInteger('invoice_id')->unsigned()->nullable()->unique()->index('transactions_invoice_id');
            $table->bigInteger('purchase_id')->unsigned()->nullable()->unique()->index('transactions_purchase_id');
			$table->float('amount', 10, 0);
            $table->date('date');
			$table->text('description')->nullable();
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
		Schema::drop('transactions');
	}

}
