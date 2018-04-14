<?php 

return [
    'index' => [
        'title' => 'Vendor Trucking',
        'page_title' => 'Vendor Trucking',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Vendor Trucking Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Vendor Trucking',
                'title_show' => 'Show Vendor Trucking',
                'title_edit' => 'Edit Vendor Trucking',
            ],
        ],
        'table' => [
            'vendor_trucking_list' => [
                'header' => [
                    'name' => 'Name',
                    'address' => 'Address',
                    'phone' => 'Phone',
                    'tax_id' => 'Tax ID',
                    'status' => 'Status',
                    'remarks' => 'Remarks',
                ],
            ],
            'bank_list' => [
                'header' => [
                    'bank' => 'Bank',
                    'account_name' => 'Account Name',
                    'account_number' => 'Account Number',
                    'remarks' => 'Remarks',
                ],
            ],
        ],
    ],
    'fields' => [
        'name' => 'Name',
        'address' => 'Address',
        'phone' => 'Phone',
        'tax_id' => 'Tax ID',
        'status' => 'Status',
        'remarks' => 'Remarks',
        'bank' => 'Bank Accounts',
    ],
];