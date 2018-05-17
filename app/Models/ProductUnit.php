<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/10/2016
 * Time: 12:57 PM
 */

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

/**
 * App\Models\ProductUnit
 *
 * @property int $id
 * @property int $company_id
 * @property int $product_id
 * @property int $unit_id
 * @property int|null $is_base
 * @property float|null $conversion_value
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $h_id
 * @property-read mixed $product_h_id
 * @property-read mixed $unit_h_id
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Unit $unit
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductUnit onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereConversionValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereIsBase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductUnit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductUnit withoutTrashed()
 * @mixin \Eloquent
 * @property int|null $display
 * @property-read mixed $company_h_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductUnit whereDisplay($value)
 */
class ProductUnit extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'product_units';

    protected $fillable = [
        'is_base',
        'display',
        'conversion_value',
        'remarks'
    ];

    protected $hidden = [
        'id',
        'product_id',
        'unit_id',
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
        'productHId',
        'unitHId',
        'companyHId',
    ];

    protected $casts = [
        'conversion_value' => 'float',
        'is_base' => 'integer',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getProductHIdAttribute()
    {
        return HashIds::encode($this->attributes['product_id']);;
    }

    public function getUnitHIdAttribute()
    {
        return HashIds::encode($this->attributes['unit_id']);;
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);;
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'unit_id');
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