<?php 

return [
    'index' => [
        'title' => 'Peran',
        'page_title' => 'Peran',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Peran',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Peran',
                'title_show' => 'Tampilkan Peran',
                'title_edit' => 'Ubah Peran',
            ],
        ],
        'table' => [
            'role_list' => [
                'header' => [
                    'name' => 'Nama',
                    'display_name' => 'Nama Tampilan',
                    'description' => 'Deskripsi',
                    'permission' => 'Izin',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Nama',
        'display_name' => 'Nama Tampilan',
        'description' => 'Deskripsi',
        'permission' => 'Izin',
    ],

];