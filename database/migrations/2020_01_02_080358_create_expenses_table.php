<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function(Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('expense_type_id')->unsigned()->index('expenses_expense_type_id');
			$table->float('amount', 10, 0);
			$table->string('expense_by');
			$table->date('date');
			$table->text('remark');
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
		Schema::drop('expenses');
	}

}
