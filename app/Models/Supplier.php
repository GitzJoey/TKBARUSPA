<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/7/2016
 * Time: 12:17 AM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

/**
 * App\Models\Supplier
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BankAccount[] $bankAccounts
 * @property-read \App\Models\Company $company
 * @property-read mixed $h_id
 * @property-read mixed $status_i18n
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Profile[] $personsInCharge
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Supplier onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Supplier withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Supplier withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int $company_id
 * @property string|null $code_sign
 * @property string|null $name
 * @property string|null $address
 * @property string|null $city
 * @property string|null $phone_number
 * @property string|null $fax_num
 * @property string|null $tax_id
 * @property int $payment_due_day
 * @property string|null $status
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $list_selected_product_h_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCodeSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereFaxNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier wherePaymentDueDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier whereUpdatedBy($value)
 */
class Supplier extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'suppliers';

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
        'company_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $appends = [
        'hId',
        'statusI18n',
        'listSelectedProductHId'
    ];

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getListSelectedProductHIdAttribute()
    {
        $pId = [];

        foreach ($this->products as $p) {
            array_push($pId, $p->hId);
        }

        return $pId;
    }

    public function personsInCharge()
    {
        return $this->morphMany('App\Models\Profile', 'owner');
    }

    public function bankAccounts()
    {
        return $this->morphMany('App\Models\BankAccount', 'owner');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function purchaseOrders()
    {
        return null;//$this->hasMany('App\Model\PurchaseOrder');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
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