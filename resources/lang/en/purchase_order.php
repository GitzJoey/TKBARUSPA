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
                    'disc_total_pct' => 'Discount %',
                    'disc_total_value' => 'Discount',
                    'grandtotal' => 'TOTAL',
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
        'po_copy_code' => '',
    ],
];