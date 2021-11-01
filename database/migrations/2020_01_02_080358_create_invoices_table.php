<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customer_id')->unsigned()->index('invoices_customer_id');
			$table->bigInteger('payment_type_id')->unsigned()->nullable()->index('invoices_payment_type_id');
			$table->bigInteger('invoice_type_id')->unsigned()->index('invoices_invoice_type_id');
            $table->string('currency')->nullable();
            $table->integer('quotation_number')->nullable();
            $table->string('rfq_number')->nullable();
            $table->string('order_number')->nullable();
            $table->date('order_date')->nullable();
			$table->date('date');
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
		Schema::drop('invoices');
	}

}
