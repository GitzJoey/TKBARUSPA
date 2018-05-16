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
use App\Traits\CompanyFilter;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Truck
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $type
 * @property string|null $plate_number
 * @property string|null $inspection_date
 * @property string|null $driver
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
 * @property-read mixed $type_i18n
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Truck onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereInspectionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck wherePlateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Truck whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Truck withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Truck withoutTrashed()
 * @mixin \Eloquent
 */
class Truck extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'trucks';

    protected $fillable = [
        'type',
        'plate_number',
        'inspection_date',
        'driver',
        'remarks'
    ];

    protected $hidden = [
        'id',
        'vendor_trucking_id',
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
        'typeI18n'
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getTypeI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['type']);
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function vendorTrucking()
    {
        return $this->belongsTo('App\Models\VendorTrucking');
    }

    public function truckMaintenances()
    {
        return $this->hasMany('App\Models\TruckMaintenance');
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