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
        'statusI18n'
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