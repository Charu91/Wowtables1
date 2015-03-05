<?php namespace WowTables\Http\Requests;

class CreateExperienceRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'vendor_id' => 'required|numeric',
            'product_type_id' => 'required|numeric',
            'restaurant_locations' => 'required|array',
            'name' => 'required',
            'slug'  => 'required|unique:products',
            'status' => 'required|in:Publish,Draft',
            'short_description' => 'required',
            'description' => 'required',
            'seo_title' => 'required',
            'seo_meta_description' => 'required',
            'seo_meta_keywords' => 'required',
            'main_image' => 'required|numeric',
            'listing_image' => 'required|numeric',
            'gallery_images' => 'required|array',
            'min_people_per_reservation' => 'required|numeric',
            'max_people_per_reservation' => 'required|numeric',
            'max_reservation_per_time_slot' => 'required|numeric',
            'max_reservation_per_day' => 'required|numeric',
            'min_reservation_time_buffer' => 'required|numeric',
            'max_reservation_time_buffer' => 'required|numeric',
            'commision_per_reservation' => 'required|numeric',
            'prepayment' => 'required',
            'reward_points_per_reservation' => 'required|numeric',
            'tax' => 'required|numeric',
            'price_before_tax' => 'required|numeric',
            'price_after_tax' => 'required|numeric',
            'price_type' => 'required',
            'commission_calculated_on' => 'required',
            'addons' => 'array',
            'allow_gift_card_redemption' => 'required',
            'allow_cancellations' => 'required',
            'terms_conditions' => 'required',
            'publish_date' => 'required',
            'publish_time' => 'required',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}


}
