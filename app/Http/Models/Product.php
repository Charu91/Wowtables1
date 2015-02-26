<?php namespace WowTables\Http\Models;

use DB;

class Product {

    public function fetch($id){
        if ($id = 10) {
            return [
                'code' => 200,
                'data' => [
                    'type' => 'Experience',
                    'name' => 'The 5 Course Pan Asian Affair at Skky Restro-Lounge',
                    'short_description' => '',
                    'experience_info' => '',
                    'experience_includes' => '',
                    'terms_and_conditions' => '',
                    'prepayment_allowed' => true,
                    'gift_card_redeemable' => false,
                    'total_reviews' => 100,
                    'rating' => 4.5,
                    'variable' => true,
                    'variations' => [
                        ['name' => '', 'slug' => '']
                    ],

                    'pricing' => [

                    ],

                    'menus' => [
                        ''
                    ],

                    'addons' => [

                    ],

                    'gallery' => [

                    ],

                    'locations' => [

                    ]
                ]
            ];
        } else if($id = 11) {
            return [
                'code' => 200,
                'data' => [
                    'type' => 'Experience',
                    'name' => 'The Lively Sunday Brunch at blueFROG',
                    'short_description' => '',
                    'experience_info' => '',
                    'experience_includes' => '',
                    'terms_and_conditions' => '',
                    'prepayment_allowed' => false,
                    'gift_card_redeemable' => true,
                    'total_reviews' => 60,
                    'rating' => 3.9,
                    'variable' => true,
                    'variations' => [
                        ['name' => '', 'slug' => '']
                    ],

                    'menus' => [
                        ''
                    ],

                    'addons' => [
                        [
                            'name' => '',
                            'description' => '',
                            'menu' => '',
                            'pricing' => [
                                'pre_tax_price' => '',
                                'tax' => '',
                                'post_tax_price' => ''
                            ]
                        ]
                    ],

                    'gallery' => [

                    ],

                    'locations' => [
                        [
                            'location' => '',
                            'id' => '',
                            'address' => '',
                            'city' => '',
                            'pin_code' => '',
                            'latitude' => '',
                            'longitude' => ''
                        ]
                    ]
                ]
            ];
        } else {
            return [
                'code' => 200,
                'data' => [
                    'type' => 'Event',
                    'name' => 'Master Class at Arola Oberoi',
                    'short_description' => 'Enjoy Cooking with the world class chef who epitomises spanish food in a way nobody can. Cook mouth watering recipes and learn from the very best in the business.',
                    'event_info' => '',
                    'event_date' => '',
                    'event_time' => '',
                    'location' => [
                        'name' => '',
                        'id' => '',
                        'address' => '',
                        'city' => '',
                        'pin_code' => '',
                        'latitude' => '',
                        'longitude' => ''
                    ],

                    'pricing' => [
                        'pre_tax_price' => '',
                        'tax' => '',
                        'post_tax_price' => ''
                    ],

                    'addons' => [
                        [
                            'name' => '',
                            'description' => '',
                            'pricing' => [
                                'pre_tax_price' => '',
                                'tax' => '',
                                'post_tax_price' => ''
                            ]
                        ]
                    ]
                ]
            ];
        }

    }
}