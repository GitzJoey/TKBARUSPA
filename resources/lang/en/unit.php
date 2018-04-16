<?php 

return [
    'index' => [
        'title' => 'Unit',
        'page_title' => 'Unit',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Unit Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Unit',
                'title_show' => 'Show Unit',
                'title_edit' => 'Edit Unit',
            ],
        ],
        'table' => [
            'unit_list' => [
                'header' => [
                    'name' => 'Name',
                    'symbol' => 'Symbol',
                    'status' => 'Status',
                    'remarks' => 'Remarks',
                ],
            ],
        ],
        'fields' => [
            'name' => 'Name',
            'symbol' => 'Symbol',
            'status' => 'Status',
            'remarks' => 'Remarks',
        ],
    ],
];