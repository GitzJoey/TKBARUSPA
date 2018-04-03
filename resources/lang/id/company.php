<?php 

return [
    'index' => [
        'title' => 'Perusahaan',
        'page_title' => 'Perusahaan',
        'page_title_desc' => '',
        'table' => [
            'company_list' => [
                'title' => 'Daftar Perusahaan',
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
        'field' => [
            'title' => '',
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
        'address' => 'Alamat',
        'phone' => 'Telepon',
        'fax' => 'Fax',
        'tax_id' => 'NPWP No.',
        'status' => 'Status',
        'default' => 'Utama',
        'frontweb' => 'Web Utama',
        'remarks' => 'Keterangan',
    ],
];