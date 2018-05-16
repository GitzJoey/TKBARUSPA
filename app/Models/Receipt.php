<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Vinkla\Hashids\Facades\Hashids;

class Receipt extends Model
{
    use SoftDeletes;

    protected $table = 'receipts';

    protected $dates = ['deleted_at', 'receipt_date'];

    protected $fillable = [
        'article_code',
        'license_plate',
        'driver_name',
        'receipt_date',
        'remarks',
    ];

    protected $hidden = [
        'id',
        'po_id',
        'vendor_trucking_id',
        'truck_id',
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
        'selectedProductUnitsHId',
        'baseProductUnitHId',
        'companyHId',
        'vendorTruckingHId',
        'truckHId',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getItemHIdAttribute()
    {
        return HashIds::encode($this->attributes['item_id']);
    }

    public function getSelectedProductUnitsHIdAttribute()
    {
        return HashIds::encode($this->attributes['selected_product_unit_id']);
    }

    public function getBaseProductUnitHIdAttribute()
    {
        return HashIds::encode($this->attributes['base_product_unit_id']);
    }

    public function getVendorTruckingHIdAttribute()
    {
        return Hashids::encode($this->attributes['vendor_trucking_id']);
    }

    public function getTruckHIdAttribute()
    {
        return Hashids::encode($this->attributes['truck_id']);
    }

    public function receiptDetails()
    {
        return $this->hasMany('App\Models\ReceiptDetail');
    }

    public function selectedProductUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit', 'selected_product_unit_id');
    }

    public function baseProductUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit', 'base_product_unit_id');
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
