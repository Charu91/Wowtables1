<?php namespace WowTables\Http\Requests;



class UpdatePageRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page_title' => 'required',
            'page_contents' => 'required',
            'slug' => 'required',
            'seo_title' => 'required',
            'meta_desc' => 'required',
            'meta_keywords' => 'required'
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
