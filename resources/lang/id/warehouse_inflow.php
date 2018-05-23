<?php 

return [
    'index' => [
        'title' => 'Pemasukan Barang',
        'page_title' => 'Pemasukan Barang',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Pemasukan Barang',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Pemasukan Barang',
                'title_show' => 'Tampilkan Pemasukan Barang',
                'title_edit' => 'Ubah Pemasukan Barang',
            ],
        ],
        'table' => [
            'po_list' => [
                'header' => [
                    'code' => 'Kode',
                    'receipt' => 'Penerimaan',
                    'supplier' => 'Supplier',
                    'shipping_date' => 'Tanggal Pengiriman',
                    'status' => 'Status',
                ],
            ],
            'receipt_details_table' => [
                'header' => [
                    'receipt_date' => 'Tgl Terima',
                    'unit' => 'Unit',
                    'brutto' => 'Brutto',
                    'netto' => 'Netto',
                    'tare' => 'Tare',
                ]
            ],
            'item_table' => [
                'header' => [
                    'product_name' => 'Nama Produk',
                    'unit' => 'Satuan',
                    'brutto' => 'Bruto',
                    'netto' => 'Netto',
                    'tare' => 'Tare',
                ],
            ],
            'expense_table' => [
                'header' => [
                    'title' => 'Biaya-Biaya',
                    'name' => 'Nama',
                    'type' => 'Tipe',
                    'internal_expense' => 'Internal',
                    'remarks' => 'Keterangan',
                    'amount' => 'Jumlah',
                    'total' => 'Total',
                ],
            ],
        ],
    ],
    'fields' => [
        'po_detail' => 'Detil Pembelian',
        'receipt_date' => 'Tgl Terima',
        'driver_name' => 'Nama Supir',
        'vendor_trucking' => 'Penyedia Angkutan',
        'license_plate' => 'Plat Nomor',
        'remarks' => 'Keterangan',
        'receipt_no' => 'Penerimaan'
    ],
];