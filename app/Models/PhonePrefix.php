<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 12/20/2016
 * Time: 9:07 AM
 */

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\PhonePrefix
 *
 * @property int $id
 * @property int $phone_provider_id
 * @property string|null $prefix
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $h_id
 * @property-read mixed $phone_provider_h_id
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhonePrefix onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix wherePhoneProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PhonePrefix whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhonePrefix withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PhonePrefix withoutTrashed()
 * @mixin \Eloquent
 */
class PhonePrefix extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'phone_prefixes';

    protected $fillable = [
        'prefix'
    ];

    protected $hidden = [
        'id',
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
        'phoneProviderHId',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getPhoneProviderHIdAttribute()
    {
        return HashIds::encode($this->attributes['phone_provider_id']);
    }

    public function providers()
    {
        $this->belongsTo('App\Models\PhoneProvider', 'phone_provider_id');
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