<?php namespace WowTables\Commands\Admin;

use WowTables\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation;

/**
 * Class RegisterUserCommand
 * @package WowTables\Commands\Site
 */
class CreateRestaurantLocationCommand extends Command implements SelfHandling {

    protected   $vendor_id ,
                $location_id,
                $title,
                $slug,
                $status,
                $short_description,
                $description,
                $seo_title,
                $seo_meta_description,
                $seo_meta_keywords,
                $main_image,
                $listing_image,
                $gallery_images,
                $min_people_per_reservation,
                $max_people_per_reservation,
                $max_reservation_per_time_slot,
                $max_reservation_per_day,
                $min_reservation_time_buffer,
                $max_reservation_time_buffer,
                $schedules,
                $allow_alacarte_reservation,
                $alacarte_terms_conditions,
                $address,
                $city,
                $state,
                $country,
                $pin_code,
                $latitude,
                $longitude,
                $driving_locations,
                $location_map,
                $cuisine,
                $collections,
                $commision_per_reservation,
                $prepayment,
                $reward_points_per_reservation,
                $publish_date,
                $publish_time;

    function __construct($vendor_id, $location_id, $title, $slug, $status, $short_description, $description, $seo_title, $seo_meta_description, $seo_meta_keywords, $main_image, $listing_image, $gallery_images, $min_people_per_reservation, $max_people_per_reservation, $max_reservation_per_time_slot, $max_reservation_per_day, $min_reservation_time_buffer, $max_reservation_time_buffer, $schedules, $allow_alacarte_reservation, $alacarte_terms_conditions, $address, $city, $state, $country, $pin_code, $latitude, $longitude, $driving_locations, $location_map, $cuisine, $collections, $commision_per_reservation, $prepayment, $reward_points_per_reservation, $publish_date, $publish_time)
    {
        $this->vendor_id = $vendor_id;
        $this->location_id = $location_id;
        $this->title = $title;
        $this->slug = $slug;
        $this->status = $status;
        $this->short_description = $short_description;
        $this->description = $description;
        $this->seo_title = $seo_title;
        $this->seo_meta_description = $seo_meta_description;
        $this->seo_meta_keywords = $seo_meta_keywords;
        $this->main_image = $main_image;
        $this->listing_image = $listing_image;
        $this->gallery_images = $gallery_images;
        $this->min_people_per_reservation = $min_people_per_reservation;
        $this->max_people_per_reservation = $max_people_per_reservation;
        $this->max_reservation_per_time_slot = $max_reservation_per_time_slot;
        $this->max_reservation_per_day = $max_reservation_per_day;
        $this->min_reservation_time_buffer = $min_reservation_time_buffer;
        $this->max_reservation_time_buffer = $max_reservation_time_buffer;
        $this->schedules = $schedules;
        $this->allow_alacarte_reservation = $allow_alacarte_reservation;
        $this->alacarte_terms_conditions = $alacarte_terms_conditions;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->pin_code = $pin_code;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->driving_locations = $driving_locations;
        $this->location_map = $location_map;
        $this->cuisine = $cuisine;
        $this->collections = $collections;
        $this->commision_per_reservation = $commision_per_reservation;
        $this->prepayment = $prepayment;
        $this->reward_points_per_reservation = $reward_points_per_reservation;
        $this->publish_date = $publish_date;
        $this->publish_time = $publish_time;
    }


    /**
     * Execute the command.
     *
     * @param RestaurantLocationsRepository $repository
     * @param VendorLocation $vendorLocation
     * @return array
     */
    public function handle(RestaurantLocationsRepository $repository,VendorLocation $vendorLocation)
    {
        $restaurantLocation = $vendorLocation->add(
                        $this->vendor_id,
                        $this->location_id,
                        $this->title,
                        $this->slug,
                        $this->status,
                        $this->short_description,
                        $this->description,
                        $this->seo_title,
                        $this->seo_meta_description,
                        $this->seo_meta_keywords,
                        $this->main_image,
                        $this->listing_image,
                        $this->gallery_images,
                        $this->min_people_per_reservation,
                        $this->max_people_per_reservation,
                        $this->max_reservation_per_time_slot,
                        $this->max_reservation_per_day,
                        $this->min_reservation_time_buffer,
                        $this->max_reservation_time_buffer,
                        $this->schedules,
                        $this->allow_alacarte_reservation,
                        $this->alacarte_terms_conditions,
                        $this->address,
                        $this->city,
                        $this->state,
                        $this->country,
                        $this->pin_code,
                        $this->latitude,
                        $this->longitude,
                        $this->driving_locations,
                        $this->location_map,
                        $this->cuisine,
                        $this->collections,
                        $this->commision_per_reservation,
                        $this->prepayment,
                        $this->reward_points_per_reservation,
                        $this->publish_date,
                        $this->publish_time
        );

        $response = $repository->add($restaurantLocation);

        //event(new NewUserWasRegistered($this->email,$this->full_name));

        return $response;
    }

}
