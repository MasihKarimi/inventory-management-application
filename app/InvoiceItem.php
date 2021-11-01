<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\InvoiceItem
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 * @property string $remark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Invoice $invoice
 * @property-read \App\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 */
class InvoiceItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditEvents = [
        'created',
        'updated',
        'deleted'
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
