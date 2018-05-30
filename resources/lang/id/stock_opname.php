<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/30/2018
 * Time: 12:25 PM
 */

return [
    'index' => [
        'title' => 'Stok Opname',
        'page_title' => 'Stok Opname',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Stok Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Stok Opname',
                'title_show' => 'Tampilkan Stok Opname',
            ],
        ],
        'table' => [
            'stock_list' => [
                'header' => [
                    'warehouse' => 'Gudang',
                    'product' => 'Produk',
                    'opname_date' => 'Tanggal Opname',
                    'current_quantity' => 'Jumlah Sekarang',
                ],
            ],
        ],
        'fields' => [
            'warehouse' => 'Gudang',
            'product' => 'Produk',
            'opname_date' => 'Tanggal Opname',
            'is_match' => 'Sama',
            'current_quantity' => 'Jumlah Sekarang',
            'adjusted_quantity' => 'Jumlah Penyesuaian',
            'reason' => 'Alasan',
        ],
    ],
];