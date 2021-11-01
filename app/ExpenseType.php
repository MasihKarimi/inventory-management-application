<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ExpenseType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Expense $expenses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExpenseType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExpenseType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExpenseType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExpenseType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExpenseType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExpenseType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExpenseType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExpenseType extends Model
{
    public function expenses()
    {
        return $this->belongsTo('App\Expense');
    }
}
