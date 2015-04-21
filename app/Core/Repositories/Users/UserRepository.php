<?php namespace WowTables\Core\Repositories\Users;

use WowTables\Http\Models\Eloquent\User;

class UserRepository {

    protected $attributes = [];

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

            $this->populateUserAttributes($userWithAttributes->attributesBoolean);
            $this->populateUserAttributes($userWithAttributes->attributesInteger);
            $this->populateUserAttributes($userWithAttributes->attributesFloat);
            $this->populateUserAttributes($userWithAttributes->attributesDate);
            $this->populateUserAttributes($userWithAttributes->attributesText);
            $this->populateUserAttributes($userWithAttributes->attributesVarChar);
            $this->populateUserAttributes($userWithAttributes->attributesSingleSelect);
            $this->populateUserAttributes($userWithAttributes->attributesMultiSelect);

        return [ 'user' => User::find($id), 'attributes' => $this->attributes];
    }

    public function populateUserAttributes ( $userAttributes )
    {

        foreach($userAttributes as $attribute)
        {
            $name  = $attribute->attribute->alias;
            $value = $attribute->attribute_value;

            $this->attributes[$name] = $value;
        }

    }
}
