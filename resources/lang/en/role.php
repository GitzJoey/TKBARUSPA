<?php 

return [
    'index' => [
        'title' => 'Role',
        'page_title' => 'Role',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Role Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Role',
                'title_show' => 'Show Role',
                'title_edit' => 'Edit Role',
            ],
        ],
        'table' => [
            'role_list' => [
                'header' => [
                    'name' => 'Name',
                    'display_name' => 'Display Name',
                    'description' => 'Description',
                    'permission' => 'Permission',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Name',
        'display_name' => 'Display Name',
        'description' => 'Description',
        'permission' => 'Permission',
    ],

];