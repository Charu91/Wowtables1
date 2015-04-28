<?php namespace WowTables\Core\Repositories\Experiences;


use WowTables\Http\Models\Eloquent\Products\Product;

class ExperiencesRepository {


    protected $attributes = [];


    public function getAll()
    {
        return Product::all();
    }

    public function add(Product $product)
    {
        $product->save();
    }

    public function getByProductId($id)
    {
        $vendorWithAttributes = Product::with(
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

        return [ 'product' => Product::find($id), 'attributes' => $this->attributes];
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
