<?php

/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/7/2016
 * Time: 12:22 AM
 */

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

/**
 * App\Models\BankAccount
 *
 * @property int $id
 * @property int $company_id
 * @property int $bank_id
 * @property int $owner_id
 * @property string|null $account_name
 * @property string|null $account_number
 * @property string|null $remarks
 * @property string|null $owner_type
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Bank $bank
 * @property-read mixed $bank_h_id
 * @property-read mixed $h_id
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankAccount onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankAccount whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankAccount withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankAccount withoutTrashed()
 * @mixin \Eloquent
 */
class BankAccount extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'bank_accounts';

    protected $fillable = [
        'account_name',
        'account_number',
        'remarks'
    ];

    protected $hidden = [
        'id',
        'bank_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
        'owner_type'
    ];

    protected $appends = [
        'hId',
        'bankHId',
    ];

    public function getHIdAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }

    public function getBankHIdAttribute()
    {
        return Hashids::encode($this->attributes['bank_id']);
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank', 'bank_id');
    }

    public function owner(){
        // Supplier | Customer | Company
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
