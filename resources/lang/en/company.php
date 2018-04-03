<?php 

return [
    'index' => [
        'title' => 'Company',
        'page_title' => 'Company',
        'page_title_desc' => '',
        'table' => [
            'company_list' => [
                'title' => 'Company Lists',
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
        ],
        'field' => [
            'title' => '',
        ],
        'tabs' => [
            'company' => 'Company Data',
            'bank_account' => 'Bank Account',
            'currencies' => 'Currencies',
            'settings' => 'Settings',
        ],
    ],
    'fields' => [
        'name' => 'Name',
        'address' => 'Address',
        'phone' => 'Phone',
        'fax' => 'Fax',
        'tax_id' => 'TaxOutput ID',
        'status' => 'Status',
        'default' => 'Default',
        'frontweb' => 'Front Web',
        'remarks' => 'Remarks',
    ],
];