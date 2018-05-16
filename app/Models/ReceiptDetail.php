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