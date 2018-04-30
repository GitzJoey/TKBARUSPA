<?php
/**
 * Created by PhpStorm.
 * User: TKBARU
 * Date: 4/18/2018
 * Time: 12:44 PM
 */

namespace App\Utils;

use Auth;
use Config;

class AppConfig
{
    public static function get()
    {
        $separator = '|';
        $sessionTimeout = '0';
        $dateFormat = Config::get('const.DATETIME_FORMAT.DATE_FORMAT');
        $timeFormat = Config::get('const.DATETIME_FORMAT.TIME_FORMAT');
        $thousandSeparator = Config::get('const.DIGIT_GROUP_SEPARATOR');
        $decimalSeparator = Config::get('const.DECIMAL_SEPARATOR');
        $decimalDigit = Config::get('const.DECIMAL_DIGIT');
        $databaseDateFormat = Config::get('const.DATETIME_FORMAT.DATABSE_DATE');
        $databaseTimeFormat = Config::get('const.DATETIME_FORMAT.DATABSE_TIME');

        if (!is_null(Config::get('session.lifetime'))) {
            $sessionTimeout = Config::get('session.lifetime');
        }

        if (!is_null(Auth::user())) {
            if (!empty(Auth::user()->company)) {
                $dateFormat = Auth::user()->company->date_format;
                $timeFormat = Auth::user()->company->time_format;
                $thousandSeparator = Auth::user()->company->thousand_separator;
                $decimalSeparator = Auth::user()->company->decimal_separator;
                $decimalDigit = Auth::user()->company->decimal_digit;
            }
        }

        $result =
            $sessionTimeout         //[0]
            .$separator
            .$dateFormat            //[1]
            .$separator
            .$timeFormat            //[2]
            .$separator
            .$thousandSeparator     //[3]
            .$separator
            .$decimalSeparator      //[4]
            .$separator
            .$decimalDigit          //[5]
            .$separator
            .$databaseDateFormat    //[6]
            .$separator
            .$databaseTimeFormat    //[7]
        ;

        return $result;
    }
}