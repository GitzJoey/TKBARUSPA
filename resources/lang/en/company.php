<?php 

return [
    'index' => [
        'title' => 'Company',
        'page_title' => 'Company',
        'page_title_desc' => '',
        'panel' => [
            'list_panel' => [
                'title' => 'Company Lists',
            ],
            'crud_panel' => [
                'title_create' => 'Create Company',
                'title_show' => 'Show Company',
                'title_edit' => 'Edit Company',
            ],
        ],
        'table' => [
            'company_list' => [
                'header' => [
                    'name' => 'Name',
                    'address' => 'Address',
                    'tax_id' => 'Tax ID',
                    'default' => 'Default',
                    'frontweb' => 'Front Web',
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
        'tabs' => [
            'company' => 'Company Data',
            'bank_account' => 'Bank Account',
            'settings' => 'Settings',
        ],
        'fields' => [
            'bank_id' => 'Bank',
            'account_name' => 'Account Name',
            'account_number' => 'Account Number',
        ],
    ],
    'fields' => [
        'name' => 'Name',
        'logo' => 'Logo',
        'address' => 'Address',
        'phone' => 'Phone',
        'fax' => 'Fax',
        'tax_id' => 'Tax ID',
        'status' => 'Status',
        'default' => 'Default',
        'frontweb' => 'Front Web',
        'remarks' => 'Remarks',
        'date_format' => 'Date Format',
        'time_format' => 'Time Format',
        'thousand_separator' => 'Thousand Separator',
        'decimal_separator' => 'Decimal Separator',
        'decimal_digit' => 'Decimal Digit',
        'comma' => 'Comma',
        'dot' => 'Dot',
        'space' => 'Space',
        'color_theme' => 'Color Theme',
    ],
];