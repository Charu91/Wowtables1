<?php namespace WowTables\Http\Models;

use DB;

class Product {

    public function fetch($id){
        if ($id == 10) {
            return [
                'code' => 200,
                'data' => [
                    'type' => 'Experience',
                    'name' => 'The 5 Course Pan Asian Affair at Skky Restro-Lounge',
                    'short_description' => 'Watch the Mumbai skyline light up as you enjoy a mixed bag of flavours from Thailand, Malaysia, Vietnam, China and Japan at Skky Lounge.',
                    'experience_info' => '<p>
SKKY – a Pan-Asian Rooftop Resto lounge will entrance you with its open air ambience, lush green views, private dining spaces and delectable food. Located amidst the lap of nature, the cool breeze and large water bodies mesmerize you instantly. Spread across an area of 8500 sq feet, SKKY offers an unobstructed and captivating view of the sky. With stars twinkling above and scenic view of the mountains, it offers an ideal setting for an evening drink, dinner or a get together. With of one of the longest bars in the city which measures upto 510 sq ft; serving some of the finest brands and mixes SKKY’s bartenders have a long list of favorites that will set you on the path of relaxation. But that’s just the beginning; tantalize your taste buds with mouth watering Pan Asian cuisine bringing together ingredients &amp; flavors from Thailand, Malaysia, Vietnam, China &amp; Japan.&nbsp;</p>',
                    'experience_includes' => '<ul>
<li>
-&nbsp;&nbsp;A choice of a soup, an appetizer, a main, staple and a dessert</li>
<li>
- This experience is paired with a glass of house wine or a cocktail or a mocktail</li>
<li>- 1000 Gourmet Points when you make a reservation online<a href="http://gourmetitup.com/gourmet-rewards" target="_blank" data-original-title="Click here to read about Gourmet Rewards" data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="http://gourmetitup.com/images/question_icon_small_display.png"></a></li>
</ul>',
                    'terms_and_conditions' => '<ul>
<li>
- The dishes in this set meal cannot be shared with guests having a-la-carte.</li>
<li>
- Taxes are not included in the advertised price.</li>
<li>
- This experience is priced at Rs. 1200 per person inclusive of all taxes and charges.</li>
<li>
- This experience is paired with a glass of house wine or a cocktail or a mocktail.</li>
<li>
- Valid on all days only for Dinner.</li>
<li>
- Payment can be made directly at the restaurant.</li>
<li>
- The above displayed Gourmet Points are available only for reservations made online.</li>
<li>
- Guests need to pre-order (ask the server for the items in each course at the beginning of the meal).</li>
</ul>',
                    'prepayment_mandatory' => false,
                    'prepayment_allowed' => true,
                    'gift_card_redeemable' => false,
                    'total_reviews' => 100,
                    'rating' => 4.5,
                    'variable' => true,
                    'variations' => [
                        [
                            'name' => '5 Course Pan Asian Affair Veg',
                            'slug' => '5-course-pan-asian-affair-veg'
                        ],
                        [
                            'name' => '5 Course Pan Asian Affair Non Veg',
                            'slug' => '5-course-pan-asian-affair-non-veg'
                        ]
                    ],

                    'pricing' => [
                        '5-course-pan-asian-affair-veg' => [
                            'pre_tax_price' => 1000,
                            'tax' => 100,
                            'post_tax_price' => 1100
                        ],

                        '5-course-pan-asian-affair-non-veg' => [
                            'pre_tax_price' => 1200,
                            'tax' => 120,
                            'post_tax_price' => 1320
                        ],
                    ],

                    'menus' => [
                        '5-course-pan-asian-affair-veg' => [
                            'title' => '5 COURSE EXPERIENCE',
                            'sub_title' => 'This experience is paired with a glass of house wine or a cocktail',

                            'menu' => [
                                [
                                    'heading' => 'APPETIZER',
                                    'choice' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Hed Krob',
                                            'tags' => ['Veg'],
                                            'description' => 'Garlic flavored fresh corn, glass noodles and spinach with sesame oil'
                                        ],

                                        [
                                            'title' => 'Sichuan Corn Curd',
                                            'tags' => ['Veg'],
                                            'description' => 'Wok fried crisp mushrooms tossed with black pepper sauce and coriander'
                                        ],

                                        [
                                            'title' => 'Cristal Vegetable Dim Sum',
                                            'tags' => ['Veg'],
                                            'description' => 'Fresh corn blend cooked with sichuan pepper oil and bean sauce'
                                        ]
                                    ]
                                ],

                                [
                                    'heading' => 'SOUP',
                                    'choice' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Spicy Veg. Bean Curd Soup',
                                            'tags' => ['Veg', 'Specials'],
                                            'description' => 'Mildly spiced braised vegetables, tofu, and celery soup'
                                        ],

                                        [
                                            'title' => 'Ipoh Sar Ho Fan',
                                            'tags' => ['Veg'],
                                            'description' => 'Veg soup with bok choi and rice sticks topped with crisp garlic'
                                        ],
                                    ]
                                ],

                                [
                                    'heading' => 'MAIN COURSE',
                                    'choice' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Shanghai Clay Pot',
                                            'tags' => ['Veg'],
                                            'description' => 'Exotic vegetables with dried bean curd and chilli bean sauce'
                                        ],

                                        [
                                            'title' => 'Gaeng Kiew Wan Phak',
                                            'tags' => ['Veg', 'Specials'],
                                            'description' => 'Fresh vegetables & bean curd with Thai pea eggplant in sweet basil flavoured Thai green curry'
                                        ],
                                    ]
                                ],

                                [
                                    'heading' => 'STAPLE',
                                    'choice' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Burnt Garlic and Spring Onion Fried Rice',
                                            'tags' => ['Veg'],
                                            'description' => 'Vegetable fried rice with crisp garlic and spring onions'
                                        ],

                                        [
                                            'title' => 'Khao Phad Kaprow Potato',
                                            'tags' => ['Veg'],
                                            'description' => 'A Thai fried rice with garlic, bird chili, sweet basil'
                                        ],
                                    ]
                                ],
                            ]
                        ],
                        '5-course-pan-asian-affair-non-veg' => [
                            'title' => '5 COURSE EXPERIENCE',
                            'sub_title' => 'This experience is paired with a glass of house wine or a cocktail',

                            'menu' => [
                                [
                                    'heading' => 'APPETIZER',
                                    'choice' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Hed Krob',
                                            'tags' => ['Non-Veg'],
                                            'description' => 'Garlic flavored fresh corn, glass noodles and spinach with sesame oil'
                                        ],

                                        [
                                            'title' => 'Sichuan Corn Curd',
                                            'tags' => ['Non-Veg'],
                                            'description' => 'Wok fried crisp mushrooms tossed with black pepper sauce and coriander'
                                        ],

                                        [
                                            'title' => 'Cristal Vegetable Dim Sum',
                                            'tags' => ['Non-Veg'],
                                            'description' => 'Fresh corn blend cooked with sichuan pepper oil and bean sauce'
                                        ]
                                    ]
                                ],

                                [
                                    'heading' => 'SOUP',
                                    'choice' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Spicy Veg. Bean Curd Soup',
                                            'tags' => ['Non-Veg'],
                                            'description' => 'Mildly spiced braised vegetables, tofu, and celery soup'
                                        ],

                                        [
                                            'title' => 'Ipoh Sar Ho Fan',
                                            'tags' => ['Non-Veg','Specials'],
                                            'description' => 'Veg soup with bok choi and rice sticks topped with crisp garlic'
                                        ],
                                    ]
                                ],

                                [
                                    'heading' => 'MAIN COURSE',
                                    'choice' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Shanghai Clay Pot',
                                            'tags' => ['Non-Veg'],
                                            'description' => 'Exotic vegetables with dried bean curd and chilli bean sauce'
                                        ],

                                        [
                                            'title' => 'Gaeng Kiew Wan Phak',
                                            'tags' => ['Non-Veg'],
                                            'description' => 'Fresh vegetables & bean curd with Thai pea eggplant in sweet basil flavoured Thai green curry'
                                        ],
                                    ]
                                ],

                                [
                                    'heading' => 'STAPLE',
                                    'choice' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Burnt Garlic and Spring Onion Fried Rice',
                                            'tags' => ['Non-Veg','Specials'],
                                            'description' => 'Vegetable fried rice with crisp garlic and spring onions'
                                        ],

                                        [
                                            'title' => 'Khao Phad Kaprow Potato',
                                            'tags' => ['Non-Veg'],
                                            'description' => 'A Thai fried rice with garlic, bird chili, sweet basil'
                                        ],
                                    ]
                                ],
                            ]
                        ]
                    ],

                    'addons' => [
                        [
                            'name' => 'Bottle of Chenin Blanc',
                            'Description' => '<p>
The Chenin Blanc is a Sula Special that will add make your food more emorable at Skky Restro Lounge. Do not miss it
</p>',
                            'pricing' => [
                                'pre_tax_price' => 800,
                                'tax' => 80,
                                'post_tax_price' => 880
                            ]
                        ],

                        [
                            'name' => 'Choice of Desserts',
                            'Description' => '<p>
Please Add Dessert to your 5 course menu and make sure that you do not leave unsatisfied.
</p>',
                            'pricing' => [
                                'pre_tax_price' => 600,
                                'tax' => 60,
                                'post_tax_price' => 660
                            ],

                            'menu' => [
                                'title' => '5 COURSE EXPERIENCE',
                                'sub_title' => 'This experience is paired with a glass of house wine or a cocktail',

                                'menu' => [
                                    'heading' => 'Desserts',
                                    'description' => 'choose any one',

                                    'items' => [
                                        [
                                            'title' => 'Hand Crafted Ice Cream',
                                            'tags'  => ['Veg'],
                                            'description' => 'Paan or dolce de leche'
                                        ],

                                        [
                                            'title' => 'White Chocolate and Grand Mariner Cheese Cake',
                                            'tags'  => ['Veg'],
                                            'description' => 'Baked Philadelphia cheese cake with an elegant blend of grand mariner and white chocolate truffle'
                                        ],

                                        [
                                            'title' => 'Banoffee Pie',
                                            'tags'  => ['Veg'],
                                            'description' => 'Soft pastry base with a blend of banana and toffee, garnished with swirls of whipped cream'
                                        ],

                                        [
                                            'title' => 'Chocolate Brownie',
                                            'tags'  => ['Non-Veg']
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],

                    'gallery' => [
                        'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/thaiPlace.jpg',
                        'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/thaipalce2.jpg',
                        'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/thaiplace1.jpg',
                        'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/thaiplace3.jpg'
                    ],

                    'locations' => [
                        [
                            'location' => 'Powai',
                            'id' => '20',
                            'address' => 'Ramada Powai Hotel & Convention Centre Near The Residence Hotel & Apartments, Before Vihar Lake, Powai, Saki Vihar Road',
                            'city' => 'Mumbai',
                            'pin_code' => '400087',
                            'latitude' => '19.136440',
                            'longitude' => '72.899267'
                        ],

                        [
                            'location' => 'Andheri',
                            'id' => '21',
                            'address' => 'Plot B31, Shree Siddhivanayak Plaza, Off New Link Road, Lane Opp. Citi Mal, Andheri West ',
                            'city' => 'Mumbai',
                            'pin_code' => '400053',
                            'latitude' => '19.139852',
                            'longitude' => '72.833438'
                        ],
                    ]
                ]
            ];
        } else if($id == 11) {
            return [
                'code' => 200,
                'data' => [
                    'type' => 'Experience',
                    'name' => 'The Lively Sunday Brunch at blueFROG',
                    'short_description' => 'Watch the Mumbai skyline light up as you enjoy a mixed bag of flavours from Thailand, Malaysia, Vietnam, China and Japan at Skky Lounge.',
                    'experience_info' => '<p>
SKKY – a Pan-Asian Rooftop Resto lounge will entrance you with its open air ambience, lush green views, private dining spaces and delectable food. Located amidst the lap of nature, the cool breeze and large water bodies mesmerize you instantly. Spread across an area of 8500 sq feet, SKKY offers an unobstructed and captivating view of the sky. With stars twinkling above and scenic view of the mountains, it offers an ideal setting for an evening drink, dinner or a get together. With of one of the longest bars in the city which measures upto 510 sq ft; serving some of the finest brands and mixes SKKY’s bartenders have a long list of favorites that will set you on the path of relaxation. But that’s just the beginning; tantalize your taste buds with mouth watering Pan Asian cuisine bringing together ingredients &amp; flavors from Thailand, Malaysia, Vietnam, China &amp; Japan.&nbsp;</p>',
                    'experience_includes' => '<ul>
<li>
-&nbsp;&nbsp;A choice of a soup, an appetizer, a main, staple and a dessert</li>
<li>
- This experience is paired with a glass of house wine or a cocktail or a mocktail</li>
<li>- 1000 Gourmet Points when you make a reservation online<a href="http://gourmetitup.com/gourmet-rewards" target="_blank" data-original-title="Click here to read about Gourmet Rewards" data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="http://gourmetitup.com/images/question_icon_small_display.png"></a></li>
</ul>',
                    'terms_and_conditions' => '<ul>
<li>
- The dishes in this set meal cannot be shared with guests having a-la-carte.</li>
<li>
- Taxes are not included in the advertised price.</li>
<li>
- This experience is priced at Rs. 1200 per person inclusive of all taxes and charges.</li>
<li>
- This experience is paired with a glass of house wine or a cocktail or a mocktail.</li>
<li>
- Valid on all days only for Dinner.</li>
<li>
- Payment can be made directly at the restaurant.</li>
<li>
- The above displayed Gourmet Points are available only for reservations made online.</li>
<li>
- Guests need to pre-order (ask the server for the items in each course at the beginning of the meal).</li>
</ul>',
                    'prepayment_mandatory' => false,
                    'prepayment_allowed' => false,
                    'gift_card_redeemable' => true,
                    'total_reviews' => 60,
                    'rating' => 3.9,
                    'variable' => false,

                    'menu' => [
                        'title' => '5 COURSE EXPERIENCE',
                        'sub_title' => 'This experience is paired with a glass of house wine or a cocktail',

                        'menu' => [
                            [
                                'heading' => 'APPETIZER',
                                'choice' => 'choose any one',

                                'items' => [
                                    [
                                        'title' => 'Hed Krob',
                                        'tags'  => ['Veg', 'Specials'],
                                        'description' => 'Garlic flavored fresh corn, glass noodles and spinach with sesame oil'
                                    ],

                                    [
                                        'title' => 'Sichuan Corn Curd',
                                        'tags'  => ['Non-Veg'],
                                        'description' => 'Wok fried crisp mushrooms tossed with black pepper sauce and coriander'
                                    ],

                                    [
                                        'title' => 'Cristal Vegetable Dim Sum',
                                        'tags'  => ['Veg'],
                                        'description' => 'Fresh corn blend cooked with sichuan pepper oil and bean sauce'
                                    ]
                                ]
                            ],

                            [
                                'heading' => 'SOUP',
                                'choice' => 'choose any one',

                                'items' => [
                                    [
                                        'title' => 'Spicy Chicken Bean Curd Soup',
                                        'tags'  => ['Non-Veg'],
                                        'description' => 'Mildly spiced braised vegetables, tofu, and celery soup'
                                    ],

                                    [
                                        'title' => 'Ipoh Sar Ho Fan',
                                        'tags'  => ['Veg'],
                                        'description' => 'Veg soup with bok choi and rice sticks topped with crisp garlic'
                                    ],
                                ]
                            ],

                            [
                                'heading' => 'MAIN COURSE',
                                'choice' => 'choose any one',

                                'items' => [
                                    [
                                        'title' => 'Shanghai Clay Pot',
                                        'tags'  => ['Veg'],
                                        'description' => 'Exotic vegetables with dried bean curd and chilli bean sauce'
                                    ],

                                    [
                                        'title' => 'Gaeng Kiew Wan Phak',
                                        'tags'  => ['Non-Veg', 'Specials'],
                                        'description' => 'Fresh vegetables & bean curd with Thai pea eggplant in sweet basil flavoured Thai green curry'
                                    ],
                                ]
                            ],

                            [
                                'heading' => 'STAPLE',
                                'choice' => 'choose any one',

                                'items' => [
                                    [
                                        'title' => 'Burnt Garlic and Spring Onion Fried Rice',
                                        'tags'  => ['Veg'],
                                        'description' => 'Vegetable fried rice with crisp garlic and spring onions'
                                    ],

                                    [
                                        'title' => 'Khao Phad Kaprow Potato',
                                        'tags'  => ['Veg'],
                                        'description' => 'A Thai fried rice with garlic, bird chili, sweet basil'
                                    ],
                                ]
                            ],
                        ]
                    ],

                    'addons' => [],

                    'gallery' => [
                        'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/bluefrog.jpg',
                        'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/bluefrog1.jpeg'
                    ],

                    'locations' => [
                        [
                            'location' => 'Andheri',
                            'id' => '21',
                            'address' => 'Plot B31, Shree Siddhivanayak Plaza, Off New Link Road, Lane Opp. Citi Mal, Andheri West ',
                            'city' => 'Mumbai',
                            'pin_code' => '400053',
                            'latitude' => '19.139852',
                            'longitude' => '72.833438'
                        ],
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
                    'experience_info' => '<p>
Make sure to indulge yourself and your lived one in a master class like never before.
You will come out with your better half feeling the food of a country that never found a way to your homes
and leave you wondering why.
</p>',
                    'experience_includes' => '<ul>
<li>
-&nbsp;&nbsp;A choice of a soup, an appetizer, a main, staple and a dessert</li>
<li>
- This experience is paired with a glass of house wine or a cocktail or a mocktail</li>
<li>- 1000 Gourmet Points when you make a reservation online<a href="http://gourmetitup.com/gourmet-rewards" target="_blank" data-original-title="Click here to read about Gourmet Rewards" data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="http://gourmetitup.com/images/question_icon_small_display.png"></a></li>
</ul>',
                    'terms_and_conditions' => '<ul>
<li>
- The dishes in this set meal cannot be shared with guests having a-la-carte.</li>
<li>
- Taxes are not included in the advertised price.</li>
<li>
- This experience is priced at Rs. 1200 per person inclusive of all taxes and charges.</li>
<li>
- This experience is paired with a glass of house wine or a cocktail or a mocktail.</li>
<li>
- Valid on all days only for Dinner.</li>
<li>
- Payment can be made directly at the restaurant.</li>
<li>
- The above displayed Gourmet Points are available only for reservations made online.</li>
<li>
- Guests need to pre-order (ask the server for the items in each course at the beginning of the meal).</li>
</ul>',
                    'prepayment_mandatory' => true,
                    'event_date' => '2015-03-21',
                    'event_time' => '21:30:00',
                    'location' => [
                        'name' => 'Worli',
                        'id' => '18',
                        'address' => 'Amateur Riders Club, Mahalaxmi Race Course, Mahalaxmi',
                        'city' => 'Mumbai',
                        'pin_code' => '400016',
                        'latitude' => ' 18.980545',
                        'longitude' => '72.820132'
                    ],

                    'pricing' => [
                        'pre_tax_price' => 2000,
                        'tax' => 200,
                        'post_tax_price' => 2200
                    ],

                    'gallery' => [
                        'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/arola.jpg',
                        'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/arola2.gif'
                    ],

                    'addons' => [
                        [
                            'name' => 'Special Wine Tasting After the cooking',
                            'description' => '<p>Sit Back Taste some of the most exquisite Wine from the spanish country side</p>',
                            'pricing' => [
                                'pre_tax_price' => 1000,
                                'tax' => 100,
                                'post_tax_price' => 1100
                            ]
                        ]
                    ]
                ]
            ];
        }

    }
}