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

    public function getByExperienceId($id)
    {
        $productWithAttributes = Product::with(
            'attributesBoolean',
            'attributesDate',
            'attributesInteger',
            'attributesFloat',
            'attributesText',
            'attributesVarChar',
            'attributesSingleSelect',
            'attributesMultiSelect'
            //'schedules'
        )->findOrFail($id);
        /*->wherehas('vendor.vendorType',function($q) {
        $q->where('type','Restaurants');
    })->first();*/
        //dd(array_flatten($vendorLocationWithAttributes->schedules->toArray()));
        $this->populateExperienceAttributes($productWithAttributes->attributesBoolean);
        $this->populateExperienceAttributes($productWithAttributes->attributesInteger);
        $this->populateExperienceAttributes($productWithAttributes->attributesFloat);
        $this->populateExperienceAttributes($productWithAttributes->attributesDate);
        $this->populateExperienceAttributes($productWithAttributes->attributesText);
        $this->populateExperienceAttributes($productWithAttributes->attributesVarChar);
        $this->populateExperienceAttributes($productWithAttributes->attributesSingleSelect);
        $this->populateExperienceMultiSelectAttributes($productWithAttributes->attributesMultiSelect);
        //$this->populateExperienceSchedules($id);

        return [ 'Experience' => Product::find($id), 'attributes' => $this->attributes];
    }

    public function populateExperienceAttributes ( $experienceAttributes )
    {
        foreach($experienceAttributes as $attribute)
        {
            $name  = $attribute->attribute->alias;
            $value = $attribute->attribute_value;

            $this->attributes[$name] = $value;
        }

    }

    public function populateExperienceMultiSelectAttributes ( $experienceMultiSelectAttributes )
    {

        foreach($experienceMultiSelectAttributes as $attribute)
        {echo "<pre>"; print_r($attribute);
            $name  = $attribute->attribute->attribute->alias;
            $value = $attribute->product_attributes_select_option_id;

            $this->attributes[$name][] = $value;
        }
    }

}
