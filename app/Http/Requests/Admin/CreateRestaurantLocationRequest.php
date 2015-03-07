<?php namespace WowTables\Http\Requests\Admin;

use WowTables\Http\Requests\Request;

class CreateRestaurantLocationRequest extends Request {

    /**
     * The user model object
     *
     * @var Object
     */
    protected $user;

    /**
     * The constructor object
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user->can('create', 'restaurant');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['restaurant_id'] = 'required|integer|restaurant';
        $rules['location_id'] = 'required|integer|exists:locations,id,type,Area';
        $rules['slug'] = 'required|unique:vendors,slug';
        $rules['status'] = 'required|in:Publish,Draft';

        if($this->get('status') === 'Publish'){
            $rules['publish_date'] = 'date_format:Y-m-d'; //YYYY-MM-DD
            $rules['publish_time'] = 'required_with:publish_date|date_format:H:i:s'; //HH:MM:SS
            $rules['attributes.restaurant_info'] = 'required';
            $rules['attributes.short_description'] = 'required';
            $rules['attributes.terms_and_conditions'] = 'required';
            $rules['attributes.menu_picks'] = 'required';
            $rules['attributes.expert_tips'] = 'required';
            $rules['attributes.seo_title'] = 'required';
            $rules['attributes.seo_meta_description'] = 'required';
            $rules['attributes.seo_meta_keywords'] = 'required|nonemptyarray';
            $rules['attributes.min_people_per_reservation'] = 'required|integer';
            $rules['attributes.max_people_per_reservation'] = 'required|integer';
            $rules['attributes.max_reservations_per_time_slot'] = 'required|integer';
            $rules['attributes.max_people_per_day'] = 'required|integer';
            $rules['attributes.minimum_reservation_time_buffer'] = 'required|integer';
            $rules['attributes.maximum_reservation_time_buffer'] = 'required|integer';
            $rules['attributes.commission_per_cover'] = 'required|numeric';
            $rules['attributes.allow_gift_card_redemptions'] = 'required|boolean';
            $rules['attributes.reward_points_per_reservation'] = 'required|integer';
            $rules['address.address'] = 'required';
            $rules['address.pin_code'] = 'required';
            $rules['address.latitude'] = 'required|numeric';
            $rules['address.longitude'] = 'required|numeric';
            $rules['curators'] = 'required|curatorarray';
            $rules['tags'] = 'required|tagarray';
            $rules['media.listing_image'] = 'required|exists:media,id';
            $rules['media.gallery_images'] = 'required|galleryarray';
            $rules['schedules'] = 'required|schedulearray';
            $rules['block_dates'] = 'required|blockdatesarray';
            $rules['reset_time_range_limits'] = 'required|timerangelimits';
        }

        return $rules;
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->ajax())
        {
            return response()->json($errors, 422);
        }

        return redirect()->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }

    /**
     * Get the response for a forbidden operation.
     *
     * @return \Illuminate\Http\Response
     */
    public function forbiddenResponse()
    {
        return response('Forbidden', 403);
    }

}
