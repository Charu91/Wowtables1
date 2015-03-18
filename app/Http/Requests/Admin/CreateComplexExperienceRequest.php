<?php namespace WowTables\Http\Requests\Admin;

use WowTables\Http\Requests\Request;
use WowTables\Http\Models\User;
use DB;

class CreateComplexExperienceRequest extends Request {

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

        $rules['name'] = 'required';
        $rules['slug'] = 'required|unique:products,slug';
        $rules['status'] = 'required|in:Publish,Draft';
        $rules['media.listing_image'] = 'required|exists:media,id';
        $rules['media.gallery_images'] = 'required|galleryarray';

        if ($this->has('status') && $this->get('status') === 'Publish') {
            $rules['publish_date'] = 'date_format:Y-m-d'; //YYYY-MM-DD
            $rules['publish_time'] = 'required_with:publish_date|date_format:H:i:s'; //HH:MM:SS

            $rules['attributes.experience_info'] = 'required';
            $rules['attributes.experience_includes'] = 'required';
            $rules['attributes.short_description'] = 'required';
            $rules['attributes.terms_and_conditions'] = 'required';
            $rules['attributes.menu'] = 'required';
            $rules['attributes.menu_markdown'] = 'required';
            $rules['attributes.seo_title'] = 'required';
            $rules['attributes.seo_meta_description'] = 'required';
            $rules['attributes.seo_meta_keywords'] = 'required|array';
            $rules['attributes.allow_gift_card_redemptions'] = 'required|boolean';
            $rules['attributes.prepayment_allowed'] = 'required|boolean';
            $rules['attributes.reward_points_per_reservation'] = 'required|integer';
            $rules['attributes.curator_tip'] = 'required';
            $rules['attributes.cuisines'] = 'required|productcuisinesarray';
        } else {
            $rules['attributes.prepayment_allowed'] = 'boolean';
            $rules['attributes.allow_gift_card_redemptions'] = 'boolean';
            $rules['attributes.reward_points_per_reservation'] = 'integer';
            $rules['attributes.cuisines'] = 'productcuisinesarray';
        }

        $rules['addons'] = 'array';

        if($this->has('addons') && is_array($this->get('addons'))){
            foreach($this->get('addons') as $key => $addon){
                $rules['addons.'.$key.'.name'] = 'required_with:addons';
                $rules['addons.'.$key.'.price'] = 'required_with:addons|numeric';
                $rules['addons.'.$key.'.tax'] = 'required_with:addons|numeric';
                $rules['addons.'.$key.'.post_tax_price'] = 'required_with:addons|numeric';
                $rules['addons.'.$key.'.commission_per_cover'] = 'required|numeric';
                $rules['addons.'.$key.'.commission_on'] = 'required|in:Pre-Tax,Post-Tax';
                $rules['addons.'.$key.'.experience_info'] = 'required_with:addons';
            }
        }


        $rules['curators'] = 'curatorarray';
        $rules['tags'] = 'tagarray';
        $rules['flags'] = 'flagarray';

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
