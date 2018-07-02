<?php 

return [
    'index' => [
        'title' => 'Outflow',
        'page_title' => 'Outflow',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Outflow Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Outflow',
                'title_show' => 'Show Outflow',
                'title_edit' => 'Edit Outflow',
            ],
        ],
        'table' => [
            'so_list' => [
                'header' => [
                    'code' => 'Code',
                    'deliver' => 'Deliver',
                    'customer' => 'Customer',
                    'shipping_date' => 'Shipping Date',
                    'status' => 'Status',
                ],
            ],
            'deliver_details_table' => [
                'header' => [
                    'product' => 'Product',
                    'deliver_date' => 'Deliver Date',
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
        'so_detail' => 'SO Detail',
        'deliver_date' => 'Deliver Date',
        'driver_name' => 'Driver Name',
        'vendor_trucking' => 'Vendor Trucking',
        'license_plate' => 'License Plate',
        'remarks' => 'Remarks',
        'deliver_no' => 'Deliver'
    ],
];