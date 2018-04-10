<?php 

return [
    'index' => [
        'title' => 'Supplier',
        'page_title' => 'Supplier',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Supplier Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Supplier',
                'title_show' => 'Show Supplier',
                'title_edit' => 'Edit Supplier',
            ],
        ],
        'tabs' => [
            'supplier' => 'Supplier Data',
            'pic' => 'Person In Charge',
            'bank_account' => 'Bank Account',
            'product' => 'Product List',
            'settings' => 'Settings',
        ],
        'table' => [
            'supplier_list' => [
                'header' => [
                    'name' => 'Name',
                    'address' => 'Address',
                    'tax_id' => 'TaxOutput ID',
                    'status' => 'Status',
                    'remarks' => 'Remarks',
                ],
            ],
            'table_phone' => [
                'header' => [
                    'provider' => 'Provider',
                    'number' => 'Number',
                    'remarks' => 'Remarks',
                ],
            ],
            'table_bank' => [
                'header' => [
                    'bank' => 'Bank',
                    'account_name' => 'Account Name',
                    'account_number' => 'Account Number',
                    'remarks' => 'Remarks',
                ],
            ],
            'table_prod' => [
                'header' => [
                    'type' => '',
                    'name' => '',
                    'short_code' => '',
                    'description' => '',
                    'remarks' => '',
                ],
            ],
        ],
    ],
    'fields' => [
        'search_supplier' => 'Search Supplier',
        'name' => 'Name',
        'code_sign' => 'Code Sign',
        'address' => 'Address',
        'city' => 'City',
        'phone' => 'Phone',
        'fax_num' => 'Fax',
        'tax_id' => 'TaxOutput ID',
        'status' => 'Status',
        'remarks' => 'Remarks',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'ic_num' => 'IC Number',
        'phone_number' => 'Phone Number',
        'bank' => 'Bank',
        'bank_account' => 'Bank Account',
        'payment_due_day' => 'Payment Due Day',
    ],
];