<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DealType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DealType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DealType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DealType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DealType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DealType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DealType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DealType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DealType extends Model
{
    public function transactions()
    {
        $this->hasMany('App\Transaction');
    }
}
