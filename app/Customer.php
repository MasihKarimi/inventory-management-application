<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Customer
 *
 * @property int $id
 * @property int $customer_type_id
 * @property string $name
 * @property string $phone
 * @property string|null $address
 * @property string|null $focal_point_person
 * @property int|null $TIN_number
 * @property string|null $license_number
 * @property string|null $registration_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\CustomerType $customerType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Invoice[] $invoice
 * @property-read int|null $invoice_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereCustomerTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereFocalPointPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereTINNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Stock[] $stocks
 * @property-read int|null $stocks_count
 */
class Customer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $auditEvents = [
        'created',
        'updated',
        'deleted'
    ];

    public function customerType()
    {
        return $this->belongsTo('App\CustomerType');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function invoice()
    {
        return $this->hasMany('App\Invoice');
    }

    public function purchase()
    {
        return $this->hasMany('App\Purchase');
    }
}
