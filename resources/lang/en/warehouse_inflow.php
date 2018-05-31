<?php 

return [
    'index' => [
        'title' => 'Inflow',
        'page_title' => 'Inflow',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Inflow Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Inflow',
                'title_show' => 'Show Inflow',
                'title_edit' => 'Edit Inflow',
            ],
        ],
        'table' => [
            'po_list' => [
                'header' => [
                    'code' => 'Code',
                    'receipt' => 'Receipt',
                    'supplier' => 'Supplier',
                    'shipping_date' => 'Shipping Date',
                    'status' => 'Status',
                ],
            ],
            'receipt_details_table' => [
                'header' => [
                    'product' => 'Product',
                    'receipt_date' => 'Receipt Date',
                    'unit' => 'Unit',
                    'brutto' => 'Brutto',
                    'netto' => 'Netto',
                    'tare' => 'Tare',
                ]
            ],
            'item_table' => [
                'header' => [
                    'product_name' => 'Product Name',
                    'unit' => 'Unit',
                    'brutto' => 'Brutto',
                    'netto' => 'Netto',
                    'tare' => 'Tare',
                ],
            ],
            'expense' => [
                'header' => [
                    'title' => 'Expenses',
                    'name' => 'Name',
                    'type' => 'Type',
                    'internal_expense' => 'Internal',
                    'remarks' => 'Remarks',
                    'amount' => 'Amount',
                    'total' => 'Total',
                ],
            ],
        ],
    ],
    'fields' => [
        'po_detail' => 'PO Detail',
        'receipt_date' => 'Receipt Date',
        'driver_name' => 'Driver Name',
        'vendor_trucking' => 'Vendor Trucking',
        'license_plate' => 'License Plate',
        'remarks' => 'Remarks',
        'receipt_no' => 'Receipt'
    ],
];