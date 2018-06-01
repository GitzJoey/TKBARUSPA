<?php 

return [
    'index' => [
        'title' => 'Penjualan',
        'page_title' => 'Penjualan',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Daftar Penjualan',
            ],
            'crud_panel' => [
                'title_create' => 'Tambah Penjualan',
                'title_show' => 'Tampilkan Penjualan',
                'title_edit' => 'Ubah Penjualan',
            ],
            'customer_panel' => [
                'title' => 'Pelanggan',
            ],
            'detail_panel' => [
                'title' => 'Detil',
            ],
            'shipping_panel' => [
                'title' => 'Pengiriman',
            ],
            'transaction_panel' => [
                'title' => 'Transaksi',
            ],
            'remarks_panel' => [
                'title' => 'Keterangan',
            ],
        ],
        'tabs' => [
            'remarks' => 'Keterangan',
            'internal' => 'Internal',
            'private' => 'Privat',
        ],
        'table' => [
            'list_table' => [
                'header' => [
                    'code' => 'Kode',
                    'so_date' => 'Tanggal',
                    'customer' => 'Pelanggan',
                    'shipping_date' => 'Tanggal Kirim',
                    'status' => 'Status',
                ],
            ],
            'item_table' => [
                'header' => [
                    'product_name' => 'Produk',
                    'quantity' => 'Quantity',
                    'unit' => 'Satuan',
                    'price_unit' => 'Harga',
                    'total_price' => 'Total Harga',
                    'discount' => 'Diskon',
                    'title' => 'Barang',
                ],
            ],
            'expense_table' => [
                'header' => [
                    'name' => 'Nama',
                    'type' => 'Tipe',
                    'remarks' => 'Keterangan',
                    'amount' => 'Jumlah',
                    'internal_expense' => 'Internal',
                    'title' => 'Biaya-Biaya',
                ],
            ],
            'total_table' => [
                'header' => [
                    'subtotal' => 'Sub Total',
                    'discount' => 'Diskon',
                    'grandtotal' => 'TOTAL',
                ],
            ],
        ],
    ],
    'fields' => [
        'customer_type' => 'Tipe',
        'customer_name' => 'Nama',
        'customer_details' => 'Detil',
        'so_code' => 'Kode',
        'so_type' => 'Tipe',
        'so_created' => 'Tanggal SO',
        'shipping_date' => 'Tgl Pengiriman',
        'warehouse' => 'Gudang',
        'vendor_trucking' => 'Layanan Angkutan',
        'so_status' => 'Status',
    ],
];