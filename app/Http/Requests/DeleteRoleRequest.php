<?php namespace WowTables\Http\Requests;

use WowTables\Http\Requests\Request;
use WowTables\Http\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class DeleteRoleRequest extends Request {

    /**
     * The route to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute;

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
		return false;
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
            return new JsonResponse($errors, 422);
        }

        return $this->redirector->to($this->getRedirectUrl())
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
        return new Response('Forbidden', 403);
    }

}
