<?php namespace WowTables\Core\Repositories\Restaurants;

use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation;

class RestaurantLocationsRepository {


    protected $attributes = [];


    public function getAll()
    {
        return VendorLocation::all();
        /*return VendorLocation::wherehas('vendorType', function($q)
        {
            $q->where('type','Restaurants');
        })->get()*/;
    }

    public function getByRestaurantLocationId($id)
    {
        $vendorLocationWithAttributes = VendorLocation::with(
            'attributesBoolean',
            'attributesDate',
            'attributesInteger',
            'attributesFloat',
            'attributesText',
            'attributesVarChar',
            'attributesSingleSelect',
            'attributesMultiSelect'
        )->findOrFail($id)->wherehas('vendorType',function($q) {
            $q->where('type','Restaurants');
        })->first();

        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesBoolean);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesInteger);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesFloat);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesDate);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesText);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesVarChar);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesSingleSelect);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesMultiSelect);

        return [ 'RestaurantLocation' => VendorLocation::find($id), 'attributes' => $this->attributes];
    }

    public function populateVendorAttributes ( $vendorLocationAttributes )
    {

        foreach($vendorLocationAttributes as $attribute)
        {
            $name  = $attribute->attribute->alias;
            $value = $attribute->attribute_value;

            $this->attributes[$name] = $value;
        }

    }

    public function add(VendorLocation $vendorLocation)
    {
        $vendorLocation->save();
    }
}
