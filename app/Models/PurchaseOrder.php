<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/9/2016
 * Time: 11:50 PM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $table = 'purchase_orders';

    protected $dates = ['deleted_at', 'po_created', 'shipping_date'];

    protected $fillable = [
        'code',
        'po_type',
        'po_created',
        'shipping_date',
        'supplier_type',
        'walk_in_supplier',
        'walk_in_supplier_detail',
        'remarks',
        'internal_remarks',
        'private_remarks',
        'status',
        'disc_percent',
        'disc_value',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'supplier_id',
        'vendor_trucking_id',
        'warehouse_id',
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
        'supplierHId',
        'vendorTruckingHId',
        'warehouseHId',
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

    public function getSupplierHIdAttribute()
    {
        return HashIds::encode($this->attributes['supplier_id']);
    }

    public function getWarehouseHIdAttribute()
    {
        return HashIds::encode($this->attributes['supplier_id']);
    }

    public function items()
    {
        return $this->morphMany('App\Models\Item', 'itemable');
    }

    public function receipts()
    {
        return null;//$this->hasManyThrough('App\Model\Receipt', 'App\Model\Item', 'itemable_id', 'item_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }

    public function vendorTrucking()
    {
        return null;//$this->belongsTo('App\Model\VendorTrucking', 'vendor_trucking_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function warehouse()
    {
        return null;//$this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }

    public function payments()
    {
        return null;//$this->morphMany('App\Models\Payment', 'payable');
    }

    public function totalAmount()
    {
        $itemAmounts = $this->items->map(function($item){
            return $item->price * $item->to_base_quantity;
        });

        $itemTotalAmount = count($itemAmounts) > 0 ? $itemAmounts->sum() : 0;

        $itemDiscounts = $this->items->map(function ($item) {
            return $item->discounts->map(function ($discount) {
                return $discount->item_disc_value;
            })->all();
        })->flatten();

        $itemDiscountAmount = count($itemDiscounts) > 0 ? $itemDiscounts->sum() : 0;

        $expenseAmounts = $this->expenses->map(function ($expense){
            return $expense->type === 'EXPENSETYPE.ADD' ? $expense->amount : ($expense->amount * -1);
        });

        $expenseTotalAmount = count($expenseAmounts) > 0 ? $expenseAmounts->sum() : 0;

        return $itemTotalAmount + $expenseTotalAmount - $itemDiscountAmount - $this->disc_value;
    }

    public function totalAmountPaid()
    {
        return $this->payments->filter(function ($payment, $key){
            return $payment->status !== 'TRFPAYMENTSTATUS.UNCONFIRMED'
            && $payment->status !== 'GIROPAYMENTSTATUS.WE'
            && $payment->status !== 'PAYMENTTYPE.FR';
        })->sum('total_amount');
    }

    public function expenses()
    {
        return null;//$this->morphMany('App\Models\Expense', 'expensable');
    }

    public function copies()
    {
        return null;//$this->hasMany('App\Models\PurchaseOrderCopy', 'main_po_id');
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