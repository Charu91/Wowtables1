<?php namespace WowTables\Http\Requests\Admin;

use WowTables\Http\Requests\Request;
use WowTables\Http\Models\User;
use DB;

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
        $rules['location_id'] = 'required|integer|exists:locations,id,type,Locality|unique:vendor_locations,location_id,NULL,id,vendor_id,'.$this->get('restaurant_id'); // Locality Id
        $rules['slug'] = 'required|unique:vendor_locations,slug';
        $rules['status'] = 'required|in:Active,Inactive';

        if(($this->has('status') && $this->get('status') === 'Active') && ($this->has('a_la_carte') && $this->get('a_la_carte') === '1')){
            $rules['pricing_level'] = 'required|in:Low,Medium,High';
            $rules['attributes.restaurant_info'] = 'required';
            $rules['attributes.short_description'] = '';
            $rules['attributes.terms_and_conditions'] = 'required';
            $rules['attributes.menu_picks'] = 'required';
            $rules['attributes.expert_tips'] = 'required';
            $rules['attributes.seo_title'] = 'required';
            $rules['attributes.seo_meta_description'] = 'required';
            $rules['attributes.seo_meta_keywords'] = 'required';
            $rules['location_attributes.min_people_per_reservation'] = 'required|integer';
            $rules['location_attributes.max_people_per_reservation'] = 'required|integer';
            $rules['location_attributes.min_people_increments_per_reservation'] = 'required|integer';
            $rules['location_attributes.max_reservations_per_day'] = 'required|integer';
            $rules['location_attributes.max_reservations_per_time_slot'] = 'integer';
            $rules['location_attributes.minimum_reservation_time_buffer'] = 'integer';
            $rules['location_attributes.maximum_reservation_time_buffer'] = 'integer';
            $rules['attributes.commission_per_cover'] = 'required|numeric';
            $rules['attributes.allow_gift_card_redemptions'] = 'boolean';
            $rules['attributes.reward_points_per_reservation'] = 'required|integer';
            $rules['attributes.cuisines'] = 'vendorcuisinesarray';
            $rules['address.address'] = 'required';
            $rules['address.pin_code'] = 'required';
            $rules['address.latitude'] = 'required|numeric';
            $rules['address.longitude'] = 'required|numeric';

            //$rules['schedules'] = 'required|array';

            $rules['media.listing_image'] = 'required';
            $rules['media.gallery_images'] = 'required';
        }

        //if($this->has('status') && $this->get('status') === 'Active'){
            //$rules['a_la_carte'] = 'required|boolean';
            /*$rules['pricing_level'] = 'required|in:Low,Medium,High';
            $rules['attributes.restaurant_info'] = 'required';
            $rules['attributes.short_description'] = '';
            $rules['attributes.terms_and_conditions'] = 'required';
            $rules['attributes.menu_picks'] = 'required';
            $rules['attributes.expert_tips'] = 'required';
            $rules['attributes.seo_title'] = 'required';
            $rules['attributes.seo_meta_description'] = 'required';
            $rules['attributes.seo_meta_keywords'] = 'required';
            $rules['location_attributes.min_people_per_reservation'] = 'required|integer';
            $rules['location_attributes.max_people_per_reservation'] = 'required|integer';
            $rules['location_attributes.min_people_increments_per_reservation'] = 'required|integer';
            $rules['location_attributes.max_reservations_per_day'] = 'required|integer';
            $rules['location_attributes.max_reservations_per_time_slot'] = 'integer';
            $rules['location_attributes.minimum_reservation_time_buffer'] = 'integer';
            $rules['location_attributes.maximum_reservation_time_buffer'] = 'integer';
            $rules['attributes.commission_per_cover'] = 'required|numeric';
            $rules['attributes.allow_gift_card_redemptions'] = 'boolean';
            $rules['attributes.reward_points_per_reservation'] = 'required|integer';
            $rules['attributes.cuisines'] = 'vendorcuisinesarray';
            $rules['address.address'] = 'required';
            $rules['address.pin_code'] = 'required';
            $rules['address.latitude'] = 'required|numeric';
            $rules['address.longitude'] = 'required|numeric';

            //$rules['schedules'] = 'required|array';

            $rules['media.listing_image'] = 'required';
            $rules['media.gallery_images'] = 'required';*/

        //}else{
            /*$rules['a_la_carte'] = 'boolean';
            $rules['pricing_level'] = 'in:Low,Medium,High';
            $rules['attributes.seo_meta_keywords'] = 'required';
            $rules['location_attributes.min_people_per_reservation'] = 'integer';
            $rules['location_attributes.max_people_per_reservation'] = 'integer';
            $rules['location_attributes.max_people_per_day'] = 'integer';
            $rules['location_attributes.min_people_increments_per_reservation'] = 'integer';
            $rules['location_attributes.minimum_reservation_time_buffer'] = 'integer';
            $rules['location_attributes.maximum_reservation_time_buffer'] = 'integer';
            $rules['attributes.commission_per_cover'] = 'numeric';
            $rules['attributes.allow_gift_card_redemptions'] = 'boolean';
            $rules['attributes.reward_points_per_reservation'] = 'integer';
            $rules['attributes.cuisines'] = 'vendorcuisinesarray';
            $rules['address.latitude'] = 'numeric';
            $rules['address.longitude'] = 'numeric';

            //$rules['schedules'] = 'array';

            $rules['media.listing_image'] = 'required';
            $rules['media.gallery_images'] = 'required';*/
        //}

        /*if($this->has('schedules') && is_array($this->get('schedules'))){
            $schedule_ids = DB::table('schedules')->lists('id');
            foreach($this->get('schedules') as $key => $schedule){
                $rules['schedules.'.$key.'.id'] = 'required_with:schedules, in'.implode(',',$schedule_ids);
                $rules['schedules.'.$key.'.off_peak'] ='required_with:schedules|boolean';
                $rules['schedules.'.$key.'.max_reservations'] = 'required_with:schedules|integer';
            }
        }*/

        $rules['attributes.off_peak_hour_discount_min_covers'] = 'integer';
        $rules['attributes.max_reservations_per_time_slot'] = 'integer';

        /*$rules['block_dates'] = 'array';

        if($this->has('block_dates') && is_array($this->get('block_dates'))){
            foreach($this->get('block_dates') as $key => $block_date){
                $rules['block_dates.'.$key] = 'required';
            }
        }*/

        $rules['reset_time_range_limits'] = 'array';

        if($this->has('reset_time_range_limits') && is_array($this->get('reset_time_range_limits'))){
            foreach($this->get('reset_time_range_limits') as $key => $range){
                $rules['reset_time_range_limits.'.$key.'.from_time'] = 'required_with:reset_time_range_limits|date_format:H:i:s'; //HH:MM:SS
                $rules['reset_time_range_limits.'.$key.'.to_time'] = 'required_with:reset_time_range_limits|date_format:H:i:s'; //HH:MM:SS
                $rules['reset_time_range_limits.'.$key.'.limit_by'] = 'required_with:reset_time_range_limits|in:Day,Date';
                //$rules['reset_time_range_limits.'.$key.'.max_reservations_limit'] = 'required_with:reset_time_range_limits|integer';
                if(isset($range['limit_by'])){
                    if($range['limit_by'] === 'Day'){
                        $rules['reset_time_range_limits.'.$key.'.day'] = 'required_with:'.'reset_time_range_limits.'.$key.'.limit_by|in:mon,tue,wed,thu,fri,sat,sun';
                    }else if($range['limit_by'] === 'Date'){
                        $rules['reset_time_range_limits.'.$key.'.date'] = 'required_with:'.'reset_time_range_limits.'.$key.'.limit_by|date_format:Y-m-d';
                    }
                }
            }
        }

        $rules['contacts'] = 'array';
        if($this->has('contacts') && is_array($this->get('contacts'))){
            foreach($this->get('contacts') as $key => $contact){
                $rules['contacts.'.$key.'.name'] = 'required_with:contacts';
                $rules['contacts.'.$key.'.designation'] = 'required_with:contacts';
                $rules['contacts.'.$key.'.phone_number'] = 'required_with:contacts|numeric';
                $rules['contacts.'.$key.'.email'] = 'required_with:contacts|email';

            }
        }

        //$rules['curator'] = 'array';
        //$rules['curator.id'] = 'required_with:curator|exists:curators,id';
        //$rules['curator.tips'] ='required_with:curator.id';


        //$rules['tags'] = 'tagarray';
        //$rules['flags'] = 'flagarray';


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
        //dd($errors);
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
