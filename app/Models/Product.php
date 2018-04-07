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
 * App\Model\Product
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $product_type_id
 * @property string $name
 * @property string $short_code
 * @property string $description
 * @property string $image_path
 * @property string $status
 * @property string $remarks
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Model\Store $store
 * @property-read \App\Model\ProductType $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\ProductUnit[] $productUnits
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereStoreId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereProductTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereShortCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereImagePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereRemarks($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereDeletedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereDeletedAt($value)
 * @mixin \Eloquent
 * @property string $barcode
 * @property int $minimal_in_stock
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\ProductCategory[] $productCategories
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereBarcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereMinimalInStock($value)
 * @property int $minimum_in_stock
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product whereMinimumInStock($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Product withoutTrashed()
 * @property-read mixed $base_unit_symbol
 * @property-read \App\Models\Company $company
 * @property-read mixed $company_h_id
 * @property-read mixed $h_id
 * @property-read mixed $product_type_h_id
 * @property-read \App\Models\ProductType $productType
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