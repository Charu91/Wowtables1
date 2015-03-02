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

        'main' => [
            'height' => 450,
            'width' => 450
        ],

        'listing' => [
            'height' => 200,
            'width' => 300
        ],

        'gallery' => [
            'height' => 600,
            'width' => 400
        ]
    ]
];