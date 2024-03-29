<?php

return [

    'base_path' => '/uploads/',

    'base_path_listing' => '/uploads/listing/',

    'base_path_gallery' => '/uploads/gallery/',

    'base_path_mobile' => '/uploads/mobile/',

    'base_path_collection' => '/uploads/collections/',

    'base_path_sidebar' => '/uploads/sidebars/',

    'base_path_email_footer_promotions' => '/uploads/email_footer_promotions/',

    'base_s3_url' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/',

    'base_s3_url_listing' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/listing/',

    'base_s3_url_gallery' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/gallery/',

    'base_s3_url_mobile' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/mobile/',

    'base_s3_url_collection_web' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/collections/',

    'base_s3_url_sidebars' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/sidebars/',

    'base_s3_url_email_footer_promotions' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/email_footer_promotions/',

    'base_cdn_url' => '',

    'sizes' => [
        'thumbnail' => [
            'height' => 239,
            'width' => 303
        ],

        'listing' => [
            'height' => 239,
            'width' => 303
        ],

        /*'sidebars' => [
            'height' => 283,
            'width' => 285
        ],*/

        'web_collection' => [
            'height' => 294,
            'width' => 940
        ],


        'mobile_listing_android_experience' => [
            'height' => 662,
            'width' => 1080
        ],

        'mobile_listing_ios_experience' => [
            'height' => 460,
            'width' => 750
        ],

        'mobile_listing_android_alacarte' => [
            'height' => 590,
            'width' => 1080
        ],

        'mobile_listing_ios_alacarte' => [
            'height' => 410,
            'width' => 750
        ],

        'gallery' => [
            'height' => 269,
            'width' => 651
        ]
    ]
];