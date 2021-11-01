<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CustomerType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Customer[] $customers
 * @property-read int|null $customers_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomerType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerType extends Model
{
    public function customers()
    {
        return $this->hasMany('App\Customer');
    }
}
