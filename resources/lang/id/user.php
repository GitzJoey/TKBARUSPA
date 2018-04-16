<?php 

return [
    'index' => [
        'title' => 'Pengguna',
        'page_title' => 'Pengguna',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'header' => [
                    'title' => 'Daftar Pengguna',
                ],
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Pengguna',
                'title_show' => 'Tampilkan Pengguna',
                'title_edit' => 'Ubah Pengguna',
            ],
        ],
        'table' => [
            'header' => [
                'name' => 'Nama',
                'email' => 'Email',
                'roles' => 'Peran',
                'company' => 'Perusahaan',
            ],
        ],
    ],
    'fields' => [
        'name' => 'Nama',
        'email' => 'Email',
        'company' => 'Perusahan',
        'roles' => 'Peran',
        'password' => 'Password',
        'retype_password' => 'Ulangi Password',
        'password_confirmation' => 'Ulangi Password',
    ],
];
