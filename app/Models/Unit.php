<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/9/2016
 * Time: 10:30 PM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use App\Models\ProductUnit;

/**
 * App\Models\Unit
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $symbol
 * @property string|null $status
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $h_id
 * @property-read mixed $status_i18n
 * @property-read mixed $unit_name
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Unit onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Unit whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Unit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Unit withoutTrashed()
 * @mixin \Eloquent
 */
class Unit extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'units';

    protected $fillable = [
        'name',
        'symbol',
        'status',
        'remarks',
    ];

    protected $hidden = [
        'id',
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
        'unitName'
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getUnitNameAttribute()
    {
        return $this->attributes['name'] . ' (' . $this->attributes['symbol'] . ')';
    }

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }
    
    public function productUnits()
    {
        //return $this->hasMany('App\Models\ProductUnit', 'unit_id');
    }

    public function capacityUnits()
    {
        //return $this->hasMany('App\Models\WarehouseSection', 'capacity_unit_id');
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