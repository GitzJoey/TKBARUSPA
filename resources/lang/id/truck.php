<?php 

return [
    'index' => [
        'title' => 'Truk',
        'page_title' => 'Truk',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Truk',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Truk',
                'title_show' => 'Tampilkan Truk',
                'title_edit' => 'Ubah Truk',
            ],
        ],
        'table' => [
            'unit_list' => [
                'header' => [
                    'type' => 'Tipe',
                    'plate_number' => 'Nomor Plat',
                    'inspection_date' => 'Tanggal Inspeksi',
                    'driver' => 'Sopir'
                    'status' => 'Status',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
        'fields' => [
            'type' => 'Tipe',
            'plate_number' => 'Nomor Plat',
            'inspection_date' => 'Tanggal Inspeksi',
            'driver' => 'Sopir'
            'status' => 'Status',
            'remarks' => 'Keterangan',
        ],
    ],
];