<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/10/2016
 * Time: 11:06 AM
 */

namespace App\Models;

use Lang;
use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\PhoneProvider
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_name
 * @property string|null $status
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $full_name
 * @property-read mixed $h_id
 * @property-read mixed $status_i18n
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhonePrefix[] $prefixes
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhoneProvider onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneProvider whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhoneProvider withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhoneProvider withoutTrashed()
 * @mixin \Eloquent
 */
class PhoneProvider extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "phone_providers";

    protected $fillable = [
        'name',
        'short_name',
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
        'fullName',
        'statusI18n'
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getFullNameAttribute()
    {
        return $this->attributes['name'] . '(' . $this->attributes['short_name'] . ')';
    }

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function phoneNumbers()
    {
        $this->hasMany('App\Models\PhoneNumber');
    }

    public function prefixes()
    {
        return $this->hasMany('App\Models\PhonePrefix');
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