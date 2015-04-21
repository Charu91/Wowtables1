<?php namespace WowTables\Http\Requests;


class CreateRestaurantLocationRequest extends Request {


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'vendor_id' => 'required|numeric',
			'location_id' => 'required|numeric',
			'title' => 'required',
			'slug'  => 'required|unique:vendor_locations',
			'status' => 'required|in:active,inactive',
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
			'schedules' => 'required|array',
			'allow_alacarte_reservation' => 'required',
			'alacarte_terms_conditions' => 'required',
			'address' => 'required',
			'city' => 'required|numeric',
			'state' => 'required|numeric',
			'country' => 'required|numeric',
			'pin_code' => 'required|numeric',
			'latitude' => 'required',
			'longitude' => 'required',
			'driving_locations' => 'required',
			'location_map' => 'required|array',
			'cuisine' => 'required|numeric',
			'collections' => 'array',
			'commision_per_reservation' => 'required|numeric',
			'prepayment' => 'required',
			'reward_points_per_reservation' => 'required|numeric',
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
