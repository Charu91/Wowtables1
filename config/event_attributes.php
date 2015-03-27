<?php

return [

    'typeTableAliasMap' => [
        'multi-select' => [
            'table' => 'product_location_attributes_multiselect',
            'so_table' => 'product_attributes_select_options',
            'alias' => 'pam',
            'pa_alias' => 'pavam',
            'so_alias' => 'pamso',
        ],

        'single-select' => [
            'table' => 'product_location_attributes_singleselect',
            'so_table' => 'product_attributes_select_options',
            'alias' => 'pas',
            'pa_alias' => 'pavas',
            'so_alias' => 'passo',
        ],

        'datetime' => [
            'table' => 'product_location_attributes_date',
            'alias' => 'pad',
            'pa_alias' => 'pavad'
        ],

        'boolean' => [
            'table' => 'product_location_attributes_boolean',
            'alias' => 'pab',
            'pa_alias' => 'pavab'
        ],

        'float' => [
            'table' => 'product_location_attributes_float',
            'alias' => 'paf',
            'pa_alias' => 'pavaf'
        ],

        'integer' => [
            'table' => 'product_location_attributes_integer',
            'alias' => 'pai',
            'pa_alias' => 'pavai'
        ],

        'text' => [
            'table' => 'product_location_attributes_text',
            'alias' => 'pat',
            'pa_alias' => 'pavat'
        ],

        'varchar' => [
            'table' => 'product_location_attributes_varchar',
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

        'seo_title' => [
            'name' => 'Title',
            'type' => 'varchar',
            'value' => 'single'
        ],

        'seo_meta_desciption' => [
            'name' => 'Meta Description',
            'type' => 'text',
            'value' => 'single'
        ],

        'seo_meta_keywords' => [
            'name' => 'Meta Keywords',
            'type' => 'varchar',
            'value' => 'multi'
        ],

        'min_people_per_reservation' => [
            'name' => 'Minimum People Per Reservation',
            'type' => 'integer',
            'value' => 'single'
        ],

        'max_people_per_reservation' => [
            'name' => 'Maximum People Per Reservation',
            'type' => 'integer',
            'value' => 'single'
        ],

        'min_people_increments_per_reservation' => [
            'name' => 'Minimum People Increments Per Reservation',
            'type' => 'integer',
            'value' => 'single'
        ],

        'max_people_for_event' => [
            'name' => 'Maximum People for the Event',
            'type' => 'integer',
            'value' => 'single'
        ],

        'minimum_reservation_time_buffer' => [
            'name' => 'Minimum Reservation Time Buffer',
            'type' => 'integer',
            'value' => 'single'
        ],

        'commission_per_cover' => [
            'name' => 'Commission Per Cover',
            'type' => 'float',
            'value' => 'single'
        ],

        'commission_calculated_on' => [
            'name' => 'Commission Calculated On',
            'type' => 'single-select',
            'value' => 'single'
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

        'location_type' => [
            'name' => 'Commission Calculated On',
            'type' => 'single-select',
            'value' => 'single'
        ],

        'prepayment_allowed' => [
            'name' => 'Prepayment Allowed',
            'type' => 'boolean',
            'value' => 'single'
        ],

        'prepayment_mandatory' => [
            'name' => 'Prepayment Mandatory',
            'type' => 'boolean',
            'value' => 'single'
        ]
    ]
];