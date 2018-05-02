<?php 

return [
    'index' => [
        'title' => 'Pelanggan',
        'page_title' => 'Pelanggan',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Pelanggan',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Pelanggan',
                'title_show' => 'Tampilkan Pelanggan',
                'title_edit' => 'Ubah Pelanggan',
            ],
            'pic' => [
                'title' => 'Penanggungjawab',
            ],
        ],
        'tabs' => [
            'customer' => 'Data Pelanggan',
            'pic' => 'Penanggungjawab',
            'bank_accounts' => 'Akun Bank',
            'expenses' => 'Biaya',
            'settings' => 'Settings',
        ],
        'table' => [
            'customer_list' => [
                'header' => [
                    'name' => 'Nama',
                    'address' => 'Alamat',
                    'tax_id' => 'TaxOutput ID',
                    'status' => 'Status',
                    'remarks' => 'Keterangan',
                ],
            ],
            'table_phone' => [
                'header' => [
                    'provider' => 'Provider',
                    'number' => 'Nomor',
                    'remarks' => 'Keterangan',
                ],
            ],
            'table_bank' => [
                'header' => [
                    'bank' => 'Bank',
                    'account_number' => 'Nomor Akun',
                    'remarks' => 'Keterangan',
                    'account_name' => 'Nama Akun',
                ],
            ],
            'table_expense' => [
                'header' => [
                    'name' => 'Nama',
                    'type' => 'Tipe',
                    'amount' => 'Jumlah',
                    'internal_expense' => 'Internal',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
    ],
    'fields' => [
        'search_customer' => 'Cari Pelanggan',
        'name' => 'Nama',
        'code_sign' => 'Alias',
        'address' => 'Alamat',
        'city' => 'Kota',
        'phone' => 'Telepon',
        'fax_num' => 'Fax',
        'tax_id' => 'NPWP ID',
        'status' => 'Status',
        'remarks' => 'Keterangan',
        'first_name' => 'Nama Depan',
        'last_name' => 'Nama Belakang',
        'email' => 'Email',
        'ic_num' => 'KTP',
        'phone_number' => 'No Telp',
        'bank' => 'Bank',
        'bank_account' => 'Akun Bank',
        'price_level' => 'Tingkatan Harga',
        'payment_due_day' => 'Tenggat Bayar',
    ],
];