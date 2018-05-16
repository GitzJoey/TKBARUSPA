<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/22/2016
 * Time: 3:16 AM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

/**
 * App\Models\VendorTrucking
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $tax_id
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
 * @property-read mixed $status_i18n
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorTrucking onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VendorTrucking whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorTrucking withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\VendorTrucking withoutTrashed()
 * @mixin \Eloquent
 */
class VendorTrucking extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'vendor_truckings';

    protected $fillable = [
        'name',
        'address',
        'tax_id',
        'status',
        'maintenance_by_company',
        'remarks'
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

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function bankAccounts()
    {
        return $this->morphMany('App\Models\BankAccount', 'owner');
    }

    public function trucks()
    {
        return $this->hasMany('App\Models\Truck');
    }

    public function purchaseOrders()
    {
        return null; //$this->hasMany('App\Model\PurchaseOrder');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
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