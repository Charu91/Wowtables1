<?php namespace WowTables\Http\Requests;

use WowTables\Http\Requests\Request;

class CreateCuratorRequest extends Request {

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
            'media_id'  => 'required|exists:media,id|unique:curators',
            'bio' => 'required',
            'link' => 'required|url',
            'location_id' => 'required|exists:locations,id,type,City',
        ];
    }

}
