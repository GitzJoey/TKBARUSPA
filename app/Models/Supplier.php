<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/7/2016
 * Time: 12:17 AM
 */

namespace App\Models;

use Auth;
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
    ];

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
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
        return $this->belongsToMany('App\Models\Product', 'supplier_prod');
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