<?php 

return [
    'index' => [
        'title' => 'Pembelian',
        'page_title' => 'Pembelian',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Pembelian',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Pembelian',
                'title_show' => 'Tampilkan Pembelian',
                'title_edit' => 'Ubah Pembelian',
            ],
        ],
        'table' => [
            'list_table' => [
                'header' => [
                    'code' => 'Kode',
                    'po_date' => 'Tanggal',
                    'supplier' => 'Supplier',
                    'shipping_date' => 'Tanggal Kirim',
                    'status' => 'Status',
                ],
            ],
        ],
    ],
];