<?php 

return [
    'index' => [
        'title' => 'Sales Order',
        'page_title' => 'Sales Order',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Sales Order Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Sales Order',
                'title_show' => 'Show Sales Order',
                'title_edit' => 'Edit Sales Order',
            ],
            'customer_panel' => [
                'title' => 'Customer',
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
            'remarks_panel' => [
                'title' => 'Remarks',
            ],
        ],
        'tabs' => [
            'remarks' => 'Remarks',
            'internal' => 'Internal',
            'private' => 'Private',
        ],
        'table' => [
            'list_table' => [
                'header' => [
                    'code' => 'Code',
                    'so_date' => 'Date',
                    'customer' => 'Customer',
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
                    'discount' => 'Discount',
                    'title' => 'Items',
                ],
            ],
            'expense_table' => [
                'header' => [
                    'name' => 'Name',
                    'type' => 'Type',
                    'internal_expense' => 'Internal Expense',
                    'remarks' => 'Remarks',
                    'amount' => 'Amount',
                    'title' => 'Expenses',
                ],
            ],
            'total_table' => [
                'header' => [
                    'subtotal' => 'Sub Total',
                    'discount' => 'Discount',
                    'grandtotal' => 'TOTAL',
                ],
            ],
        ],
    ],
    'fields' => [
        'customer_type' => 'Type',
        'customer_name' => 'Name',
        'customer_details' => 'Details',
        'so_code' => 'PO Code',
        'so_type' => 'Type',
        'so_created' => 'Date',
        'so_status' => 'Status',
        'shipping_date' => 'Shipping Date',
        'warehouse' => 'Warehouse',
        'vendor_trucking' => 'Vendor Trucking',
        'transaction_type' => 'Type',
        'transaction_in_stock' => 'In Stock',
        'transaction_in_stock_yes' => 'Yes',
        'transaction_in_stock_no' => 'No',
        'transaction_warehouse_name' => 'Warehouse',
        'transaction_in_stock_date' => 'Stock Date',
        'transaction_total' => 'Total',
    ],
];