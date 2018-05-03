<?php 

return [
    'index' => [
        'title' => 'Tipe Produk',
        'page_title' => 'Tipe Produk',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Tipe Produk',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Tipe Produk',
                'title_show' => 'Tampilan Tipe Produk',
                'title_edit' => 'Ubah Tipe Produk',
            ],
        ],
        'table' => [
            'product_type_list' => [
                'header' => [
                    'name' => 'Nama',
                    'short_code' => 'Kode Pendek',
                    'description' => 'Deskripsi',
                    'status' => 'Status',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Nama',
        'short_code' => 'Kode Pendek',
        'description' => 'Deskripsi',
        'status' => 'Status',
    ],
];