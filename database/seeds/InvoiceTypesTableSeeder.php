<?php

use Illuminate\Database\Seeder;

class InvoiceTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('invoice_types')->delete();

        \DB::table('invoice_types')->insert(array (
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
                'name' => 'Quotation',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
        ));
    }
}
