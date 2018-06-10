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

class DeliverDetail extends Model
{
    use SoftDeletes;

    protected $table = 'deliver_details';

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
        'deliver_id',
        'item_id',
        'selected_product_unit_id',
        'base_product_unit_id',
    ];

    protected $appends = [
        'hId',
        'companyHId',
        'deliverHId',
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

    public function getDeliverHIdAttribute()
    {
        return HashIds::encode($this->attributes['deliver_id']);
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

    public function deliver()
    {
        return $this->belongsTo('App\Models\Deliver', 'deliver_id');
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