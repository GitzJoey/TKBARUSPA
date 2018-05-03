<?php 

return [
    'index' => [
        'title' => 'Product Type',
        'page_title' => 'Product Type',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Product Type Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Product Type',
                'title_show' => 'Show Product Type',
                'title_edit' => 'Edit Product Type',
            ],
        ],
        'table' => [
            'product_type_list' => [
                'header' => [
                    'name' => 'Name',
                    'short_code' => 'Short Code',
                    'description' => 'Description',
                    'status' => 'Status',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Name',
        'short_code' => 'Short Code',
        'description' => 'Description',
        'status' => 'Status',
    ],
];