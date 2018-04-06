<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/7/2016
 * Time: 9:46 AM
 */

namespace App\Models;

use Auth;
use Lang;
use Config;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Utils\PHP2Moment;

/**
 * App\Models\Company
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Company withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $name
 * @property string|null $address
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $phone_num
 * @property string|null $fax_num
 * @property string|null $tax_id
 * @property string|null $status
 * @property string|null $is_default
 * @property string|null $frontweb
 * @property string|null $image_filename
 * @property string|null $remarks
 * @property string|null $thousand_separator
 * @property string|null $decimal_separator
 * @property int $decimal_digit
 * @property string|null $ribbon
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereDateFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereDecimalDigit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereFaxNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereFrontweb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereImageFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company wherePhoneNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereRibbon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereThousandSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereTimeFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereUpdatedBy($value)
 * @property-read mixed $date_display_format
 * @property-read mixed $date_time_display_format
 * @property-read mixed $numeral_display_format
 * @property-read mixed $time_display_format
 * @property string|null $date_format
 * @property string|null $time_format
 * @property-read mixed $default_i18n
 * @property-read mixed $frontweb_i18n
 * @property-read mixed $h_id
 * @property-read mixed $moment_date_format
 * @property-read mixed $moment_date_time_format
 * @property-read mixed $moment_time_format
 * @property-read mixed $numeral_format
 * @property-read mixed $php_date_format
 * @property-read mixed $php_date_time_format
 * @property-read mixed $php_time_format
 * @property-read mixed $status_i18n
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
        'statusI18n',
        'frontwebI18n',
        'defaultI18n',
        'numeralFormat',
        'phpDateFormat',
        'phpTimeFormat',
        'phpDateTimeFormat',
        'momentDateFormat',
        'momentTimeFormat',
        'momentDateTimeFormat'
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

    public function getPhpDateFormatAttribute()
    {
        if (is_null($this->attributes['date_format']) || empty($this->attributes['date_format'])) {
            return Config::get('const.DATETIME_FORMAT.PHP_DATE');
        } else {
            return $this->attributes['date_format'];
        }
    }

    public function getPhpTimeFormatAttribute()
    {
        if (is_null($this->attributes['time_format']) || empty($this->attributes['time_format'])) {
            return Config::get('const.DATETIME_FORMAT.PHP_TIME');
        } else {
            return $this->attributes['time_format'];
        }
    }

    public function getPhpDateTimeFormatAttribute()
    {
        return $this->getPhpDateFormatAttribute() . ' ' . $this->getPhpTimeFormatAttribute();
    }

    public function getMomentDateFormatAttribute()
    {
        return PHP2Moment::convertToMoment($this->attributes['date_format']);
    }

    public function getMomentTimeFormatAttribute()
    {
        return PHP2Moment::convertToMoment($this->attributes['time_format']);
    }

    public function getMomentDateTimeFormatAttribute()
    {
        return $this->getMomentDateFormatAttribute() . ' ' . $this->getMomentTimeFormatAttribute();
    }

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function getDefaultI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['is_default']);
    }

    public function getFrontwebI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['frontweb']);
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
        return $this->morphMany('App\Models\BankAccount', 'owner');
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