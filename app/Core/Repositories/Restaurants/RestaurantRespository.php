<?php namespace WowTables\Core\Repositories\Restaurants;

use WowTables\Http\Models\Eloquent\Vendors\Vendor;

class RestaurantRepository {


    protected $attributes = [];

    public function getAll()
    {
        return Vendor::wherehas('vendorType', function($q)
        {
            $q->where('type','Restaurants');
        })->get();
    }

    public function getByRestaurantId($id)
    {
        $vendorWithAttributes = Vendor::with(
            'attributesBoolean',
            'attributesDate',
            'attributesInteger',
            'attributesFloat',
            'attributesText',
            'attributesVarChar',
            'attributesSingleSelect',
            'attributesMultiSelect'
        )->findOrFail($id);
        /*->wherehas('vendorType',function($q) {
            $q->where('type','Restaurants');
        })->first();*/

        $this->populateVendorAttributes($vendorWithAttributes->attributesBoolean);
        $this->populateVendorAttributes($vendorWithAttributes->attributesInteger);
        $this->populateVendorAttributes($vendorWithAttributes->attributesFloat);
        $this->populateVendorAttributes($vendorWithAttributes->attributesDate);
        $this->populateVendorAttributes($vendorWithAttributes->attributesText);
        $this->populateVendorAttributes($vendorWithAttributes->attributesVarChar);
        $this->populateVendorAttributes($vendorWithAttributes->attributesSingleSelect);
        $this->populateVendorAttributes($vendorWithAttributes->attributesMultiSelect);

        return [ 'restaurant' => Vendor::find($id), 'attributes' => $this->attributes];
    }

    public function populateVendorAttributes ( $vendorAttributes )
    {

        foreach($vendorAttributes as $attribute)
        {
            $name  = $attribute->attribute->alias;
            $value = $attribute->attribute_value;

            $this->attributes[$name] = $value;
        }

    }
}
