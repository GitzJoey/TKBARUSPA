<?php

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Expense
 *
 * @property int $id
 * @property int $company_id
 * @property int $expensable_id
 * @property string|null $expensable_type
 * @property string|null $name
 * @property string|null $type
 * @property float $amount
 * @property int $is_internal_expense
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $expensable
 * @property-read mixed $company_h_id
 * @property-read mixed $h_id
 * @property-read mixed $type_i18n
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Expense onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereExpensableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereExpensableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereIsInternalExpense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Expense withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Expense withoutTrashed()
 * @mixin \Eloquent
 */
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
