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
 * App\Models\TruckMaintenance
 *
 * @property int $id
 * @property int $company_id
 * @property int $truck_id
 * @property \Carbon\Carbon|null $maintenance_date
 * @property string|null $maintenance_type
 * @property int $cost
 * @property int $odometer
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
 * @property-read mixed $maintenance_type_i18n
 * @property-read mixed $truck_h_id
 * @property-read \App\Models\Truck $truck
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TruckMaintenance onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereMaintenanceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereMaintenanceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereOdometer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereTruckId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TruckMaintenance whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TruckMaintenance withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TruckMaintenance withoutTrashed()
 * @mixin \Eloquent
 */
class TruckMaintenance extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['maintenance_date','deleted_at'];

    protected $table = 'truck_maintenances';

    protected $fillable = [
        'maintenance_date',
        'maintenance_type',
        'cost',
        'odometer',
        'remarks'
    ];

    protected $hidden = [
        'id',
        'company_id',
        'truck_id',
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
        'truckHId',
        'maintenanceTypeI18n'
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getTruckHIdAttribute()
    {
        return HashIds::encode($this->attributes['truck_id']);
    }

    public function truck()
    {
        return $this->belongsTo('App\Models\Truck');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function getMaintenanceTypeI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['maintenance_type']);
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