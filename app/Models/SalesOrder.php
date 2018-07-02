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

class SalesOrder extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at', 'so_created', 'shipping_date'];

    protected $table = 'sales_orders';

    protected $fillable = [
        'code',
        'so_type',
        'so_created',
        'shipping_date',
        'customer_type',
        'walk_in_cust',
        'walk_in_cust_detail',
        'so_type',
        'status',
        'remarks',
        'internal_remarks',
        'private_remarks',
        'disc_percent',
        'disc_value',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'customer_id',
        'vendor_trucking_id',
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
        'customerHId',
        'vendorTruckingHId',
        'statusI18n',
        'customerTypeI18n',
        'soTypeI18n',
    ];

    protected $casts = [
        'discount' => 'float',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getCustomerHIdAttribute()
    {
        if ($this->attributes['customer_id'] == 0) return '';
        return HashIds::encode($this->attributes['customer_id']);
    }

    public function getVendorTruckingHIdAttribute()
    {
        return $this->attributes['vendor_trucking_id'] == 0 ? '':HashIds::encode($this->attributes['vendor_trucking_id']);
    }

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function getCustomerTypeI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['customer_type']);
    }

    public function getSoTypeI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['so_type']);
    }

    public function items()
    {
        return $this->morphMany('App\Models\Item', 'itemable');
    }

    public function delivers()
    {
        return $this->hasMany('App\Models\Deliver', 'so_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function vendorTrucking()
    {
        return $this->belongsTo('App\Models\VendorTrucking', 'vendor_trucking_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function payments()
    {
        return null; //$this->morphMany('App\Model\Payment', 'payable');
    }

    public function expenses(){
        return $this->morphMany('App\Models\Expense', 'expensable');
    }

    public function copies()
    {
        return null;//return $this->hasMany('App\Model\SalesOrderCopy', 'main_so_id');
    }

    public function totalAmount()
    {
        return 0;
    }

    public function totalAmountPaid()
    {
        return 0;
    }

    public function totalAmountUnpaid()
    {
        return 0;
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
