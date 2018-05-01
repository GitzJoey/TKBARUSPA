<?php

return [
    'index' => [
        'title' => 'Truck Maintenance',
        'page_title' => 'Truck Maintenance',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Truck Maintenance Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Truck Maintenance',
                'title_show' => 'Show Truck Maintenance',
                'title_edit' => 'Edit Truck Maintenance',
            ],
        ],
        'table' => [
            'truck_maintenance_list' => [
                'header' => [
                    'plate_number' => 'Plate Number',
                    'maintenance_date' => 'Maintenance Date',
                    'maintenance_type' => 'Maintenance Type',
                    'cost' => 'Cost',
                    'odometer' => 'Odometer',
                    'remarks' => 'Remarks',
                ],
            ],
        ],
    ],
    'fields' => [
        'plate_number' => 'Plate Number',
        'maintenance_date' => 'Maintenance Date',
        'maintenance_type' => 'Maintenance Type',
        'cost' => 'Cost',
        'odometer' => 'Odometer',
        'remarks' => 'Remarks',
    ],
];