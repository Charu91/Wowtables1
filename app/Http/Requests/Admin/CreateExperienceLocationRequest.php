<?php namespace WowTables\Http\Requests\Admin;

use WowTables\Http\Requests\Request;
use WowTables\Http\Models\User;
use DB;

class CreateExperienceLocationRequest extends Request {

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
        return $this->user->can('create', 'experiences');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['experience_id'] = 'required|integer|exists:products,id';
        $rules['restaurant_location_id'] = 'required|array|exists:vendor_locations,id|unique:product_vendor_locations,vendor_location_id,NULL,id,product_id.'.$this->get('experience_id');
        $rules['status'] = 'required|in:Active,Inactive,Hidden';

        if($this->has('status') && $this->get('status') === 'Active'){
            $rules['attributes.min_people_per_reservation'] = 'required|integer';
            $rules['attributes.max_people_per_reservation'] = 'required|integer';
            $rules['attributes.max_reservations_per_day'] = 'required|integer';
            $rules['attributes.minimum_reservation_time_buffer'] = 'required|integer';
            $rules['attributes.maximum_reservation_time_buffer'] = 'required|integer';
            $rules['attributes.min_people_increments_per_reservation'] = 'required|integer';

            $rules['schedules'] = 'required|array';
        }else{
            $rules['attributes.min_people_per_reservation'] = 'integer';
            $rules['attributes.max_people_per_reservation'] = 'integer';
            $rules['attributes.max_reservations_per_day'] = 'integer';
            $rules['attributes.minimum_reservation_time_buffer'] = 'integer';
            $rules['attributes.maximum_reservation_time_buffer'] = 'integer';
            $rules['attributes.min_people_increments_per_reservation'] = 'integer';

            //$rules['schedules'] = 'array';
        }

        /*if($this->has('schedules') && is_array($this->get('schedules'))){
            $schedule_ids = DB::table('schedules')->lists('id');
            foreach($this->get('schedules') as $key => $schedule){
                $rules['schedules'.$key.'id'] = 'required_with:schedules, in'.implode(',',$schedule_ids);
                //$rules['schedules'.$key.'max_reservations'] = 'required_with:schedules|integer';
            }
        }*/

        /*$rules['block_dates'] = 'array';

        if($this->has('block_dates') && is_array($this->get('block_dates'))){
            foreach($this->get('block_dates') as $key => $block_date){
                $rules['block_dates'.$key] = 'required';
            }
        }*/

        $rules['reset_time_range_limits'] = 'array';

        if($this->has('reset_time_range_limits') && is_array($this->get('reset_time_range_limits'))){
            foreach($this->get('reset_time_range_limits') as $key => $range){
                $rules['reset_time_range_limits.'.$key.'.from_time'] = 'required_with:reset_time_range_limits|date_format:H:i:s'; //HH:MM:SS
                $rules['reset_time_range_limits.'.$key.'.to_time'] = 'required_with:reset_time_range_limits|date_format:H:i:s'; //HH:MM:SS
                $rules['reset_time_range_limits.'.$key.'.limit_by'] = 'required_with:reset_time_range_limits|in:Day.Date';
                $rules['reset_time_range_limits.'.$key.'.max_covers_limit'] = 'required_with:reset_time_range_limits|integer';
                $rules['reset_time_range_limits.'.$key.'.max_tables_limit'] = 'required_with:reset_time_range_limits|integer';
                if(isset($range['limit_by'])){
                    if($range['limit_by'] === 'Day'){
                        $rules['reset_time_range_limits.'.$key.'.day'] = 'required_with:reset_time_range_limits.'.$key.'.limit_by|in:mon,tue,wed,thu,fri,sat,sun';
                    }else if($range['limit_by'] === 'Date'){
                        $rules['reset_time_range_limits.'.$key.'.date'] = 'required_with:reset_time_range_limits.'.$key.'.limit_by|date_format:Y-m-d';
                    }
                }
            }
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
