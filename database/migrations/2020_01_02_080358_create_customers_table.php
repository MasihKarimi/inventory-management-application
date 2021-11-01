<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customer_type_id')->unsigned()->index('customers_customer_type_id');
			$table->string('name');
			$table->string('address')->nullable();
			$table->string('phone', 15)->nullable();
			$table->string('focal_point_person')->nullable();
			$table->integer('TIN_number')->nullable();
			$table->string('license_number', 20)->nullable();
			$table->string('registration_number', 20)->nullable();
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
		Schema::drop('customers');
	}

}
