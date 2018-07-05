<?php

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

class Deliver extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $table = 'delivers';

    protected $dates = ['deleted_at', 'deliver_date', 'confirm_receive_date'];

    protected $fillable = [
        'article_code',
        'driver_name',
        'deliver_date',
        'status',
        'remarks',
        'confirm_receive_date',
        'confirm_remarks',
    ];

    protected $hidden = [
        'company_id',
        'so_id',
        'vendor_trucking_id',
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
        'soHId',
        'vendorTruckingHId',
        'truckHId',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getSoHIdAttribute()
    {
        return HashIds::encode($this->attributes['so_id']);
    }

    public function getVendorTruckingHIdAttribute()
    {
        return Hashids::encode($this->attributes['vendor_trucking_id']);
    }

    public function getTruckHIdAttribute()
    {
        return Hashids::encode($this->attributes['truck_id']);
    }

    public function deliverDetails()
    {
        return $this->hasMany('App\Models\DeliverDetail');
    }

    public function salesOrder()
    {
        return $this->belongsTo('App\Models\SalesOrder', 'so_id');
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
