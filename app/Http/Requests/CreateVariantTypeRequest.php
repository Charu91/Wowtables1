<?php namespace WowTables\Http\Requests;

use WowTables\Http\Requests\Request;

class CreateVariantTypeRequest extends Request {

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
            'variation_name'  => 'required|unique:product_variant_options',
            'variant_alias' => 'required|unique:product_variant_options',
        ];
    }

}
