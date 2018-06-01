<?php 

return [
    'index' => [
        'title' => 'Penyedia Jasa Truk',
        'page_title' => 'Penyedia Jasa Truk',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Penyedia Jasa Truk',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Penyedia Jasa Truk',
                'title_show' => 'Tampilkan Penyedia Jasa Truk',
                'title_edit' => 'Ubah Penyedia Jasa Truk',
            ],
        ],
        'table' => [
            'vendor_trucking_list' => [
                'header' => [
                    'name' => 'Nama',
                    'address' => 'Alamat',
                    'phone' => 'Telepon',
                    'tax_id' => 'NPWP No.',
                    'status' => 'Status',
                    'remarks' => 'Keterangan',
                ],
            ],
            'bank_list' => [
                'header' => [
                    'bank' => 'Bank',
                    'account_name' => 'Nama Rekening',
                    'account_number' => 'Nomor Rekening',
                    'remarks' => 'Catatan',
                ],
            ],
            'truck_list' => [
                'header' => [
                    'type' => 'Tipe',
                    'plate_number' => 'Nomor Plat',
                    'inspection_date' => 'Tanggal Inspeksi',
                    'driver' => 'Sopir',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Nama',
        'address' => 'Alamat',
        'phone' => 'Telepon',
        'tax_id' => 'NPWP No.',
        'status' => 'Status',
        'remarks' => 'Keterangan',
        'bank' => 'Akun Bank',
        'truck_list' => 'Daftar Truk',
        'license_plate' => 'Plat Nomor',
        'driver' => 'Sopir',
        'maintenance_by' => 'Pemeliharaan Oleh',
        'company' => 'Perusahaan'
    ],
];