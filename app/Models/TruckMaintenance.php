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