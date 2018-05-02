<?php

namespace App\Models;

use Lang;
use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $code_sign
 * @property string|null $name
 * @property string|null $address
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $distance
 * @property string|null $distance_text
 * @property int|null $duration
 * @property string|null $duration_text
 * @property string|null $city
 * @property string|null $phone_number
 * @property string|null $fax_num
 * @property string|null $tax_id
 * @property int $payment_due_day
 * @property int $price_level_id
 * @property string|null $status
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BankAccount[] $bankAccounts
 * @property-read \App\Models\Company $company
 * @property-read mixed $h_id
 * @property-read mixed $price_level_h_id
 * @property-read mixed $status_i18n
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Profile[] $personsInCharge
 * @property-read \App\Models\PriceLevel $priceLevel
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCodeSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDistanceText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDurationText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereFaxNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePaymentDueDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePriceLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Customer withoutTrashed()
 * @mixin \Eloquent
 */
class Customer extends Model
{
	use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone_number',
        'fax_num',
        'tax_id',
        'payment_due_day',
        'status',
        'remarks',
    ];

    protected $hidden = [
        'id',
        'price_level_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $appends = [
        'hId',
        'priceLevelHId',
        'statusI18n'
    ];

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getPriceLevelHIdAttribute()
    {
        return Hashids::encode($this->attributes['price_level_id']);
    }

    public function personsInCharge()
    {
        return $this->morphMany('App\Models\Profile', 'owner');
    }

    public function bankAccounts()
    {
        return $this->morphMany('App\Models\BankAccount', 'owner');
    }

    public function priceLevel()
    {
        return $this->belongsTo('App\Models\PriceLevel', 'price_level_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function sales_orders()
    {
        return null;//$this->hasMany('App\Models\SalesOrder');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->updated_by = $user->id;
            }
        });

        static::deleting(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->deleted_by = $user->id;
                $model->save();
            }
        });
    }
}
