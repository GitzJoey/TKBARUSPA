<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/30/2018
 * Time: 12:25 PM
 */

return [
    'index' => [
        'title' => 'Stock Opname',
        'page_title' => 'Stock Opname',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Stock Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Stock Opname',
                'title_show' => 'Show Stock Opname',
                'title_edit' => 'Edit Stock Opname',
            ],
        ],
        'table' => [
            'stock_list' => [
                'header' => [
                    'warehouse' => 'Warehouse',
                    'product' => 'Product',
                    'opname_date' => 'Opname Date',
                    'current_quantity' => 'Current Quantity',
                ],
            ],
        ],
        'fields' => [
            'warehouse' => 'Warehouse',
            'product' => 'Product',
            'opname_date' => 'Opname Date',
            'is_match' => 'Match',
            'current_quantity' => 'Current Quantity',
            'adjusted_quantity' => 'Adjusted Quantity',
            'reason' => 'Reason',
        ],
    ],
];