<?php 

return [
    'index' => [
        'title' => 'Provider Telepon',
        'page_title' => 'Provider Telepon',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Provider Telepon',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Provider Telepon',
                'title_show' => 'Tampilkan Provider Telepon',
                'title_edit' => 'Ubah Provider Telepon',
            ],
        ],
        'table' => [
            'phone_provider_list' => [
                'header' => [
                    'name' => 'Nama',
                    'short_name' => 'Singkatan',
                    'prefix' => 'Prefix',
                    'status' => 'Status',
                    'remarks' => 'Keterangan',
                ],
            ],
            'prefix' => [
                'header' => [
                    'prefix' => 'Prefix'
                ],
            ],
        ],
        'fields' => [
            'name' => 'Nama',
            'short_name' => 'Singkatan',
            'symbol' => 'Symbol',
            'status' => 'Status',
            'remarks' => 'Keterangan',
            'prefix' => 'Prefix',
        ],
    ],
];