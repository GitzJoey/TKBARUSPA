<?php

namespace App\Model;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'expenses';

    protected $fillable = [
        'name', 'type', 'amount', 'remarks', 'is_internal_expense'
    ];

    protected $hidden = [
        'company_id',
        'expensable_id',
        'expensable_type',
    ];

    protected $appends = [
        'hId',
        'companyHId',
        'typeI18n'
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getTypeI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['type']);
    }

    public function expensable()
    {
        // SalesOrder | PurchaseOrder
        return $this->morphTo();
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
