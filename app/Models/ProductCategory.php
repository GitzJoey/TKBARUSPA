<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 1/25/2017
 * Time: 11:06 PM
 */

namespace App\Models;

use Auth;

use App\Traits\CompanyFilter;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProductCategory
 *
 * @property-read \App\Models\Company $company
 * @property-read mixed $h_id
 * @property-read \App\Models\Product $product
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductCategory onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductCategory withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int $company_id
 * @property int $product_id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $description
 * @property int $level
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductCategory whereUpdatedBy($value)
 */
class ProductCategory extends Model
{
    use SoftDeletes;

    use CompanyFilter;

    protected $dates = ['deleted_at'];

    protected $table = 'product_categories';

    protected $fillable = [
        'code',
        'name',
        'description',
        'level',
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
    ];

    public function getHIdAttribute()
    {
        return HashIds::encode($this->attributes['id']);
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
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