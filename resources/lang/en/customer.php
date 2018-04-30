<?php 

return [
    'index' => [
        'title' => 'Customer',
        'page_title' => 'Customer',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Customer Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Customer',
                'title_show' => 'Show Customer',
                'title_edit' => 'Edit Customer',
            ],
            'pic' => [
                'title' => 'Person In Charge',
            ],
        ],
        'tabs' => [
            'customer' => 'Customer Lists',
            'pic' => 'Person In Charge',
            'bank_accounts' => 'Bank Account',
            'expenses' => 'Expenses',
            'settings' => 'Settings',
        ],
        'table' => [
            'customer_list' => [
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
                    'account_number' => 'Account Number',
                    'remarks' => 'Remarks',
                    'account_name' => 'Account Name',
                ],
            ],
            'table_expense' => [
                'header' => [
                    'name' => 'Name',
                    'type' => 'Type',
                    'amount' => 'Amount',
                    'internal_expense' => 'Internal',
                    'remarks' => 'Remarks',
                ],
            ],
        ],
    ],
    'fields' => [
        'search_customer' => 'Search Customer',
        'name' => 'Name',
        'code_sign' => 'Sign Code',
        'address' => 'Address',
        'city' => 'City',
        'phone' => 'Phone',
        'fax_num' => 'Fax',
        'tax_id' => 'TAX ID',
        'status' => 'Status',
        'remarks' => 'Remarks',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'ic_num' => 'IC Number',
        'phone_number' => 'Phone Number',
        'bank' => 'Bank',
        'bank_account' => 'Bank Account',
        'payment_due_day' => 'Payment Due Day',
    ],
];