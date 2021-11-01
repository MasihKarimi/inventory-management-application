<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Invoice
 *
 * @property int $id
 * @property int $customer_id
 * @property int $payment_type_id
 * @property int $invoice_type_id
 * @property string $currency
 * @property int $order_number
 * @property string $rfq_number
 * @property string $order_date
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\InvoiceItem[] $invoiceItems
 * @property-read int|null $invoice_items_count
 * @property-read \App\InvoiceType $invoiceType
 * @property-read \App\PaymentType $paymentType
 * @property-read \App\Transaction $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereInvoiceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereRfqNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property int|null $quotation_number
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invoice whereQuotationNumber($value)
 */
class Invoice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditEvents = [
        'created',
        'updated',
        'deleted'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function invoiceType()
    {
        return $this->belongsTo('App\InvoiceType');
    }

    public function paymentType()
    {
        return $this->belongsTo('App\PaymentType');
    }

    public function transaction()
    {
        return $this->hasOne('App\Transaction');
    }

    public function invoiceItems()
    {
        return $this->hasMany('App\InvoiceItem');
    }
}
