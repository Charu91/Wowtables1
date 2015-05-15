<?php namespace WowTables\Http\Requests\Admin;

use WowTables\Http\Requests\Request;
use WowTables\Http\Models\User;

class CreateRestaurantRequest extends Request {

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

        $rules['name'] = 'required';
        //$rules['slug'] = 'required|unique:vendors,slug';
        $rules['status'] = 'required|in:Publish,Draft';

        /*if($this->get('status') === 'Publish'){
            $rules['publish_date'] = 'date_format:Y-m-d'; //YYYY-MM-DD
            $rules['publish_time'] = 'date_format:H:i:s'; //HH:MM:SS
            $rules['attributes.restaurant_info'] = 'required';
            $rules['attributes.short_description'] = 'required';
            $rules['attributes.seo_title'] = 'required';
            $rules['attributes.seo_meta_description'] = 'required';
            $rules['attributes.seo_meta_keywords'] = 'required';
        }else{
            $rules['attributes.seo_meta_keywords'] = 'required';
        }*/

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
