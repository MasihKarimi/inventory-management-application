<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Transaction
 *
 * @property int $id
 * @property int $customer_id
 * @property int $transaction_type_id
 * @property int $deal_type_id
 * @property int|null $invoice_id
 * @property int|null $purchase_id
 * @property float $amount
 * @property string $date
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Customer $customer
 * @property-read \App\DealType $dealType
 * @property-read \App\Invoice|null $invoice
 * @property-read \App\Stock $stock
 * @property-read \App\TransactionType $transactionType
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereDealTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereTransactionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditEvents = [
        'created',
        'updated',
        'deleted'
    ];

    public function transactionType()
    {
        return $this->belongsTo('App\TransactionType');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }

    public function dealType()
    {
        return $this->belongsTo('App\DealType');
    }
}
