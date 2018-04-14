<?php 

return [
    'index' => [
        'title' => 'Tingkatan Harga',
        'page_title' => 'Tingkatan Harga',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Tingkatan Harga',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Tingkatan Harga',
                'title_show' => 'Tampilan Tingkatan Harga',
                'title_edit' => 'Ubah Tingkatan Harga',
            ],
        ],
        'table' => [
            'price_level_list' => [
                'header' => [
                    'type' => 'Tipe',
                    'weight' => 'Berat',
                    'name' => 'Nama',
                    'description' => 'Deskripsi',
                    'value' => 'Nilai',
                    'status' => 'Status',
                ],
            ],
        ],
    ],
    'fields' => [
        'type' => 'Tipe',
        'weight' => 'Berat',
        'name' => 'Nama',
        'description' => 'Deskripsi',
        'incval' => 'Nilai Kenaikan',
        'pctval' => 'Nilai Persentase',
        'status' => 'Status',
    ],
];