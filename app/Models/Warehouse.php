<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/21/2016
 * Time: 4:36 PM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

/**
 * App\Models\Warehouse
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $phone_num
 * @property string|null $status
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Company $company
 * @property-read mixed $company_h_id
 * @property-read mixed $h_id
 * @property-read mixed $status_i18n
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WarehouseSection[] $sections
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Warehouse onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse wherePhoneNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Warehouse whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Warehouse withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Warehouse withoutTrashed()
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'address',
        'phone_num',
        'status',
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
        'companyHId',
        'statusI18n',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function purchaseOrders()
    {
        return null; //$this->hasMany('App\Model\PurchaseOrder');
    }

    public function sections()
    {
        return $this->hasMany('App\Models\WarehouseSection');
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