<?php 

return [
    'index' => [
        'title' => 'Pengguna',
        'page_title' => 'Pengguna',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Pengguna',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Pengguna',
                'title_show' => 'Tampilkan Pengguna',
                'title_edit' => 'Ubah Pengguna',
            ],
        ],
        'table' => [
            'user_list' => [
                'header' => [
                    'name' => 'Nama',
                    'email' => 'Email',
                    'roles' => 'Peran',
                    'company' => 'Perusahaan',
                    'active' => 'Active',
                ],
            ],
            'table_phone' => [
                'header' => [
                    'provider' => 'Provider',
                    'number' => 'Nomor',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
    ],
    'fields' => [
        'first_name' => 'Nama Depan',
        'last_name' => 'Nama Belakang',
        'address' => 'Alamat',
        'ic_num' => 'No. Identitas',
        'photo' => 'Photo',
        'phone_number' => 'No Telp',
        'email' => 'Email',
        'company' => 'Perusahan',
        'roles' => 'Peran',
        'active' => 'Aktif',
        'password' => 'Password',
        'retype_password' => 'Ulangi Password',
        'password_confirmation' => 'Ulangi Password',
    ],
];
