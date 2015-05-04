<?php namespace WowTables\Core\Repositories\Experiences;


use WowTables\Http\Models\Eloquent\Products\Product;
use DB;

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
        {   //echo "<pre>"; print_r($attribute);
            $name  = $attribute->attribute->attribute->alias;
            $value = $attribute->product_attributes_select_option_id;

            $this->attributes[$name][] = $value;
        }
    }

    public function populateProductMedia( $product_id )
    {

        $media = '
                    SELECT media_id,media_type,media.file,media.name
                    FROM product_media_map
                    LEFT JOIN media on product_media_map.media_id = media.id
                    WHERE product_id = ?
        ';

        $mediaResults = DB::select($media,[$product_id]);
        return $mediaResults;

    }

    public function populateProductPricing ( $product_id )
    {

        $productPricingDetails = DB::table('product_pricing')->where('product_id', $product_id);
        return $productPricingDetails->get();

    }

    public function populateProductFlags($product_id){

        $productFlagsDetails = DB::table('product_flag_map')->where('product_id', $product_id);
        return $productFlagsDetails->get();
    }

    public function populateProductTags($product_id){

        $productTagsDetails = DB::table('product_tag_map')->where('product_id', $product_id);
        return $productTagsDetails->get();
    }

    public function populateProductCurator($product_id){

        $productCuratorDetails = DB::table('product_curator_map')->where('product_id', $product_id);
        return $productCuratorDetails->get();
    }

}
