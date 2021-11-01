<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TransactionView
 *
 * @property int $id
 * @property string $report_type
 * @property int $customer_id
 * @property string $date
 * @property string|null $description
 * @property string $type
 * @property float $amount
 * @property float|null $balance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereReportType($value)
 * @mixin \Eloquent
 * @property string $deal_type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionView whereDealType($value)
 */
class TransactionView extends Model
{
    protected $table = 'transactions_view';

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
}
