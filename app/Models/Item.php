<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/10/2016
 * Time: 12:07 AM
 */

namespace App\Models;

use Auth;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Item
 *
 * @property int $id
 * @property int $company_id
 * @property int $product_id
 * @property int $stock_id
 * @property int $selected_unit_id
 * @property int $base_unit_id
 * @property int|null $itemable_id
 * @property string $itemable_type
 * @property float $conversion_value
 * @property float $quantity
 * @property float $price
 * @property float $to_base_quantity
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $h_id
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $itemable
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ProductUnit $selectedProductUnit
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Item onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereBaseUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereConversionValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereItemableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereItemableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereSelectedUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereToBaseQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Item withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Item withoutTrashed()
 * @mixin \Eloquent
 */
class Item extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'items';

    protected $fillable = [
        'quantity'
    ];

    protected $hidden = [
        'id',
        'itemable_type'
    ];

    protected $appends = [
        'hId'
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function selectedProductUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit', 'selected_product_unit_id');
    }

    public function itemable()
    {
        // SalesOrder | SalesOrderCopy | PurchaseOrder | PurchaseOrderCopy
        return $this->morphTo();
    }

    public function baseProductUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit', 'base_product_unit_id');
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
