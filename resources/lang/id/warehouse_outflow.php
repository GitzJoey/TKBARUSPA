<?php 

return [
    'index' => [
        'title' => 'Pengeluaran Barang',
        'page_title' => 'Pengeluaran Barang',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Pengeluaran Barang',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Pengeluaran Barang',
                'title_show' => 'Tampilkan Pengeluaran Barang',
                'title_edit' => 'Ubah Pengeluaran Barang',
            ],
        ],
        'table' => [
            'so_list' => [
                'header' => [
                    'code' => 'Kode',
                    'deliver' => 'Pengeluaran',
                    'customer' => 'Pelanggan',
                    'shipping_date' => 'Tanggal Pengiriman',
                    'status' => 'Status',
                ],
            ],
            'deliver_details_table' => [
                'header' => [
                    'product' => 'Product',
                    'deliver_date' => 'Tgl Kirim',
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
        'so_detail' => 'Detil Penjualan',
        'deliver_date' => 'Tgl Kirim',
        'driver_name' => 'Nama Supir',
        'vendor_trucking' => 'Penyedia Angkutan',
        'license_plate' => 'Plat Nomor',
        'remarks' => 'Keterangan',
        'deliver_no' => 'Pengiriman'
    ],
];