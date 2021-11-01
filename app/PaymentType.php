<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PaymentType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Invoice[] $invoices
 * @property-read int|null $invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Stock[] $stocks
 * @property-read int|null $stocks_count
 */
class PaymentType extends Model
{
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function stocks()
    {
        return $this->hasMany('App\Stock');
    }
}
