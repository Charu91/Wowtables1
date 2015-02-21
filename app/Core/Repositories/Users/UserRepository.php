<?php namespace WowTables\Core\Repositories\Users;

use WowTables\Http\Models\Eloquent\User;

class UserRepository {

    public function getByUserId($id)
    {
        $userWithAttributes = User::with
            (
                'role',
                'attributesBoolean',
                'attributesDate',
                'attributesInteger',
                'attributesFloat',
                'attributesText',
                'attributesVarChar',
                'attributesSingleSelect',
                'attributesMultiSelect'
            )->find($id);

        foreach($userWithAttributes->attributesBoolean as $value)
        {
            $name  = $value->attribute->alias;
            $value = $value->attribute_value;

            $boolean_attributes[$name] = $value;
        }

        foreach($userWithAttributes->attributesInteger as $value)
        {
            $name  = $value->attribute->alias;
            $value = $value->attribute_value;

            $integer_attributes[$name] = $value;
        }
        foreach($userWithAttributes->attributesFloat as $value)
        {
            $name  = $value->attribute->alias;
            $value = $value->attribute_value;

            $float_attributes[$name] = $value;
        }
        foreach($userWithAttributes->attributesDate as $value)
        {
            $name  = $value->attribute->alias;
            $value = $value->attribute_value;

            $date_attributes[$name] = $value;
        }
        foreach($userWithAttributes->attributesText as $value)
        {
            $name  = $value->attribute->alias;
            $value = $value->attribute_value;

            $text_attributes[$name] = $value;
        }
        foreach($userWithAttributes->attributesVarChar as $value)
        {
            $name  = $value->attribute->alias;
            $value = $value->attribute_value;

            $varchar_attributes[$name] = $value;
        }
        foreach($userWithAttributes->attributesSingleSelect as $value)
        {
            $name  = $value->attribute->alias;
            $value = $value->attribute_value;

            $singleselect_attributes[$name] = $value;
        }

        foreach($userWithAttributes->attributesMultiSelect as $value)
        {
            $name  = $value->attribute->alias;
            $value = $value->attribute_value;

            $multiselect_attributes[$name] = $value;
        }

        return [
            User::find($id),
            $boolean_attributes,
            $date_attributes
        ];
    }
}
