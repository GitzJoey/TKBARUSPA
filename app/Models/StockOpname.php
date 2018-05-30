<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Vinkla\Hashids\Facades\Hashids;

use App\Traits\CompanyFilter;

class StockOpname extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'stock_opnames';

    protected $fillable = [
        'opname_date',
        'is_match',
        'previous_quantity',
        'adjusted_quantity',
        'reason'
    ];

    protected $hidden = [
        'id',
        'stock_id',
        'company_id',
    ];

    protected $appends = [
        'hId',
        'stockHId',
        'companyHId',
    ];

    protected $casts = [
        'is_match' => 'integer',
        'previous_quantity' => 'float',
        'adjusted_quantity' => 'float',
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function getStockHIdAttribute()
    {
        return HashIds::encode($this->attributes['stock_id']);
    }

    public function getCompanyHIdAttribute()
    {
        return HashIds::encode($this->attributes['company_id']);
    }

    public function stock()
    {
        return $this->belongsTo('App\Model\Stock');
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
