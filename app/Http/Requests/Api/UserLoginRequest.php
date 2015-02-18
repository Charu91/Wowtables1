<?php namespace WowTables\Http\Requests\Api;

use WowTables\Http\Requests\Request;

class UserLoginRequest extends Request {

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
			'email'         => 'required|email',
            'password'      => 'required|between:6,15',
            'device_id'     => 'required',
            'os_type'       => 'required|in:iOS,Android',
            'os_version'    => 'required',
            'hardware'      => 'required',
            'app_version'   => 'required'
		];
	}

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return response()->json([
            'action' => 'Check for errors in the data sent',
            'message' => 'There were errors in the input sent. Please check your request and try again',
            'errors' => $errors
        ], 422);
    }

}
