<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TransactionType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TransactionType extends Model
{
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
