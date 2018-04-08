<?php 

return [
    'index' => [
        'title' => 'Produk',
        'page_title' => 'Produk',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Produk',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Produk',
                'title_show' => 'Tampilan Produk',
                'title_edit' => 'Ubah Produk',
            ],
        ],
        'table' => [
            'product_list' => [
                'header' => [
                    'type' => 'Tipe',
                    'name' => 'Nama',
                    'short_code' => 'Kode',
                    'description' => 'Deskripsi',
                    'status' => 'Status',
                    'remarks' => 'Keterangan',
                ],
            ],
            'category_table' => [
                'header' => [
                    'code' => 'Kode',
                    'name' => 'Nama',
                    'description' => 'Keterangan',
                ],
            ],
            'product_unit_table' => [
                'header' => [
                    'unit' => 'Satuan',
                    'is_base' => 'Satuan Dasar',
                    'conversion_value' => 'Nilai Tukar',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
    ],
    'fields' => [
        'type' => 'Tipe',
        'category' => 'Kategori',
        'name' => 'Nama',
        'short_code' => 'Kode',
        'description' => 'Deskripsi',
        'barcode' => 'Barcode',
        'minimal_in_stock' => 'Minimal Di Stok',
        'status' => 'Status',
        'remarks' => 'Keterangan',
        'product_unit' => 'Satuan',
    ],
];