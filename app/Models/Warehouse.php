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