<?php

return [

    'typeTableAliasMap' => [
        'multi-select' => [
            'table' => 'vendor_location_attributes_multiselect',
            'so_table' => 'vendor_attributes_select_options',
            'alias' => 'vlam',
            'va_alias' => 'vlavam',
            'so_alias' => 'vlamso',
        ],

        'single-select' => [
            'table' => 'vendor_location_attributes_singleselect',
            'so_table' => 'vendor_attributes_select_options',
            'alias' => 'vlas',
            'va_alias' => 'vlavas',
            'so_alias' => 'vlasso',
        ],

        'datetime' => [
            'table' => 'vendor_location_attributes_date',
            'alias' => 'vlad',
            'va_alias' => 'vlavad'
        ],

        'boolean' => [
            'table' => 'vendor_location_attributes_boolean',
            'alias' => 'vlab',
            'va_alias' => 'vlavab'
        ],

        'float' => [
            'table' => 'vendor_location_attributes_float',
            'alias' => 'vlaf',
            'va_alias' => 'vlavaf'
        ],

        'integer' => [
            'table' => 'vendor_location_attributes_integer',
            'alias' => 'vlai',
            'va_alias' => 'vlavai'
        ],

        'text' => [
            'table' => 'vendor_location_attributes_text',
            'alias' => 'vlat',
            'va_alias' => 'vlavat'
        ],

        'varchar' => [
            'table' => 'vendor_location_attributes_varchar',
            'alias' => 'vlav',
            'va_alias' => 'vlavav'
        ]
    ],

    'attributesMap' => [
        'restaurant_info' => [
            'name' => 'Restaurant Info',
            'type' => 'text',
            'value' => 'single'
        ],

        'short_description' => [
            'name' => 'Short Description',
            'type' => 'varchar',
            'value' => 'single'
        ],

        'terms_and_conditions' => [
            'name' => 'Terms and Conditions',
            'type' => 'text',
            'value' => 'single'
        ],

        'menu_picks' => [
            'name' => 'Menu Picks',
            'type' => 'text',
            'value' => 'single'
        ],

        'expert_tips' => [
            'name' => 'Expert Tips',
            'type' => 'text',
            'value' => 'single'
        ],

        'seo_title' => [
            'name' => 'SEO Title',
            'type' => 'varchar',
            'value' => 'single'
        ],

        'seo_meta_description' => [
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

        'max_reservations_per_time_slot' => [
            'name' => 'Maximum People Per Time Slot',
            'type' => 'integer',
            'value' => 'single'
        ],

        'max_people_per_day' => [
            'name' => 'Maximum People Per Day',
            'type' => 'integer',
            'value' => 'single'
        ],

        'minimum_reservation_time_buffer' => [
            'name' => 'Minimum Reservation Time Buffer',
            'type' => 'integer',
            'value' => 'single'
        ],

        'maximum_reservation_time_buffer' => [
            'name' => 'Maximum Reservation Time Buffer',
            'type' => 'integer',
            'value' => 'single'
        ],

        'commission_per_cover' => [
            'name' => 'Commission Per Cover',
            'type' => 'float',
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

        'cuisines' => [
            'name' => 'Cuisines',
            'type' => 'multi-select',
            'value' => 'multi',
            'id_alias' => 'cusisine_id'
        ],

        'off_peak_hour_discount' => [
            'name' => 'Off Peak Hour Discount',
            'type' => 'float',
            'value' => 'single'
        ]
    ]
];