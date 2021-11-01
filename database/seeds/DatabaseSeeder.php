<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaymentTypesTableSeeder::class);
        $this->call(TransactionTypesTableSeeder::class);
        $this->call(CustomerTypesTableSeeder::class);
        $this->call(InvoiceTypesTableSeeder::class);
        $this->call(DealTypesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
    }
}
