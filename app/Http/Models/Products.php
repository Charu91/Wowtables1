<?php namespace WowTables\Http\Models;

use DB;

class Products {

    public $filterable_fields = [
        'date' => [
            'name' => 'Date',
            'type' => 'single'
        ],

        'time' => [
            'name' => 'Time',
            'type' => 'single'
        ],

        'price_range' => [
            'name' => 'Price Range',
            'type' => 'single',
            'options' => [ 'low', 'medium', 'high' ]
        ],

        'locations' => [
            'name' => 'Locations',
            'type' => 'multi',
            'options' => [
                ['name' => 'Andheri', 'id' => '20'],
                ['name' => 'Lower Parel', 'id' => '25'],
                ['name' => 'Bandra', 'id' => '30'],
                ['name' => 'Marine Drive', 'id' => '35'],
                ['name' => 'Juhu', 'id' => '40'],
                ['name' => 'Worli', 'id' => '45']
            ]
        ],

        'tags' => [
            'name' => 'Tags',
            'type' => 'multi',
            'options' => [
                ['name' => 'Lunch', 'id' => '20'],
                ['name' => 'Date Night', 'id' => '25'],
                ['name' => 'Good For Groups', 'id' => '30'],
                ['name' => 'Alcohol Experiences', 'id' => '35'],
                ['name' => 'Formal Dining', 'id' => '40'],
                ['name' => 'Top Rated Experiences', 'id' => '45'],
                ['name' => 'Gift Card Experiences', 'id' => '50'],
            ]
        ],

        'cuisine' => [
            'name' => 'Cuisine',
            'type' => 'multi',
            'options' => [
                ['name' => 'Chinese', 'id' => '20'],
                ['name' => 'Japanese', 'id' => '25'],
                ['name' => 'Thai', 'id' => '30'],
                ['name' => 'Italian', 'id' => '35'],
                ['name' => 'Lebanese', 'id' => '40'],
                ['name' => 'Asian', 'id' => '45'],
                ['name' => 'Mexican', 'id' => '50'],
                ['name' => 'Continental', 'id' => '55'],
            ]
        ]
    ];

    public $sorting_options = ['Popular', 'Latest'];

    public function fetchAll($platform, $types, $data)
    {
        return [
            'code' => 200,
            'data' => [
                'experiences' => [
                    [
                        'id' => 10,
                        'type' => 'Experience',
                        'name' => 'The 5 Course Pan Asian Affair at Skky Restro-Lounge',
                        'description' => 'Watch the Mumbai skyline light up as you enjoy a mixed bag of flavours from Thailand, Malaysia, Vietnam, China and Japan at Skky Lounge.',
                        'price' => 1066,
                        'taxes' => 'inclusive',
                        'price_type' => 'Per Person',
                        'variable' => true,
                        'rating' => 4.2,
                        'total_reviews' => 30,
                        'image'=> 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/thaiPlace.jpg'
                    ],
                    [
                        'id' => 11,
                        'type' => 'Experience',
                        'name' => 'The Lively Sunday Brunch at blueFROG',
                        'description' => 'Tap your feet to some lively music as you enjoy a lazy Sunday brunch featuring international delicacies and unlimited drinks at blueFROG!',
                        'price' => 1500,
                        'taxes' => 'exclusive',
                        'price_type' => 'Per Couple',
                        'variable' => false,
                        'rating' => 0,
                        'total_reviews' => 0,
                        'image'=> 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/bluefrog.jpg'
                    ],
                    [
                        'id' => 12,
                        'type' => 'Event',
                        'name' => 'Master Class at Arola Oberoi',
                        'description' => 'Enjoy Cooking with the world class chef who epitomises spanish food in a way nobody can. Cook mouth watering recipes and learn from the very best in the business.',
                        'price' => 1200,
                        'taxes' => 'inclusive',
                        'price_type' => 'Per Person',
                        'image'=> 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/arola.jpg'
                    ]
                ],

                'filters' => $this->filterable_fields,

                'sorting_options' => $this->sorting_options
            ]
        ];
    }
} 