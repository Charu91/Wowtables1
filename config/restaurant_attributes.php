<?php

return [

    'typeTableAliasMap' => [
        'multi-select' => [
            'table' => 'vendor_attributes_multiselect',
            'so_table' => 'vendor_attributes_select_options',
            'alias' => 'vam',
            'va_alias' => 'vavam',
            'so_alias' => 'vamso',
        ],

        'single-select' => [
            'table' => 'vendor_attributes_singleselect',
            'so_table' => 'vendor_attributes_select_options',
            'alias' => 'vas',
            'va_alias' => 'vavas',
            'so_alias' => 'vasso',
        ],

        'datetime' => [
            'table' => 'vendor_attributes_date',
            'alias' => 'vad',
            'va_alias' => 'vavad'
        ],

        'boolean' => [
            'table' => 'vendor_attributes_boolean',
            'alias' => 'vab',
            'va_alias' => 'vavab'
        ],

        'float' => [
            'table' => 'vendor_attributes_float',
            'alias' => 'vaf',
            'va_alias' => 'vavaf'
        ],

        'integer' => [
            'table' => 'vendor_attributes_integer',
            'alias' => 'vai',
            'va_alias' => 'vavai'
        ],

        'text' => [
            'table' => 'vendor_attributes_text',
            'alias' => 'vat',
            'va_alias' => 'vavat'
        ],

        'varchar' => [
            'table' => 'vendor_attributes_varchar',
            'alias' => 'vav',
            'va_alias' => 'vavav'
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
        ]
    ]
];