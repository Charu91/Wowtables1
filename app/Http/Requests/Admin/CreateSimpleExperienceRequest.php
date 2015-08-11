<?php namespace WowTables\Http\Requests\Admin;

use WowTables\Http\Requests\Request;
use WowTables\Http\Models\User;
use DB;

class CreateSimpleExperienceRequest extends Request {

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
            $rules['attributes.seo_meta_desciption'] = 'required';
            $rules['attributes.seo_meta_keywords'] = 'required';
            //$rules['attributes.allow_gift_card_redemptions'] = 'required|boolean';
            //$rules['attributes.prepayment_allowed'] = 'required|boolean';
            $rules['attributes.reward_points_per_reservation'] = 'required|integer';
            //$rules['attributes.curator_tip'] = 'required';
            $rules['attributes.cuisines'] = 'required|productcuisinesarray';
            $rules['attributes.start_date'] = 'required';
            $rules['attributes.end_date'] = 'required';

            $rules['pricing.price'] = 'required|numeric';
            $rules['pricing.post_tax_price'] = 'required|numeric';
            $rules['pricing.tax'] = 'required|numeric';
            $rules['pricing.commission'] = 'required|numeric';
            $rules['pricing.commission_on'] = 'required|in:Pre-Tax,Post-Tax';

            $rules['media.listing_image'] = 'required|exists:media,id';
            $rules['media.gallery_images'] = 'required|galleryarray';
        } else {
            //$rules['attributes.prepayment_allowed'] = 'boolean';
            //$rules['attributes.allow_gift_card_redemptions'] = 'boolean';
            //$rules['attributes.reward_points_per_reservation'] = 'integer';
            //$rules['attributes.cuisines'] = 'productcuisinesarray';

            //$rules['attributes.start_date'] = 'date_format:Y-m-d';
            //$rules['attributes.end_date'] = 'date_format:Y-m-d';

            //$rules['pricing.price'] = 'numeric';
            //$rules['pricing.post_tax_price'] = 'numeric';
            //$rules['pricing.tax'] = 'numeric';
            //$rules['pricing.commission'] = 'numeric';
            //$rules['pricing.commission_on'] = 'in:Pre-Tax,Post-Tax';

            //$rules['media.listing_image'] = 'exists:media,id';
            //$rules['media.gallery_images'] = 'galleryarray';
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
                $rules['addons.'.$key.'.short_description'] = 'required_with:addons';
                //$rules['addons.'.$key.'.addonsMenu'] = 'required_with:addons';
            }
        }


        //$rules['curators'] = 'required';
        //$rules['curator.id'] = 'required_with:curator|exists:curators,id';
        //$rules['curator.tips'] ='required_with:curator.id';

        //$rules['tags'] = 'tagarray';
        //$rules['flags'] = 'required';

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
