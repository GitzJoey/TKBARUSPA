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