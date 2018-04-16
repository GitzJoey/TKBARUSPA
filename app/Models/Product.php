<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/7/2016
 * Time: 12:17 AM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $company_id
 * @property int $product_type_id
 * @property string|null $name
 * @property string|null $short_code
 * @property string|null $barcode
 * @property string|null $description
 * @property string|null $image_filename
 * @property int $minimal_in_stock
 * @property string|null $status
 * @property string|null $remarks
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Company $company
 * @property-read mixed $base_unit_symbol
 * @property-read mixed $company_h_id
 * @property-read mixed $h_id
 * @property-read mixed $product_type_h_id
 * @property-read mixed $status_i18n
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductCategory[] $productCategories
 * @property-read \App\Models\ProductType $productType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductUnit[] $productUnits
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereImageFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereMinimalInStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereShortCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'products';

    protected $fillable = [
        'name',
        'short_code',
        'barcode',
        'description',
        'image_path',
        'minimal_in_stock',
        'status',
        'remarks'
    ];

   protected $hidden = [
        'id',
        'company_id',
        'product_type_id',
        'pivot',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $appends = [
        'hId',
        'companyHId',
        'productTypeHId',
        'baseUnitSymbol',
        'statusI18n',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getProductTypeHIdAttribute()
    {
        return HashIds::encode($this->attributes['product_type_id']);
    }

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function productType()
    {
        return $this->belongsTo('App\Models\ProductType', 'product_type_id');
    }

    public function productUnits()
    {
        return $this->hasMany('App\Models\ProductUnit');
    }

    public function productCategories()
    {
        return $this->hasMany('App\Models\ProductCategory');
    }

    public function getBaseUnitSymbolAttribute()
    {
        $ret = '';
        foreach ($this->productUnits as $produnit) {
            if ($produnit->is_base) {
                $ret = $produnit->unit->symbol;
            }
        }
        return $ret;
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