<?php

return [

    'typeTableAliasMap' => [
        'multi-select' => [
            'table' => 'user_attributes_multiselect',
            'so_table' => 'user_attributes_select_options',
            'alias' => 'uam',
            'ua_alias' => 'uauam',
            'so_alias' => 'uamso',
        ],

        'single-select' => [
            'table' => 'user_attributes_singleselect',
            'so_table' => 'user_attributes_select_options',
            'alias' => 'uas',
            'ua_alias' => 'uauas',
            'so_alias' => 'uasso',
        ],

        'datetime' => [
            'table' => 'user_attributes_date',
            'alias' => 'uad',
            'ua_alias' => 'uauad'
        ],

        'boolean' => [
            'table' => 'user_attributes_boolean',
            'alias' => 'uab',
            'ua_alias' => 'uauab'
        ],

        'float' => [
            'table' => 'user_attributes_float',
            'alias' => 'uaf',
            'ua_alias' => 'uauaf'
        ],

        'integer' => [
            'table' => 'user_attributes_integer',
            'alias' => 'uai',
            'ua_alias' => 'uauai'
        ],

        'text' => [
            'table' => 'user_attributes_text',
            'alias' => 'uat',
            'ua_alias' => 'uauat'
        ],

        'varchar' => [
            'table' => 'user_attributes_varchar',
            'alias' => 'uav',
            'ua_alias' => 'uauav'
        ]
    ],

    'attributesMap' => [
        'date_of_birth' => [
            'name' => 'Date Of Birth',
            'type' => 'datetime',
            'value' => 'single'
        ],

        'gender' => [
            'name' => 'Gender',
            'type' => 'varchar',
            'value' => 'single'
        ],

        'preferences' => [
            'name' => 'Preferences',
            'type' => 'multi-select',
            'value' => 'multi',
            'id_alias' => 'preference_id'
        ],

        'single_something' => [
            'name' => 'Single Something',
            'type' => 'single-select',
            'value' => 'single',
            'id_alias' => 'single_something_id'
        ]
    ]
];