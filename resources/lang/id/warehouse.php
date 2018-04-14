<?php 

return [
    'index' => [
        'title' => 'Gudang',
        'page_title' => 'Gudang',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Gudang',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Gudang',
                'title_show' => 'Tampilkan Gudang',
                'title_edit' => 'Ubah Gudang',
            ],
        ],
        'table' => [
            'warehouse_list' => [
                'header' => [
                    'name' => 'Nama',
                    'address' => 'Alamat',
                    'phone_num' => 'Telepon',
                    'status' => 'Status',
                    'remarks' => 'Keterangan',
                ],
            ],
            'section_table' => [
                'header' => [
                    'name' => 'Nama',
                    'position' => 'Posisi',
                    'capacity' => 'Kapasitas',
                    'capacity_unit' => 'Satuan',
                    'remarks' => 'Keterangan',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Nama',
        'address' => 'Alamat',
        'phone_num' => 'Telepon',
        'status' => 'Status',
        'remarks' => 'Keterangan',
        'section' => 'Lot',
    ],
];