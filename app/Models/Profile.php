<?php

/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/7/2016
 * Time: 12:06 AM
 */

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Profile
 *
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Profile onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Profile withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Profile withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $address
 * @property string|null $ic_num
 * @property string|null $image_filename
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereIcNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereImageFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Profile whereUserId($value)
 */
class Profile extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'profiles';

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'ic_num',
        'image_filename',
    ];

    protected $hidden = [
        'id',
        'owner_id',
        'owner_type',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at'
    ];

    protected $appends = [
        'hId',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function phoneNumbers()
    {
        return $this->hasMany('App\Models\PhoneNumber');
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
