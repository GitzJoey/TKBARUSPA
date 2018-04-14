<?php 

return [
    'index' => [
        'title' => 'Price Level',
        'page_title' => 'Price Level',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Create Price Level',
            ],
            'crud_panel' => [
                'title_create' => 'Create Price Level',
                'title_show' => 'Show Price Level',
                'title_edit' => 'Edit Price Level',
            ],
        ],
        'table' => [
            'price_level_list' => [
                'header' => [
                    'type' => 'Type',
                    'weight' => 'Weight',
                    'name' => 'Name',
                    'description' => 'Description',
                    'value' => 'Value',
                    'status' => 'Status',
                ],
            ],
        ],
    ],
    'fields' => [
        'type' => 'Type',
        'weight' => 'Weight',
        'name' => 'Name',
        'description' => 'Description',
        'incval' => 'Increment Value',
        'pctval' => 'Percentage Value',
        'status' => 'Status',
    ],
];