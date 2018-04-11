<?php 

return [
    'index' => [
        'title' => 'Supplier',
        'page_title' => 'Supplier',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Supplier',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Supplier',
                'title_show' => 'Tampilkan Supplier',
                'title_edit' => 'Ubah Supplier',
            ],
            'pic' => [
                'title' => 'Penanggungjawab'
            ],
        ],
        'tab' => [
            'supplier' => 'Data Supplier',
            'pic' => 'Penanggungjawab',
            'bank_accounts' => 'Akun Bank',
            'product' => 'Produk',
            'settings' => 'Settings',
        ],
        'table' => [
            'supplier_list' => [
                'header' => [
                    'name' => 'Nama',
                    'address' => 'Alamat',
                    'tax_id' => 'NPWP ID',
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
            'table_prod' => [
                'header' => [
                    'type' => 'Tipe',
                    'name' => 'Nama',
                    'short_code' => 'Kode Singkat',
                    'description' => 'Deskripsi',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
    ],
    'fields' => [
        'search_supplier' => 'Cari Supplier',
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
        'payment_due_day' => 'Tenggat Bayar',
    ],
];