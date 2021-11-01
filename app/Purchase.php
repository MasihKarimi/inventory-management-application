<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Purchase
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $payment_type_id
 * @property string|null $reference
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Purchase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Purchase extends Model
{
    public function customer() {
        return $this->belongsTo('App\Customer');
    }

    public function paymentType() {
        return $this->belongsTo('App\PaymentType');
    }

    public function stocks() {
        return $this->hasMany('App\Stock');
    }

    public function transaction() {
        return $this->hasOne('App\Transaction');
    }
}
