<?php namespace WowTables\Http\Requests\Admin;

use WowTables\Http\Requests\Request;
use WowTables\Http\Models\User;

class CreateUserRequest extends Request {

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
		return $this->user->can('create', 'user');
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
            'password' => 'required|between:6,15',
            'full_name' => 'required',
            'location_id' => 'required|exists:locations,id',
            'role_id' => 'required|exists:roles,id',
            'phone_number' => 'required|numeric|digits_between:6,20',
            'attributes' => 'array',
            'newsletter_frequency' => 'in:Daily,Weekly,Never',
            'attributes.date_of_birth' => 'date_format:Y-m-d',
            'attributes.preferences' => 'array',
            'attributes.single_something' => 'exists:user_attributes_select_options,id',
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
