<?php

use Illuminate\Database\Seeder;

class CustomerTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('customer_types')->delete();

        \DB::table('customer_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'One Time',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Persistent',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Persistent/Vendor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
        ));
    }
}
