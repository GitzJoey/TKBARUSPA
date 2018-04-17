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

/**
 * App\Models\WarehouseSection
 *
 * @property int $id
 * @property int $company_id
 * @property int $warehouse_id
 * @property string|null $name
 * @property string|null $position
 * @property int $capacity
 * @property int $capacity_unit_id
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Unit $capacityUnit
 * @property-read mixed $capacity_unit_h_id
 * @property-read mixed $company_h_id
 * @property-read mixed $hid
 * @property-read mixed $warehouse_h_id
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WarehouseSection onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereCapacityUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseSection whereWarehouseId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WarehouseSection withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WarehouseSection withoutTrashed()
 * @mixin \Eloquent
 */
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