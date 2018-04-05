<?php 

return [
    'index' => [
        'title' => 'Perusahaan',
        'page_title' => 'Perusahaan',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Perusahaan',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Perusahaan',
                'title_show' => 'Tampilkan Perusahaan',
                'title_edit' => 'Ubah Perusahaan',
            ],
        ],
        'table' => [
            'company_list' => [
                'header' => [
                    'name' => 'Nama',
                    'address' => 'Alamat',
                    'tax_id' => 'NPWP No.',
                    'default' => 'Utama',
                    'frontweb' => 'Web Utama',
                    'status' => 'Status',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
        'tabs' => [
            'company' => 'Data Perusahaan',
            'bank_account' => 'Rekening Bank',
            'currencies' => 'Mata Uang',
            'settings' => 'Pengaturan',
        ],
    ],
    'fields' => [
        'name' => 'Nama',
        'logo' => 'Logo',
        'address' => 'Alamat',
        'phone' => 'Telepon',
        'fax' => 'Fax',
        'tax_id' => 'NPWP No.',
        'status' => 'Status',
        'default' => 'Utama',
        'frontweb' => 'Web Utama',
        'remarks' => 'Keterangan',
        'date_format' => 'Format Tanggal',
        'time_format' => 'Format Jam',
        'thousand_separator' => 'Pemisah Ribuan',
        'decimal_separator' => 'Pemisah Desimal',
        'decimal_digit' => 'Desimal Digit',
        'comma' => 'Koma',
        'dot' => 'Titik',
        'space' => 'Spasi',
        'color_theme' => 'Warna Tema',
    ],
];