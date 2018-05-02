<?php 

return [
    'index' => [
        'title' => 'Phone Provider',
        'page_title' => 'Phone Provider',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Phone Provider Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Phone Provider',
                'title_show' => 'Show Phone Provider',
                'title_edit' => 'Edit Phone Provider',
            ],
        ],
        'table' => [
            'phone_provider_list' => [
                'header' => [
                    'name' => 'Name',
                    'short_name' => 'Short Name',
                    'prefix' => 'Prefix',
                    'status' => 'Status',
                    'remarks' => 'Remarks',
                ],
            ],
            'prefix' => [
                'header' => [
                    'prefix' => 'Prefix'
                ],
            ],
        ],
        'fields' => [
            'name' => 'Name',
            'short_name' => 'Short Name',
            'symbol' => 'Symbol',
            'status' => 'Status',
            'remarks' => 'Remarks',
            'prefix' => 'Prefix',
        ],
    ],
];