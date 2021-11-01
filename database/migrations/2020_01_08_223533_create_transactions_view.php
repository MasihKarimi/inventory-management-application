<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
        CREATE VIEW transactions_view AS (
            SELECT t.id, t.customer_id, t.date, t.description, tp.name AS type, dt.name AS deal_type, t.amount,
                (
                    COALESCE((SELECT SUM(amount) FROM transactions t_c WHERE CONVERT(UNIX_TIMESTAMP(t_c.date), UNSIGNED INTEGER) + t_c.id <= CONVERT(UNIX_TIMESTAMP(t.date), UNSIGNED INTEGER) + t.id AND transaction_type_id = 1 AND customer_id = t.customer_id), 0)
                    - COALESCE((SELECT SUM(amount) FROM transactions t_d WHERE CONVERT(UNIX_TIMESTAMP(t_d.date), UNSIGNED INTEGER) + t_d.id <= CONVERT(UNIX_TIMESTAMP(t.date), UNSIGNED INTEGER) + t.id AND transaction_type_id = 2 and customer_id = t.customer_id), 0)
                )AS balance
            FROM transactions t
            JOIN transaction_types tp ON tp.id = t.transaction_type_id
            JOIN deal_types dt ON dt.id = t.deal_type_id
            ORDER BY t.date, t.id
        )');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW transactions_view');
    }
}
