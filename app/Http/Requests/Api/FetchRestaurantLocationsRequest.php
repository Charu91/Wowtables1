<?php namespace WowTables\Http\Requests\Api;

use WowTables\Http\Requests\Request;

class FetchRestaurantLocationsRequest extends Request {

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
            'filters' => 'required|array',
            'filters.city_id' => 'required_with:filters|integer|exists:locations,id,type,City'
		];
	}

    public function response(array $errors)
    {
        return response()->json([
            'action' => 'Check for errors in the data sent',
            'message' => 'There were errors in the input sent. Please check your request and try again',
            'errors' => $errors
        ], 422);
    }

}
