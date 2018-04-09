<?php 

return [
    'index' => [
        'title' => 'Product',
        'page_title' => 'Product',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Product Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Product',
                'title_show' => 'Show Product',
                'title_edit' => 'Edit Product',
            ],
        ],
        'table' => [
            'product_list' => [
                'header' => [
                    'type' => 'Type',
                    'name' => 'Name',
                    'short_code' => 'Short Code',
                    'description' => 'Description',
                    'status' => 'Status',
                    'remarks' => 'Remarks',
                ],
            ],
            'category_table' => [
                'header' => [
                    'code' => 'Code',
                    'name' => 'Name',
                    'description' => 'Description',
                ],
            ],
            'product_unit_table' => [
                'header' => [
                    'unit' => 'Unit',
                    'is_base' => 'Is Base',
                    'conversion_value' => 'Conversion Value',
                    'remarks' => 'Remarks',
                ],
            ],
        ],
    ],
    'fields' => [
        'type' => 'Type',
        'category' => 'Category',
        'name' => 'Name',
        'logo' => 'Logo',
        'short_code' => 'Short Code',
        'description' => 'Description',
        'barcode' => 'Barcode',
        'minimal_in_stock' => 'Minimal In Stock',
        'status' => 'Status',
        'remarks' => 'Remarks',
        'product_unit' => 'Unit',
        'search_product' => 'Search Product',
    ],
];