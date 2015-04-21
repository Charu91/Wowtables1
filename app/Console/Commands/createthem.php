<?php namespace WowTables\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use WowTables\Http\Models\RestaurantLocation;

class createthem extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'createIt';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(RestaurantLocation $restaurantLocation)
	{
        $restData = [
            [
                'restaurant_id' => 26,
                'location_id' => 15,
                'slug' => 'the-little-door-versova',
                'status' => 'Active',
                'a_la_carte' => 1,
                'pricing_level' => 'Medium',
                'attributes' => [
                    'restaurant_info' => '<p>In your daily life there are very few places you come across that allow you to just unwind. No jarring lights, no over the top décor – just a beautiful place that takes you away to a happy state of mind. At&nbsp;TLD, there’s something special about the food and drinks. They aren’t the take it or leave it people. They’re more like the "we’re happy to actually implement feedback you’ve given us – time and again, until we’re perfect and even after that, there’s always room for more."</p>',
                    'short_description' => 'The Litte Door is a cute Place',
                    'terms_and_conditions' => '<p class="lead">TERMS</p>
<ul>
<li>Gourmetitup Gift Cards are not valid for A la carte reservations</li>
<ul>
<li>
<p>
Please make a reservation at least 2 hours in advance to ensure availability.</p>
</li>
<li>
<p>
After making a reservation, you will receive a confirmation by e-mail as well as SMS.</p>
</li>
<li>
<p>
Rights to table reservation is solely on the basis of availability.</p>
</li>
<li>
<p>
Call our Concierge service at 9619551387 if you have any queries.</p>
</li>
</ul>
<p>
<br>
&nbsp;</p>
</ul>',
                    'menu_picks' => '<strong>Menu Picks</strong>
<p>
<u>Food Picks: </u></p>
<p>
&nbsp;</p>
<p>
Funghi Ripieni</p>
<p>
Bruschetta</p>
<p>
Sicilian cottage cheese</p>
<p>
Mini Shawarma</p>
<p>
Spanish Wings</p>
<p>
Chicken Kabobs</p>
<p>
Bombil Fritto</p>
<p>
Shrimp Brochette</p>
<p>
Champagne Risotto</p>
<p>
Gambas A La Parilla</p>
<p>
Peppered Steak</p>
<p>
&nbsp;</p>
<p>
<u>Dessert Pick:</u></p>
<p>
&nbsp;</p>
<p>
Rum Drum</p>',
                    'expert_tips' => '<strong>Expert Tips</strong>
<p>
They have the age old activity of the JUG CHUG - down a mug of beer faster than anyone when the bell rings and you might just win that beer for yourself besides becoming famous around here with your name on the Wall of Fam</p>
<p>
<br>
And if you think you’ve got the aim and you’re ready for a game, (unintentional rhyme) order a pitcher and challenge your friends to a round of BEER PONG.</p>',
                    'seo_title' => 'The Little Door',
                    'seo_meta_description' => 'The Litte Door is a cute Place',
                    'seo_meta_keywords' => ['Little Door', 'Italian', 'Breeze'],
                    'min_people_per_reservation' => 2,
                    'max_people_per_reservation' => 8,
                    'min_people_increments_per_reservation' => 2,
                    'max_reservations_per_time_slot' => 10,
                    'minimum_reservation_time_buffer' => 60,
                    'maximum_reservation_time_buffer' => 30,
                    'commission_per_cover' => 100,
                    'allow_gift_card_redemptions' => 1,
                    'reward_points_per_reservation' => 200,
                    'cuisines' => [1,3,4],
                ],

                'address' => [
                    'address' => 'This is a random Address',
                    'latitude' => '18.92285',
                    'longitude' => '72.831888',
                    'pin_code' => '110030'
                ],

                'media' => [
                    'listing_image' => 29,
                    'gallery_images' => [30,31,33]
                ],

                'schedules' => [
                    131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180
                ],

                'tags' => [5,9],

                'flags' => [1,3]
            ],

            [
                'restaurant_id' => 26,
                'location_id' => 20,
                'slug' => 'the-little-door-powai',
                'status' => 'Active',
                'a_la_carte' => 1,
                'pricing_level' => 'Medium',
                'attributes' => [
                    'restaurant_info' => '<p>In your daily life there are very few places you come across that allow you to just unwind. No jarring lights, no over the top décor – just a beautiful place that takes you away to a happy state of mind. At&nbsp;TLD, there’s something special about the food and drinks. They aren’t the take it or leave it people. They’re more like the "we’re happy to actually implement feedback you’ve given us – time and again, until we’re perfect and even after that, there’s always room for more."</p>',
                    'short_description' => 'The Litte Door is a cute Place',
                    'terms_and_conditions' => '<p class="lead">TERMS</p>
<ul>
<li>Gourmetitup Gift Cards are not valid for A la carte reservations</li>
<ul>
<li>
<p>
Please make a reservation at least 2 hours in advance to ensure availability.</p>
</li>
<li>
<p>
After making a reservation, you will receive a confirmation by e-mail as well as SMS.</p>
</li>
<li>
<p>
Rights to table reservation is solely on the basis of availability.</p>
</li>
<li>
<p>
Call our Concierge service at 9619551387 if you have any queries.</p>
</li>
</ul>
<p>
<br>
&nbsp;</p>
</ul>',
                    'menu_picks' => '<strong>Menu Picks</strong>
<p>
<u>Food Picks: </u></p>
<p>
&nbsp;</p>
<p>
Funghi Ripieni</p>
<p>
Bruschetta</p>
<p>
Sicilian cottage cheese</p>
<p>
Mini Shawarma</p>
<p>
Spanish Wings</p>
<p>
Chicken Kabobs</p>
<p>
Bombil Fritto</p>
<p>
Shrimp Brochette</p>
<p>
Champagne Risotto</p>
<p>
Gambas A La Parilla</p>
<p>
Peppered Steak</p>
<p>
&nbsp;</p>
<p>
<u>Dessert Pick:</u></p>
<p>
&nbsp;</p>
<p>
Rum Drum</p>',
                    'expert_tips' => '<strong>Expert Tips</strong>
<p>
They have the age old activity of the JUG CHUG - down a mug of beer faster than anyone when the bell rings and you might just win that beer for yourself besides becoming famous around here with your name on the Wall of Fam</p>
<p>
<br>
And if you think you’ve got the aim and you’re ready for a game, (unintentional rhyme) order a pitcher and challenge your friends to a round of BEER PONG.</p>',
                    'seo_title' => 'The Little Door',
                    'seo_meta_description' => 'The Litte Door is a cute Place',
                    'seo_meta_keywords' => ['Little Door', 'Italian', 'Breeze'],
                    'min_people_per_reservation' => 2,
                    'max_people_per_reservation' => 8,
                    'min_people_increments_per_reservation' => 2,
                    'max_reservations_per_time_slot' => 10,
                    'minimum_reservation_time_buffer' => 60,
                    'maximum_reservation_time_buffer' => 30,
                    'commission_per_cover' => 100,
                    'allow_gift_card_redemptions' => 1,
                    'reward_points_per_reservation' => 200,
                    'cuisines' => [1,3,4],
                ],

                'address' => [
                    'address' => 'This is a random Address',
                    'latitude' => '18.92285',
                    'longitude' => '72.831888',
                    'pin_code' => '110030'
                ],

                'media' => [
                    'listing_image' => 30,
                    'gallery_images' => [31,29,32]
                ],

                'schedules' => [
                    1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182
                ],

                'tags' => [5,9],

                'flags' => [1,3]
            ],

            [
                'restaurant_id' => 26,
                'location_id' => 23,
                'slug' => 'the-little-door-bandra',
                'status' => 'Active',
                'a_la_carte' => 1,
                'pricing_level' => 'Medium',
                'attributes' => [
                    'restaurant_info' => '<p>In your daily life there are very few places you come across that allow you to just unwind. No jarring lights, no over the top décor – just a beautiful place that takes you away to a happy state of mind. At&nbsp;TLD, there’s something special about the food and drinks. They aren’t the take it or leave it people. They’re more like the "we’re happy to actually implement feedback you’ve given us – time and again, until we’re perfect and even after that, there’s always room for more."</p>',
                    'short_description' => 'The Litte Door is a cute Place',
                    'terms_and_conditions' => '<p class="lead">TERMS</p>
<ul>
<li>Gourmetitup Gift Cards are not valid for A la carte reservations</li>
<ul>
<li>
<p>
Please make a reservation at least 2 hours in advance to ensure availability.</p>
</li>
<li>
<p>
After making a reservation, you will receive a confirmation by e-mail as well as SMS.</p>
</li>
<li>
<p>
Rights to table reservation is solely on the basis of availability.</p>
</li>
<li>
<p>
Call our Concierge service at 9619551387 if you have any queries.</p>
</li>
</ul>
<p>
<br>
&nbsp;</p>
</ul>',
                    'menu_picks' => '<strong>Menu Picks</strong>
<p>
<u>Food Picks: </u></p>
<p>
&nbsp;</p>
<p>
Funghi Ripieni</p>
<p>
Bruschetta</p>
<p>
Sicilian cottage cheese</p>
<p>
Mini Shawarma</p>
<p>
Spanish Wings</p>
<p>
Chicken Kabobs</p>
<p>
Bombil Fritto</p>
<p>
Shrimp Brochette</p>
<p>
Champagne Risotto</p>
<p>
Gambas A La Parilla</p>
<p>
Peppered Steak</p>
<p>
&nbsp;</p>
<p>
<u>Dessert Pick:</u></p>
<p>
&nbsp;</p>
<p>
Rum Drum</p>',
                    'expert_tips' => '<strong>Expert Tips</strong>
<p>
They have the age old activity of the JUG CHUG - down a mug of beer faster than anyone when the bell rings and you might just win that beer for yourself besides becoming famous around here with your name on the Wall of Fam</p>
<p>
<br>
And if you think you’ve got the aim and you’re ready for a game, (unintentional rhyme) order a pitcher and challenge your friends to a round of BEER PONG.</p>',
                    'seo_title' => 'The Little Door',
                    'seo_meta_description' => 'The Litte Door is a cute Place',
                    'seo_meta_keywords' => ['Little Door', 'Italian', 'Breeze'],
                    'min_people_per_reservation' => 2,
                    'max_people_per_reservation' => 8,
                    'min_people_increments_per_reservation' => 2,
                    'max_reservations_per_time_slot' => 10,
                    'minimum_reservation_time_buffer' => 60,
                    'maximum_reservation_time_buffer' => 30,
                    'commission_per_cover' => 100,
                    'allow_gift_card_redemptions' => 1,
                    'reward_points_per_reservation' => 200,
                    'cuisines' => [1,3,4],
                ],

                'address' => [
                    'address' => 'This is a random Address',
                    'latitude' => '18.92285',
                    'longitude' => '72.831888',
                    'pin_code' => '110030'
                ],

                'media' => [
                    'listing_image' => 33,
                    'gallery_images' => [31,29,30]
                ],

                'schedules' => [
                    1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182
                ],

                'tags' => [5,9],

                'flags' => [1,3]
            ],

            [
                'restaurant_id' => 26,
                'location_id' => 24,
                'slug' => 'the-little-door-phoenix-mills',
                'status' => 'Active',
                'a_la_carte' => 1,
                'pricing_level' => 'Medium',
                'attributes' => [
                    'restaurant_info' => '<p>In your daily life there are very few places you come across that allow you to just unwind. No jarring lights, no over the top décor – just a beautiful place that takes you away to a happy state of mind. At&nbsp;TLD, there’s something special about the food and drinks. They aren’t the take it or leave it people. They’re more like the "we’re happy to actually implement feedback you’ve given us – time and again, until we’re perfect and even after that, there’s always room for more."</p>',
                    'short_description' => 'The Litte Door is a cute Place',
                    'terms_and_conditions' => '<p class="lead">TERMS</p>
<ul>
<li>Gourmetitup Gift Cards are not valid for A la carte reservations</li>
<ul>
<li>
<p>
Please make a reservation at least 2 hours in advance to ensure availability.</p>
</li>
<li>
<p>
After making a reservation, you will receive a confirmation by e-mail as well as SMS.</p>
</li>
<li>
<p>
Rights to table reservation is solely on the basis of availability.</p>
</li>
<li>
<p>
Call our Concierge service at 9619551387 if you have any queries.</p>
</li>
</ul>
<p>
<br>
&nbsp;</p>
</ul>',
                    'menu_picks' => '<strong>Menu Picks</strong>
<p>
<u>Food Picks: </u></p>
<p>
&nbsp;</p>
<p>
Funghi Ripieni</p>
<p>
Bruschetta</p>
<p>
Sicilian cottage cheese</p>
<p>
Mini Shawarma</p>
<p>
Spanish Wings</p>
<p>
Chicken Kabobs</p>
<p>
Bombil Fritto</p>
<p>
Shrimp Brochette</p>
<p>
Champagne Risotto</p>
<p>
Gambas A La Parilla</p>
<p>
Peppered Steak</p>
<p>
&nbsp;</p>
<p>
<u>Dessert Pick:</u></p>
<p>
&nbsp;</p>
<p>
Rum Drum</p>',
                    'expert_tips' => '<strong>Expert Tips</strong>
<p>
They have the age old activity of the JUG CHUG - down a mug of beer faster than anyone when the bell rings and you might just win that beer for yourself besides becoming famous around here with your name on the Wall of Fam</p>
<p>
<br>
And if you think you’ve got the aim and you’re ready for a game, (unintentional rhyme) order a pitcher and challenge your friends to a round of BEER PONG.</p>',
                    'seo_title' => 'The Little Door',
                    'seo_meta_description' => 'The Litte Door is a cute Place',
                    'seo_meta_keywords' => ['Little Door', 'Italian', 'Breeze'],
                    'min_people_per_reservation' => 2,
                    'max_people_per_reservation' => 8,
                    'min_people_increments_per_reservation' => 2,
                    'max_reservations_per_time_slot' => 10,
                    'minimum_reservation_time_buffer' => 60,
                    'maximum_reservation_time_buffer' => 30,
                    'commission_per_cover' => 100,
                    'allow_gift_card_redemptions' => 1,
                    'reward_points_per_reservation' => 200,
                    'cuisines' => [1,3,4],
                ],

                'address' => [
                    'address' => 'This is a random Address',
                    'latitude' => '18.92285',
                    'longitude' => '72.831888',
                    'pin_code' => '110030'
                ],

                'media' => [
                    'listing_image' => 33,
                    'gallery_images' => [29,31,32]
                ],

                'schedules' => [
                    1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100
                ],

                'tags' => [5,9],

                'flags' => [1,3]
            ],
        ];

        foreach($restData as $data){
            $restaurantLocation->create($data);
        }

        echo 'Word!!';
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			//['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
