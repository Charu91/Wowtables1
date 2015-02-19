<?php namespace WowTables\Http\Requests\Site;

use WowTables\Http\Requests\Request;

class CustomerRegisterUserRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email' => 'required|email|unique:users',
			'password' => 'required|min:6|max:15|confirmed',
			'full_name' => 'required',
			'terms_of_use' => 'accepted'
		];
	}


}
