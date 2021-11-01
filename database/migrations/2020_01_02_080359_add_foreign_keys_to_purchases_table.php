<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchases', function(Blueprint $table)
		{
			$table->foreign('customer_id', 'purchases_customer_id')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('payment_type_id', 'purchases_payment_type_id')->references('id')->on('payment_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchases', function(Blueprint $table)
		{
			$table->dropForeign('purchases_customer_id');
			$table->dropForeign('purchases_payment_type_id');
		});
	}

}
