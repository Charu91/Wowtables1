<?php namespace WowTables\Http\Requests;

use WowTables\Http\Requests\Request;

class CreateUserAttributeRequest extends Request {

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
			'name'  => 'required|unique:user_attributes',
			'alias' => 'required|unique:user_attributes',
			'type'  => 'required|in:boolean,datetime,float,integer,multiselect,select_options,singleselect,text,varchar'
		];
	}

}
