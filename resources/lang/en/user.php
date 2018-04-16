<?php 

return [
    'index' => [
        'title' => 'User',
        'page_title' => 'User',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'User Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create User',
                'title_show' => 'Show User',
                'title_edit' => 'Edit User',
            ],
        ],
        'table' => [
            'user_list' => [
                'header' => [
                    'name' => 'Name',
                    'email' => 'Email',
                    'roles' => 'Role',
                    'company' => 'Company',
                    'active' => 'Active',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Name',
        'email' => 'Email',
        'company' => 'Company',
        'roles' => 'Role',
        'password' => 'Password',
        'retype_password' => 'Retype Password',
        'password_confirmation' => 'Retype Password',
    ],
];
