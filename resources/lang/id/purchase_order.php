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
            'supplier_panel' => [
                'title' => 'Supplier',
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
            'item_table' => [
                'header' => [
                    'product_name' => 'Produk',
                    'quantity' => 'Quantity',
                    'unit' => 'Satuan',
                    'price_unit' => 'Harga',
                    'total_price' => 'Total Harga',
                    'discount_percent' => 'Diskon %',
                    'discount_nominal' => 'Diskon Nominal',
                ],
            ],
        ],
    ],
    'fields' => [
        'supplier_type' => 'Tipe',
        'supplier_name' => 'Nama',
        'supplier_details' => 'Detil',
        'po_code' => 'Kode',
        'po_copy_code' => 'Duplikat',
        'po_type' => 'Tipe',
        'po_created' => 'Tanggal PO',
        'shipping_date' => 'Tgl Pengiriman',
        'warehouse' => 'Gudang',
        'vendor_trucking' => 'Layanan Angkutan',
    ],
];