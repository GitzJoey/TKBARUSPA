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
        ],
    ],
];