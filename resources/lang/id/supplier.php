<?php 

return [
    'index' => [
        'title' => '',
        'page_title' => '',
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
        ],
        'tab' => [
            'supplier' => 'Data Supplier',
            'pic' => 'Penanggungjawab',
            'bank_account' => 'Akun Bank',
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
        'search_supplier' => '',
        'name' => 'Nama',
        'address' => 'Alamat',
        'city' => 'Kota',
        'phone' => 'Telepon',
        'tax_id' => 'NPWP ID',
        'status' => 'Status',
        'remarks' => 'Keterangan',
        'first_name' => 'Nama Depan',
        'last_name' => 'Nama Belakang',
        'ic_num' => 'KTP',
        'phone_number' => 'No Telp',
        'bank' => 'Bank',
        'bank_account' => 'Akun Bank',
        'payment_due_day' => 'Tenggat Bayar',
    ],
];