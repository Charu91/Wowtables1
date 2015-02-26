<?php namespace WowTables\Http\Requests;

use WowTables\Http\Models\User;

class CreateRestaurantLocationRequest extends Request {


    /**
     * The property to hold the user object
     *
     * @var Object
     */
    protected $user;

    /**
     * The constructor method
     *
     * @param User $user
     */
    public function __constructor(User $user)
    {
        $this->user = $user;
    }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [

		];
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

}
