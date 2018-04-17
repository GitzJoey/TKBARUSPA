<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/10/2016
 * Time: 12:44 AM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

/**
 * App\Models\PriceLevel
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $type
 * @property int $weight
 * @property string|null $name
 * @property string|null $description
 * @property int|null $increment_value
 * @property int|null $percentage_value
 * @property string|null $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $h_id
 * @property-read mixed $status_i18n
 * @property-read mixed $type_i18n
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PriceLevel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereIncrementValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel wherePercentageValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceLevel whereWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PriceLevel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PriceLevel withoutTrashed()
 * @mixin \Eloquent
 */
class PriceLevel extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'price_levels';

    protected $fillable = [
        'type',
        'weight',
        'name',
        'description',
        'increment_value',
        'percentage_value',
        'status',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $appends = [
        'hId',
        'typeI18n',
        'statusI18n',
    ];

    public function getTypeI18nAttribute()
    {
        return Lang::get('lookup.' . $this->attributes['type']);
    }

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.' . $this->attributes['status']);
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