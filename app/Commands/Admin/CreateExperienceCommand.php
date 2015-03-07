<?php namespace WowTables\Commands\Admin;

use WowTables\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Http\Models\Eloquent\Products\Product;

/**
 * Class RegisterUserCommand
 * @package WowTables\Commands\Site
 */
class CreateExperienceCommand extends Command implements SelfHandling {

    protected   $vendor_id ,
                $product_type_id,
                $name,
                $slug,
                $status,
                $restaurant_locations,
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
                $commision_per_reservation,
                $prepayment,
                $reward_points_per_reservation,
                $tax,
                $price_before_tax,
                $price_after_tax,
                $price_type,
                $commission_calculated_on,
                $addons,
                $allow_gift_card_redemption,
                $allow_cancellations,
                $terms_conditions,
                $publish_date,
                $publish_time;

    function __construct($vendor_id, $product_type_id, $name, $slug, $status, $restaurant_locations, $short_description, $description, $seo_title, $seo_meta_description, $seo_meta_keywords, $main_image, $listing_image, $gallery_images, $min_people_per_reservation, $max_people_per_reservation, $max_reservation_per_time_slot, $max_reservation_per_day, $min_reservation_time_buffer, $max_reservation_time_buffer, $commision_per_reservation, $prepayment, $reward_points_per_reservation, $tax, $price_before_tax, $price_after_tax, $price_type, $commission_calculated_on, $addons, $allow_gift_card_redemption, $allow_cancellations, $terms_conditions, $publish_date, $publish_time)
    {
        $this->vendor_id = $vendor_id;
        $this->product_type_id = $product_type_id;
        $this->name = $name;
        $this->slug = $slug;
        $this->status = $status;
        $this->restaurant_locations = $restaurant_locations;
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
        $this->commision_per_reservation = $commision_per_reservation;
        $this->prepayment = $prepayment;
        $this->reward_points_per_reservation = $reward_points_per_reservation;
        $this->tax = $tax;
        $this->price_before_tax = $price_before_tax;
        $this->price_after_tax = $price_after_tax;
        $this->price_type = $price_type;
        $this->commission_calculated_on = $commission_calculated_on;
        $this->addons = $addons;
        $this->allow_gift_card_redemption = $allow_gift_card_redemption;
        $this->allow_cancellations = $allow_cancellations;
        $this->terms_conditions = $terms_conditions;
        $this->publish_date = $publish_date;
        $this->publish_time = $publish_time;
    }


    /**
     * Execute the command.
     *
     * @param RestaurantLocationsRepository|ExperiencesRepository $repository
     * @param Product $product
     * @return array
     */
    public function handle(ExperiencesRepository $repository,Product $product)
    {
        $experience = $product->add(
            $this->vendor_id,
            $this->product_type_id,
            $this->name,
            $this->slug,
            $this->status,
            $this->restaurant_locations,
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
            $this->commision_per_reservation,
            $this->prepayment,
            $this->reward_points_per_reservation,
            $this->tax,
            $this->price_before_tax,
            $this->price_after_tax,
            $this->price_type,
            $this->commission_calculated_on,
            $this->addons,
            $this->allow_gift_card_redemption,
            $this->allow_cancellations,
            $this->terms_conditions,
            $this->publish_date,
            $this->publish_time
        );

        $response = $repository->add($experience);

        //event(new NewUserWasRegistered($this->email,$this->full_name));

        return $response;
    }

}
