<?php

use Illuminate\Database\Seeder;

class PaymentTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_types')->delete();

        \DB::table('payment_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Cash',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Loan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Cash/Loan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
        ));
    }
}
