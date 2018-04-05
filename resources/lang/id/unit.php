<?php 

return [
    'index' => [
        'title' => 'Satuan',
        'page_title' => 'Satuan',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Satuan',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Satuan',
                'title_show' => 'Tampilkan Satuan',
                'title_edit' => 'Ubah Satuan',
            ],
        ],
        'table' => [
            'unit_list' => [
                'header' => [
                    'name' => 'Nama',
                    'symbol' => 'Symbol',
                    'status' => 'Status',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
        'fields' => [
            'name' => 'Nama',
            'symbol' => 'Symbol',
            'status' => 'Status',
            'remarks' => 'Keterangan',
        ],
    ],
];