<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/7/2016
 * Time: 9:46 AM
 */

namespace App\Models;

use Auth;
use Config;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Company
 *
 * @property-read mixed $date_format
 * @property-read mixed $date_time_format
 * @property-read mixed $numeral_format
 * @property-read mixed $time_format
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company withoutTrashed()
 * @mixin \Eloquent
 */
class Company extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'phone_num',
        'fax_num',
        'tax_id',
        'status',
        'is_default',
        'frontweb',
        'image_filename',
        'remarks',
        'date_format',
        'time_format',
        'thousand_separator',
        'decimal_separator',
        'decimal_digit',
        'ribbon'
    ];

    protected $hidden = [
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $appends = [
        'numeralFormat',
        'dateFormat',
        'timeFormat',
        'dateTimeformat'
    ];

    public function getNumeralFormatAttribute()
    {
        $thousandSeparator = is_null($this->attributes['thousand_separator']) ? ',':$this->attributes['thousand_separator'];
        $decimalSeparator = is_null($this->attributes['decimal_separator']) ? '.':$this->attributes['decimal_separator'];
        $decimalDigit = '';

        if ($this->attributes['decimal_digit'] == 0) {
            $decimalDigit = '00';
        } else {
            for ($i = 0; $i < $this->attributes['decimal_digit']; $i++) {
                $decimalDigit .= '0';
            }
        }

        return '0'.$thousandSeparator.'0'.'['.$decimalSeparator.']'.$decimalDigit;
    }

    public function getDateFormatAttribute()
    {
        if (is_null($this->attributes['date_format']) || empty($this->attributes['date_format'])) {
            return Config::get('const.DATETIME_FORMAT.PHP_DATE');
        } else {
            return $this->attributes['date_format'];
        }
    }

    public function getTimeFormatAttribute()
    {
        if (is_null($this->attributes['time_format']) || empty($this->attributes['time_format'])) {
            return Config::get('const.DATETIME_FORMAT.PHP_TIME');
        } else {
            return $this->attributes['time_format'];
        }
    }

    public function getDateTimeFormatAttribute()
    {
        return $this->getDateFormatAttribute() . ' ' . $this->getTimeFormatAttribute();
    }

    public function hId()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function products()
    {
        return null;//$this->hasMany('App\Models\Product');
    }

    public function purchaseOrders()
    {
        return null;//$this->hasMany('App\Models\PurchaseOrder');
    }

    public function bankAccounts()
    {
        return null;//$this->morphMany('App\Models\BankAccount', 'owner');
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