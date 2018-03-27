<?php

/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/10/2016
 * Time: 11:28 AM
 */

return [
    'SESSION' => [
        'USER_SO_LIST' => 'userSOs',
    ],

    'PASSPORT_TOKEN_NAME' => [
        'WEBAPI' => 'WEBAPI_TOKEN',
        'API' => 'API_TOKEN',
        'EXTERNAL' => 'EXTERNAL_API_TOKEN',
    ],

    'RANDOMSTRINGRANGE' => [
        'ALPHABET' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
        'NUMERIC' => [3,4,7,9],
    ],

    'TRXCODE' => [
        'LENGTH' => 6,
    ],

    'DATETIME_FORMAT' => [
        'PHP_DATE' => 'd M Y',
        'PHP_TIME' => 'G:H A',
        'PHP_DATETIME' => 'd M Y G:H A',
        'DATABASE_DATETIME' => 'Y-m-d H:i:s',
        'JS_DATE' => 'YYYY-MM-DD',
        'JS_TIME' => 'hh:mm:ss A',
        'JS_DATETIME' => 'YYYY-MM-DD hh:mm A'
    ],

    'DIGIT_GROUP_SEPARATOR' => ',',

    'DECIMAL_SEPARATOR' => '.',

    'DECIMAL_DIGIT' => 2,

    'PAGINATION' => 10,


    'SETTING_KEY' => [

    ],
];