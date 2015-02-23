<?php namespace WowTables\Http\Requests;



class CreatePageRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'main_content' => 'required',
            'slug' => 'required|unique:pages',
            'seo_title' => 'required',
            'seo_meta_description' => 'required',
            'seo_meta_keywords' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


}
