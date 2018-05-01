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

    'RANDOMSTRINGRANGE' => [
        'ALPHABET' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
        'NUMERIC' => [3,4,7,9],
    ],

    'TRXCODE' => [
        'LENGTH' => 6,
    ],

    'DATETIME_FORMAT' => [
        'PHP_DATE' => 'd M Y',
        'PHP_TIME' => 'H:i:s',
        'PHP_DATETIME' => 'd M Y H:i:s',
        'DATABASE_DATETIME' => 'Y-m-d H:i:s',
        'DATABASE_DATE' => 'Y-m-d',
        'DATABASE_TIME' => 'H:i:s',
    ],

    'DIGIT_GROUP_SEPARATOR' => ',',

    'DECIMAL_SEPARATOR' => '.',

    'DECIMAL_DIGIT' => 2,

    'PAGINATION' => 10,
];