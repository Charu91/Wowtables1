<?php

return [

    'typeTableAliasMap' => [
        'multi-select' => [
            'table' => 'product_attributes_multiselect',
            'so_table' => 'product_attributes_select_options',
            'alias' => 'pam',
            'pa_alias' => 'pavam',
            'so_alias' => 'pamso',
        ],

        'single-select' => [
            'table' => 'product_attributes_singleselect',
            'so_table' => 'product_attributes_select_options',
            'alias' => 'pas',
            'pa_alias' => 'pavas',
            'so_alias' => 'passo',
        ],

        'datetime' => [
            'table' => 'product_attributes_date',
            'alias' => 'pad',
            'pa_alias' => 'pavad'
        ],

        'boolean' => [
            'table' => 'product_attributes_boolean',
            'alias' => 'pab',
            'pa_alias' => 'pavab'
        ],

        'float' => [
            'table' => 'product_attributes_float',
            'alias' => 'paf',
            'pa_alias' => 'pavaf'
        ],

        'integer' => [
            'table' => 'product_attributes_integer',
            'alias' => 'pai',
            'pa_alias' => 'pavai'
        ],

        'text' => [
            'table' => 'product_attributes_text',
            'alias' => 'pat',
            'pa_alias' => 'pavat'
        ],

        'varchar' => [
            'table' => 'product_attributes_varchar',
            'alias' => 'pav',
            'pa_alias' => 'pavav'
        ]
    ],

    'attributesMap' => [
        'experience_info' => [
            'name' => 'Experience Info',
            'type' => 'text',
            'value' => 'single'
        ],

        'experience_includes' => [
            'name' => 'Experience Includes',
            'type' => 'text',
            'value' => 'single'
        ],

        'short_description' => [
            'name' => 'Short Description',
            'type' => 'text',
            'value' => 'single'
        ],

        'terms_and_conditions' => [
            'name' => 'Terms and Conditions',
            'type' => 'text',
            'value' => 'single'
        ],

        'menu' => [
            'name' => 'Menu',
            'type' => 'text',
            'value' => 'single'
        ],

        'menu_markdown' => [
            'name' => 'Menu Markdown',
            'type' => 'text',
            'value' => 'single'
        ],

        'seo_title' => [
            'name' => 'SEO Title',
            'type' => 'varchar',
            'value' => 'single'
        ],

        'seo_meta_desciption' => [
            'name' => 'SEO Meta Description',
            'type' => 'text',
            'value' => 'single'
        ],

        'seo_meta_keywords' => [
            'name' => 'SEO Meta Keywords',
            'type' => 'varchar',
            'value' => 'multi'
        ],

        'allow_gift_card_redemptions' => [
            'name' => 'Allow Gift Card Redemptions',
            'type' => 'boolean',
            'value' => 'single'
        ],

        'reward_points_per_reservation' => [
            'name' => 'Reward Points Per Reservation',
            'type' => 'integer',
            'value' => 'single'
        ],

        'cuisines' => [
            'name' => 'Cuisines',
            'type' => 'multi-select',
            'value' => 'multi',
            'id_alias' => 'cusisine_id'
        ],

        'prepayment_allowed' => [
            'name' => 'Prepayment Allowed',
            'type' => 'boolean',
            'value' => 'single'
        ],

        'curator_tip' => [
            'name' => 'Curator Tip',
            'type' => 'text',
            'value' => 'single'
        ],

        'start_date' => [
            'name' => 'Start Date',
            'type' => 'datetime',
            'value' => 'single'
        ],

        'end_date' => [
            'name' => 'End Date',
            'type' => 'datetime',
            'value' => 'single'
        ],

        'reservation_title' => [
            'name' => 'Reservation Title',
            'type' => 'text',
            'value' => 'single'
        ]
    ]
];