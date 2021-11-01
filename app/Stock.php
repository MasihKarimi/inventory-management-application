<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Stock
 *
 * @property int $id
 * @property int $product_id
 * @property int $purchase_id
 * @property int $quantity
 * @property float $net_price
 * @property float $sale_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Customer $customer
 * @property-read \App\PaymentType $paymentType
 * @property-read \App\Product $stock
 * @property-read \App\Transaction $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereNetPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stock extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditEvents = [
        'created',
        'updated',
        'deleted'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }

    public function paymentType()
    {
        return $this->belongsTo('App\PaymentType');
    }
}
