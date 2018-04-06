<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/7/2016
 * Time: 12:25 AM
 */

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Bank
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $name
 * @property string|null $short_name
 * @property string|null $branch
 * @property string|null $branch_code
 * @property string|null $status
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BankAccount[] $bankAccounts
 * @property-read mixed $bank_full_name
 * @property-read mixed $h_id
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Bank onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereBranchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bank whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Bank withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Bank withoutTrashed()
 * @mixin \Eloquent
 */
class Bank extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'banks';

    protected $fillable = [
        'name',
        'short_name',
        'branch',
        'branch_code',
        'status',
        'remarks'
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
        'bankFullName',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getBankFullNameAttribute()
    {
        return $this->attributes['name'] . ' ' . '(' . $this->attributes['short_name'] . ')';
    }

    public function bankAccounts()
    {
        return $this->hasMany('App\Models\BankAccount', 'bank_id');
    }

    public function Giros()
    {
        return null;//$this->hasMany('App\Model\Giro', 'bank_id');
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