<?php

namespace App\Models;

use Lang;
use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
	use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone_number',
        'fax_num',
        'tax_id',
        'payment_due_day',
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

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function personsInCharge()
    {
        return $this->morphMany('App\Models\Profile', 'owner');
    }

    public function bankAccounts()
    {
        return $this->morphMany('App\Models\BankAccount', 'owner');
    }

    public function priceLevel()
    {
        return $this->belongsTo('App\Models\PriceLevel', 'price_level_id');
    }

    public function company()
    {
        return null;//$this->belongsTo('App\Models\Company', 'company_id');
    }

    public function sales_orders()
    {
        return null;//$this->hasMany('App\Models\SalesOrder');
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
