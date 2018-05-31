<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/15/2018
 * Time: 8:43 PM
 */

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Vinkla\Hashids\Facades\Hashids;

/**
 * App\Models\ReceiptDetail
 *
 * @property int $id
 * @property int $company_id
 * @property int $receipt_id
 * @property int $item_id
 * @property int $selected_product_unit_id
 * @property int $base_product_unit_id
 * @property float $conversion_value
 * @property float $brutto
 * @property float $base_brutto
 * @property float $netto
 * @property float $base_netto
 * @property float $tare
 * @property float $base_tare
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ReceiptDetail onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereBaseBrutto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereBaseNetto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereBaseProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereBaseTare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereBrutto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereConversionValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereNetto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereSelectedProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereTare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReceiptDetail whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ReceiptDetail withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ReceiptDetail withoutTrashed()
 * @mixin \Eloquent
 * @property-read mixed $base_product_unit_h_id
 * @property-read mixed $h_id
 * @property-read mixed $item_h_id
 * @property-read mixed $selected_product_units_h_id
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\ProductUnit $baseProductUnit
 * @property-read mixed $company_h_id
 * @property-read mixed $receipt_h_id
 * @property-read mixed $selected_product_unit_h_id
 * @property-read \App\Models\Receipt $receipt
 * @property-read \App\Models\ProductUnit $selectedProductUnit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Stock[] $stock
 * @property-read mixed $base_unit
 * @property-read mixed $selected_unit
 */
class ReceiptDetail extends Model
{
    use SoftDeletes;

    protected $table = 'receipt_details';

    protected $dates = ['deleted_at', 'receipt_date'];

    protected $fillable = [
        'conversion_value',
        'brutto',
        'base_brutto',
        'netto',
        'base_netto',
        'tare',
        'base_tare',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'receipt_id',
        'item_id',
        'selected_product_unit_id',
        'base_product_unit_id',
    ];

    protected $appends = [
        'hId',
        'companyHId',
        'receiptHId',
        'itemHId',
        'selectedProductUnitHId',
        'baseProductUnitHId',
        'selectedUnit',
        'baseUnit',
    ];

    protected $casts = [
        'conversion_value' => 'float',
        'brutto' => 'float',
        'base_brutto' => 'float',
        'netto' => 'float',
        'base_netto' => 'float',
        'tare' => 'float',
        'base_tare' => 'float',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function getReceiptHIdAttribute()
    {
        return HashIds::encode($this->attributes['receipt_id']);
    }

    public function getItemHIdAttribute()
    {
        return HashIds::encode($this->attributes['item_id']);
    }

    public function getSelectedProductUnitHIdAttribute()
    {
        return HashIds::encode($this->attributes['selected_product_unit_id']);
    }

    public function getBaseProductUnitHIdAttribute()
    {
        return HashIds::encode($this->attributes['base_product_unit_id']);
    }

    public function getBaseUnitAttribute()
    {
        $unit = '';

        $unit = $this->baseProductUnit->unit->unitName;

        return $unit;
    }

    public function getSelectedUnitAttribute()
    {
        $unit = '';

        $unit = $this->selectedProductUnit->unit->unitName;

        return $unit;
    }

    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt', 'receipt_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

    public function selectedProductUnit()
    {
        return $this->belongsTo('App\Models\ProductUnit', 'selected_product_unit_id');
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