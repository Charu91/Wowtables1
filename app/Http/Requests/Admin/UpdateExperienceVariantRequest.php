<?php namespace WowTables\Http\Requests\Admin;

use WowTables\Http\Requests\Request;
use WowTables\Http\Models\User;

class UpdateExperienceVariantRequest extends Request {

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
        return $this->user->can('update', 'experiences');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $experience_id = $this->route()->getParameter('id');

        $rules['name'] = 'required';
        $rules['slug'] = 'required|unique:products,slug,'.$experience_id;
        $rules['visibility'] = 'required|boolean';

        if($this->has('visibility') && $this->get('visibility')){
            $rules['attributes.short_description'] = 'required';
            $rules['attributes.menu'] = 'required';
            $rules['attributes.menu_markdown'] = 'required';
            $rules['pricing.price'] = 'required|numeric';
            $rules['pricing.post_tax_price'] = 'required|numeric';
            $rules['pricing.tax'] = 'required|numeric';
            $rules['pricing.commission_per_cover'] = 'required|numeric';
            $rules['pricing.commission_on'] = 'required|in:Pre-Tax,Post-Tax';
            $rules['mapping.complex_product_id'] = 'required|exists:products,id,type,complex';
            $rules['mapping.variant_option_id'] = 'required_with:complex_product_id|exists:product_variant_options,id';
        }else{
            $rules['pricing.price'] = 'numeric';
            $rules['pricing.post_tax_price'] = 'numeric';
            $rules['pricing.tax'] = 'numeric';
            $rules['pricing.commission_per_cover'] = 'numeric';
            $rules['pricing.commission_on'] = 'in:Pre-Tax,Post-Tax';
            $rules['mapping.complex_product_id'] = 'exists:products,id,type,complex';
            $rules['mapping.variant_option_id'] = 'required_with:complex_product_id|exists:product_variant_options,id';
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
