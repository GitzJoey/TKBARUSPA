<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Vinkla\Hashids\Facades\Hashids;

/**
 * App\Models\Receipt
 *
 * @property int $id
 * @property int $company_id
 * @property int $po_id
 * @property int $vendor_trucking_id
 * @property int $truck_id
 * @property string|null $article_code
 * @property string|null $license_plate
 * @property string|null $driver_name
 * @property \Carbon\Carbon|null $receipt_date
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\ProductUnit $baseProductUnit
 * @property-read mixed $base_product_unit_h_id
 * @property-read mixed $h_id
 * @property-read mixed $item_h_id
 * @property-read mixed $selected_product_units_h_id
 * @property-read mixed $truck_h_id
 * @property-read mixed $vendor_trucking_h_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ReceiptDetail[] $receiptDetails
 * @property-read \App\Models\ProductUnit $selectedProductUnit
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Receipt onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereArticleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereDriverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereLicensePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt wherePoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereReceiptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereTruckId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereVendorTruckingId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Receipt withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Receipt withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $status
 * @property-read mixed $company_h_id
 * @property-read mixed $po_h_id
 * @property-read \App\Models\PurchaseOrder $purchaseOrder
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereStatus($value)
 */
class Receipt extends Model
{
    use SoftDeletes;

    protected $table = 'receipts';

    protected $dates = ['deleted_at', 'receipt_date'];

    protected $fillable = [
        'article_code',
        'driver_name',
        'receipt_date',
        'status',
        'remarks',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'po_id',
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
        'poHId',
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

    public function getPoHIdAttribute()
    {
        return HashIds::encode($this->attributes['po_id']);
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

    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder', 'po_id');
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
