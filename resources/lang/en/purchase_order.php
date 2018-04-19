<?php 

return [
    'index' => [
        'title' => 'Purchase Order',
        'page_title' => 'Purchase Order',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Purchase Order Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Purchase Order',
                'title_show' => 'Show Purchase Order',
                'title_edit' => 'Edit Purchase Order',
            ],
            'supplier_panel' => [
                'title' => 'Supplier',
            ],
            'detail_panel' => [
                'title' => 'Detail',
            ],
            'shipping_panel' => [
                'title' => 'Shipping',
            ],
            'transaction_panel' => [
                'title' => 'Transactions',
            ],
        ],
        'table' => [
            'list_table' => [
                'header' => [
                    'code' => 'Code',
                    'po_date' => 'Date',
                    'supplier' => 'Supplier',
                    'shipping_date' => 'Shipping Date',
                    'status' => 'Status',
                ],
            ],
            'item_table' => [
                'header' => [
                    'product_name' => 'Product Name',
                    'quantity' => 'Quantity',
                    'unit' => 'Unit',
                    'price_unit' => 'Price',
                    'total_price' => 'Total Price',
                    'discount_percent' => 'Discount %',
                    'discount_nominal' => 'Discount Nominal',
                ],
            ],
        ],
    ],
    'fields' => [
        'supplier_type' => 'Type',
        'supplier_name' => 'Name',
        'supplier_details' => 'Details',
        'po_code' => 'PO Code',
        'po_type' => 'Type',
        'po_created' => 'Date',
        'po_status' => 'Status',
        'shipping_date' => 'Shipping Date',
        'warehouse' => 'Warehouse',
        'vendor_trucking' => 'Vendor Trucking',
    ],
];