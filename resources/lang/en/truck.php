<?php 

return [
    'index' => [
        'title' => 'Truck',
        'page_title' => 'Truck',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Truck Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Truk',
                'title_show' => 'Show Truk',
                'title_edit' => 'Edit Truk',
            ],
        ],
        'table' => [
            'truck_list' => [
                'header' => [
                    'type' => 'Type',
                    'plate_number' => 'Plate Number',
                    'inspection_date' => 'Inspection Date',
                    'driver' => 'Driver',
                    'status' => 'Status',
                    'remarks' => 'Remarks',
                ],
            ],
        ],
        'fields' => [
            'type' => 'Type',
            'plate_number' => 'Plate Number',
            'inspection_date' => 'Inspection Date',
            'driver' => 'Driver',
            'status' => 'Status',
            'remarks' => 'Remarks',
        ],
    ],
]