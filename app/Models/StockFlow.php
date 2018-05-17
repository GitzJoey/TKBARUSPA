<?php

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\StockFlow
 *
 * @property int $id
 * @property int $company_id
 * @property int $owner_id
 * @property string $owner_type
 * @property int $warehouse_id
 * @property int $product_id
 * @property int $base_product_unit_id
 * @property int $display_product_unit_id
 * @property int $stock_id
 * @property float $quantity_in
 * @property float $quantity_out
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $base_product_unit_h_id
 * @property-read mixed $company_h_id
 * @property-read mixed $display_product_unit_h_id
 * @property-read mixed $h_id
 * @property-read mixed $product_h_id
 * @property-read mixed $warehouse_h_id
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\StockFlow onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereBaseProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereDisplayProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereQuantityIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereQuantityOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StockFlow whereWarehouseId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\StockFlow withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\StockFlow withoutTrashed()
 * @mixin \Eloquent
 */
class StockFlow extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'stock_flows';

    protected $fillable = [
        'quantity_in',
        'quantity_out',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'owner_id',
        'owner_type',
        'warehouse_id',
        'product_id',
        'base_product_unit_id',
        'display_product_unit_id',
        'stock_id',
    ];

    protected $appends = [
        'hId',
        'companyHId',
        'warehouseHId',
        'productHId',
        'baseProductUnitHId',
        'displayProductUnitHId',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getWarehouseHIdAttribute()
    {
        return HashIds::encode($this->attributes['warehouse_id']);
    }

    public function getProductHIdAttribute()
    {
        return HashIds::encode($this->attributes['product_id']);
    }

    public function getBaseProductUnitHIdAttribute()
    {
        return HashIds::encode($this->attributes['base_product_unit_id']);
    }

    public function getDisplayProductUnitHIdAttribute()
    {
        return HashIds::encode($this->attributes['display_product_unit_id']);
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
