<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 11/22/2016
 * Time: 1:54 PM
 */

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

class WarehouseSection extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'warehouse_sections';

    protected $fillable = [
        'name',
        'position',
        'capacity',
        'remarks'
    ];

    protected $hidden = [
        'id',
        'warehouse_id',
        'company_id',
        'capacity_unit_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $appends = [
        'hid',
        'warehouseHId',
        'companyHId',
        'capacityUnitHId',
    ];

    public function getHidAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getWarehouseHIdAttribute()
    {
        return Hashids::encode($this->attributes['warehouse_id']);
    }

    public function getCompanyHIdAttribute()
    {
        return Hashids::encode($this->attributes['company_id']);
    }

    public function getCapacityUnitHIdAttribute()
    {
        return Hashids::encode($this->attributes['capacity_unit_id']);
    }

    public function purchaseOrders()
    {
        return null; //$this->hasMany('App\Models\PurchaseOrder');
    }

    public function warehouse()
    {
        $this->belongsTo('App\Models\Warehouse');
    }

    public function capacityUnit()
    {
        return $this->belongsTo('App\Models\Unit', 'capacity_unit_id');
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