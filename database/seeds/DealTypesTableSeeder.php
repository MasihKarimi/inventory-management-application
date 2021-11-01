<?php

use Illuminate\Database\Seeder;

class DealTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('deal_types')->delete();

        \DB::table('deal_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Sale',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Purchase',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Cash Receive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Cash Payment',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
        ));
    }
}
