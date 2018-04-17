<?php 

return [
    'index' => [
        'title' => 'Warehouse',
        'page_title' => 'Warehouse',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Warehouse Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Warehouse',
                'title_show' => 'Show Warehouse',
                'title_edit' => 'Edit Warehouse',
            ],
        ],
        'table' => [
            'warehouse_list' => [
                'header' => [
                    'name' => 'Name',
                    'address' => 'Address',
                    'phone_num' => 'Phone Number',
                    'status' => 'Status',
                    'remarks' => 'Remarks',
                ],
            ],
            'section_table' => [
                'header' => [
                    'name' => 'Name',
                    'position' => 'Position',
                    'capacity' => 'Capacity',
                    'capacity_unit' => 'Unit',
                    'remarks' => 'Remarks',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Name',
        'address' => 'Address',
        'phone_num' => 'Phone Number',
        'status' => 'Status',
        'remarks' => 'Remarks',
        'section' => 'Sections',
    ],
];