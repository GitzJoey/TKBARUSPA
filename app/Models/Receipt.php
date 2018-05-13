<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Vinkla\Hashids\Facades\Hashids;

/**
 * App\Models\Receipt
 *
 * @property int $id
 * @property int $company_id
 * @property int $item_id
 * @property int $selected_product_unit_id
 * @property int $base_product_unit_id
 * @property float $conversion_value
 * @property \Carbon\Carbon|null $receipt_date
 * @property float $brutto
 * @property float $base_brutto
 * @property float $netto
 * @property float $base_netto
 * @property float $tare
 * @property float $base_tare
 * @property string|null $license_plate
 * @property string|null $article_code
 * @property string|null $driver_name
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\ProductUnit $baseProductUnit
 * @property-read mixed $base_product_unit_h_id
 * @property-read mixed $h_id
 * @property-read mixed $item_h_id
 * @property-read mixed $selected_product_units_h_id
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\ProductUnit $selectedProductUnit
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Receipt onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereArticleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereBaseBrutto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereBaseNetto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereBaseProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereBaseTare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereBrutto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereConversionValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereDriverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereLicensePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereNetto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereReceiptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereSelectedProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereTare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Receipt withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Receipt withoutTrashed()
 * @mixin \Eloquent
 */
class Receipt extends Model
{
    use SoftDeletes;

    protected $table = 'receipts';

    protected $dates = ['deleted_at', 'receipt_date'];

    protected $fillable = [
        'license_plate',
        'driver_name',
        'receipt_date',
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
        'item_id',
        'selected_product_unit_id',
        'base_product_unit_id',
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
        'itemHId',
        'selectedProductUnitsHId',
        'baseProductUnitHId',
        'companyHId',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getItemHIdAttribute()
    {
        return HashIds::encode($this->attributes['item_id']);
    }

    public function getSelectedProductUnitsHIdAttribute()
    {
        return HashIds::encode($this->attributes['selected_product_unit_id']);
    }

    public function getBaseProductUnitHIdAttribute()
    {
        return HashIds::encode($this->attributes['base_product_unit_id']);
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
