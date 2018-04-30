<?php
/**
 * Created by PhpStorm.
 * User: TKBARU
 * Date: 4/15/2018
 * Time: 7:07 PM
 */
return [
    'groups' => [
        'role' => [
            'api.get.settings.role.*',
            'api.post.settings.role.*',
        ],
        'truck' => [
            'api.get.lookup.*',
            'api.get.truck.*',
            'api.post.truck.*',
        ],
        'unit' => [
            'api.get.lookup.*',
            'api.get.settings.unit.*',
            'api.post.settings.unit.*',
        ],
        'company' => [
            'api.get.lookup.*',
            'api.get.bank.*',
            'api.get.settings.company.*',
            'api.post.settings.company.*'
        ],
        'purchase_order' => [
            'api.get.lookup.*',
            'api.get.po.*',
            'api.get.supplier.*',
            'api.get.warehouse.*',
            'api.get.product.*',
            'api.get.truck.vendor_trucking.*',
            'api.post.po.*',
        ],
        'phone_provider' => [
            'api.get.lookup.*',
            'api.get.settings.phone_provider.*',
            'api.post.settings.phone_provider.*',
        ],
        'bank' => [
            'api.get.lookup.*',
            'api.get.settings.bank.*',
            'api.post.settings.bank.*',
        ],
        'warehouse' => [
            'api.get.lookup.*',
            'api.get.warehouse.*',
            'api.post.warehouse.*',
            'api.get.settings.unit.*',
        ],
        'price_level' => [
            'api.get.lookup.*',
            'api.get.price.price_level.*',
            'api.post.price.price_level.*',
        ],
        'product' => [
            'api.get.lookup.*',
            'api.get.product.*',
            'api.post.product.*',
            'api.get.product.product_type.*',
            'api.get.settings.unit.*',
        ],
        'product_type' => [
            'api.get.lookup.*',
            'api.get.product.product_type.*',
            'api.post.product.product_type.*',
        ],
        'supplier' => [
            'api.get.lookup.*',
            'api.get.bank.*',
            'api.get.product.*',
            'api.get.supplier.*',
            'api.get.settings.phone_provider.*',
            'api.post.supplier.*',
        ],
        'customer' => [
            'api.get.lookup.*',
            'api.get.bank.*',
            'api.get.customer.*',
            'api.get.settings.phone_provider.*',
            'api.post.customer.*',
        ],
        'vendor_trucking' => [
            'api.get.lookup.*',
            'api.get.bank.*',
            'api.get.truck.vendor_trucking.*',
            'api.post.truck.vendor_trucking.*',
        ],
        'user' => [
            'api.get.lookup.*',
            'api.get.settings.user.*',
            'api.get.settings.company.*',
            'api.get.settings.role.*',
            'api.get.settings.phone_provider.*',
            'api.post.settings.user.*',
        ]
    ],
];