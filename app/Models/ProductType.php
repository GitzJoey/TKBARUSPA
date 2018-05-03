<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/10/2016
 * Time: 1:40 PM
 */

namespace App\Models;

use Auth;
use Lang;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\CompanyFilter;

/**
 * App\Models\ProductType
 *
 * @property-read \App\Models\Company $company
 * @property-read mixed $h_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductType withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int $company_id
 * @property string|null $name
 * @property string|null $short_code
 * @property string|null $description
 * @property string|null $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereShortCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductType whereUpdatedBy($value)
 */
class ProductType extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'product_types';

    protected $fillable = [
        'name',
        'short_code',
        'description',
        'status'
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
        'companyHId',
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

    public function getStatusI18nAttribute()
    {
        return Lang::get('lookup.'.$this->attributes['status']);
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'product_type_id');
    }

    public function stocks()
    {
        return null; //$this->hasManyThrough('App\Model\Stock', 'App\Model\Product');
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