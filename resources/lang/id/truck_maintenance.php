<?php

return [
    'index' => [
        'title' => 'Pemeliharaan Truk',
        'page_title' => 'Pemeliharaan Truk',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Pemeliharaan Truk',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Pemeliharaan Truk',
                'title_show' => 'Tampilkan Pemeliharaan Truk',
                'title_edit' => 'Ubah Pemeliharaan Truk',
            ],
        ],
        'table' => [
            'truck_maintenance_list' => [
                'header' => [
                    'plate_number' => 'Plat Nomor',
                    'maintenance_date' => 'Tgl Pemeliharaan',
                    'maintenance_type' => 'Tipe Pemeliharaan',
                    'cost' => 'Ongkos',
                    'odometer' => 'Odometer',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
    ],
    'fields' => [
        'plate_number' => 'Plat Nomor',
        'maintenance_date' => 'Tgl Pemeliharaan',
        'maintenance_type' => 'Tipe Pemeliharaan',
        'cost' => 'Ongkos',
        'odometer' => 'Odometer',
        'remarks' => 'Keterangan',
    ],
];