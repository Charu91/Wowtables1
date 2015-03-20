<?php namespace WowTables\Http\Requests;

use WowTables\Http\Requests\Request;

class CreateCollectionRequest extends Request {

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
            'name'  => 'required',
            'slug'  => 'required|unique:tags',
            'description'  => 'required',
            'seo_title'  => 'required',
            'seo_meta_description'  => 'required',
            'seo_meta_keywords'  => 'required',
            'media_id'  => 'required|exists:media,id',
        ];
    }

}
