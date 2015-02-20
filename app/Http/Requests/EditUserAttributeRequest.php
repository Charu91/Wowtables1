<?php namespace WowTables\Http\Requests;

use WowTables\Http\Requests\Request;

class EditUserAttributeRequest extends Request {

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
            'alias' => 'required',
            'type'  => 'required|in:boolean,datetime,float,integer,multiselect,select_options,singleselect,text,varchar'
        ];
    }

}
