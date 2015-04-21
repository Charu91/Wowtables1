<?php namespace WowTables\Http\Requests;

use WowTables\Http\Requests\Request;

class CreateFlagRequest extends Request {

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
            'name'  => 'required|unique:flags',
            'color' => 'required|in:Red,Blue,Green,Yellow,Black,White',
        ];
    }

}
