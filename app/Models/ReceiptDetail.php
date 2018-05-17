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

}