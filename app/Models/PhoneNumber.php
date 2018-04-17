<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/10/2016
 * Time: 11:05 AM
 */

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\PhoneNumber
 *
 * @property int $id
 * @property int $profile_id
 * @property int $phone_provider_id
 * @property string|null $number
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $h_id
 * @property-read mixed $phone_provider_h_id
 * @property-read \App\Models\PhoneProvider $provider
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhoneNumber onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber wherePhoneProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhoneNumber whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhoneNumber withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhoneNumber withoutTrashed()
 * @mixin \Eloquent
 */
class PhoneNumber extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'phone_numbers';

    protected $fillable = ['number', 'status', 'remarks'];

    protected $hidden = [
        'id',
        'profile_id',
        'phone_provider_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $appends = [
        'hId',
        'phoneProviderHId'
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getPhoneProviderHIdAttribute()
    {
        return HashIds::encode($this->attributes['phone_provider_id']);
    }

    public function profile()
    {
        $this->belongsTo('App\Models\Profile');
    }

    public function provider()
    {
        return $this->belongsTo('App\Models\PhoneProvider', 'phone_provider_id');
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