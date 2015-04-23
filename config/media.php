<?php

return [

    'base_path' => '/uploads/',

    'base_s3_url' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/',

    'base_cdn_url' => '',

    'sizes' => [
        'thumbnail' => [
            'height' => 450,
            'width' => 450
        ],

        'listing' => [
            'height' => 300,
            'width' => 200
        ],

        'mobile_listing_ios_experience' => [
            'height' => 460,
            'width' => 750
        ],

        'mobile_listing_ios_alacarte' => [
            'height' => 410,
            'width' => 750
        ],

        'mobile_listing_andriod_experience' => [
            'height' => 662,
            'width' => 1080
        ],

        'mobile_listing_andriod_alacarte' => [
            'height' => 590,
            'width' => 1080
        ],

        'gallery' => [
            'height' => 400,
            'width' => 600
        ]
    ]
];