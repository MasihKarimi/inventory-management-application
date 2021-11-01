<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\InvoiceType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Invoice[] $invoices
 * @property-read int|null $invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InvoiceType extends Model
{
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}
