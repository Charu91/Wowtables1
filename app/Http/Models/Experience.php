<?php namespace WowTables\Http\Models;

use DB;
use Image;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Foundation\Application;
use WowTables\Http\Models\ExperienceAddons;

class Experience extends Product{

    public $id;

    public $name;

    public $slug;

    public $attributes = [];

    public $schecules = [];

    public $locations = [];

    public $tags = [];

    public $curators = [];

    public $addons = [];

    protected $config;

    protected $cloud;

    protected $app;

    public function __construct(Config $config, Cloud $cloud, Application $application){
        $this->config = $config;
        $this->cloud = $cloud;
        $this->app = $application;

        //dd($this->app->make('ExperienceAddons'));
    }

    public function create($data){
        DB::beginTransaction();

        $productTypeId = DB::table('product_types')->where('slug', 'experiences')->pluck('id');

        // Insert the experience Itself
        $experienceId = DB::table('products')->insertGetId([
            'product_type_id' => $productTypeId,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status'],
            'type' => $data['$type'],
            'visible' => $data['visible'],
            'publish_time' => $data['publish_time']
        ]);

        if($experienceId){
            $attributes = array_keys($data['attributes']);

            // Fetch the Experience Attributes Id
            $attributeIdMapResults = DB::table('product_attributes AS pa')
                ->join('product_type_attributes_map AS ptam', 'ptam.product_attribute_id', '=', 'pa.id')
                ->where('ptam.product_type_id', $productTypeId)
                ->whereIn('pa.alias', $attributes)
                ->select('pa.id', 'pa.alias')
                ->get();

            if($attributeIdMapResults) {
                $attributeIdMap = [];

                foreach ($attributeIdMapResults as $result) {
                    $attributeIdMap[$result->alias] = $result->id;
                }
            }

            // Insert the Experiences Attributes
            if(!empty($data['attributes']) && count($attributeIdMap)){
                if(count($attributes)){
                    $attributesMap = $this->config->get('experience_attributes.attributesMap');
                    $typeTableAliasMap = $this->config->get('experience_attributes.typeTableAliasMap');
                    $attribute_inserts = [];

                    foreach($data['attributes'] as $attribute => $value){
                        if(!isset($attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']]))
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']] = [];

                        if($attributesMap[$attribute]['type'] === 'single-select'){
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                'product_id' => $experienceId,
                                'product_attributes_select_option_id' => $value
                            ];
                        }else if($attributesMap[$attribute]['value'] === 'multi' && is_array($value)) {
                            if($attributesMap[$attribute]['type'] === 'multi-select'){
                                foreach ($value as $singleValue) {
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'product_id' => $experienceId,
                                        'product_attributes_select_option_id' => $singleValue
                                    ];
                                }
                            }else{
                                foreach ($value as $singleValue) {
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'product_id' => $experienceId,
                                        'product_attribute_id' => $attributeIdMap[$attribute],
                                        'attribute_value' => $singleValue
                                    ];
                                }
                            }
                        }else{
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                'product_id' => $experienceId,
                                'product_attribute_id' => $attributeIdMap[$attribute],
                                'attribute_value' => $value
                            ];
                        }
                    }

                    $attributeInserts = true;

                    foreach($attribute_inserts as $table => $insertData){
                        $productAttrInsert = DB::table($table)->insert($insertData);

                        if(!$productAttrInsert){
                            $attributeInserts = false;
                            break;
                        }
                    }

                    if(!$attributeInserts){
                        DB::rollBack();
                        return [
                            'status' => 'failure',
                            'action' => 'Inserting the product attributes into the DB',
                            'message' => 'The product could not be created. Please contact the sys admin'
                        ];
                    }
                }
            }

            // Insert the Experience Pricing
            if(!empty($data['pricing'])){
                $pricing_insert_data = [
                    'product_id' => $experienceId,
                    'price' => isset($data['price']) ? $data['price'] : null,
                    'tax' => isset($data['tax'])? $data['tax'] : null,
                    'post_tax_price' => isset($data['post_tax_price'])? $data['post_tax_price'] : null,
                    'commission' => isset($data['commission'])? $data['commission'] : null,
                ];

                if(isset($data['commission_on'])){
                    $pricing_insert_data['commission_on'] = $data['commission_on'];
                }

                $pricingInsert = DB::table('product_pricing')->insert($pricing_insert_data);

                if(!$pricingInsert){
                    DB::rollBack();
                    return [
                        'status' => 'failure',
                        'action' => 'Inserting the product pricing into the DB',
                        'message' => 'The product could not be created. Please contact the sys admin'
                    ];
                }
            }

            // Fetch the Experience Attributes Id for menu and experience Info
            $attributeIdMapResults = DB::table('product_attributes AS pa')
                ->join('product_type_attributes_map AS ptam', 'ptam.product_attribute_id', '=', 'pa.id')
                ->where('ptam.product_type_id', $productTypeId)
                ->whereIn('pa.alias', ['menu', 'experience_info'])
                ->select('pa.id', 'pa.alias')
                ->get();

            if($attributeIdMapResults) {
                $attributeIdMap = [];

                foreach ($attributeIdMapResults as $result) {
                    $attributeIdMap[$result->alias] = $result->id;
                }
            }

            if(!empty($data['addons']) && count($attributeIdMap)){
                $addonInserts = true;

                foreach($data['addons'] as $addon){
                    $addonId = DB::table('products')->insertGetId([
                        'name' => $addon['name'],
                        'type' => 'addon',
                        'visible' => 1,
                        'status' => 'Publish',
                        'product_type_id' => $productTypeId,
                        'product_parent_id' => $experienceId
                    ]);

                    $addon_pricing_insert_data = [
                        'product_id' => $experienceId,
                        'price' => isset($addon['price']) ? $addon['price'] : null,
                        'tax' => isset($addon['tax'])? $addon['tax'] : null,
                        'post_tax_price' => isset($addon['post_tax_price'])? $addon['post_tax_price'] : null,
                        'commission' => isset($addon['commission'])? $addon['commission'] : null
                    ];

                    if(isset($addon['post_tax_price'])){
                        $addon_pricing_insert_data['commission_on'] = $addon['commission_on'];
                    }

                    if($addonId){
                        $AddonPriceInsert = DB::table('product_pricing')->insert($addon_pricing_insert_data);

                        if(!$AddonPriceInsert){
                            $addonInserts = false;
                            break;
                        }

                        $addon_attributes = [];

                        if($addon['menu']){
                            $addon_attributes[] = ['product_id' => $addonId, 'product_attribute_id' => $attributeIdMap['menu'], 'attribute_value' => $addon['menu']];
                        }

                        if($addon['experience_info']){
                            $addon_attributes[] = ['product_id' => $addonId, 'product_attribute_id' => $attributeIdMap['menu'], 'attribute_value' => $addon['experience_info']];
                        }

                        if(count($addon_attributes)){
                            $addonAttributesInsert = DB::table('product_attributes_text')->insert($addon_attributes);

                            if(!$addonAttributesInsert){
                                $addonInserts = false;
                                break;
                            }
                        }
                    }else{
                        $addonInserts = false;
                        break;
                    }
                }

                if(!$addonInserts){
                    DB::rollBack();
                    return [
                        'status' => 'failure',
                        'action' => 'Inserting the experience addon into the DB',
                        'message' => 'The product could not be created. Please contact the sys admin'
                    ];
                }
            }

            if(!empty($data['media'])){

            }

            if(!empty($data['tags'])){

            }

            if(!empty($data['curators'])){

            }

            if(!empty($data['flags'])){

            }

        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Inserting the experience into the DB',
                'message' => 'The product could not be created. Please contact the sys admin'
            ];
        }
    }

    public function update($id, $data){

    }

    public function fetch($id){

    }

    public function fetchBySlug($slug){

    }

    public function delete($id){

    }

    protected function saveAttributes($productId, $attributes){

    }

    protected function saveMedia($productId, $media){
        $mediaSizes = $this->config->get('media.sizes');
        $uploads_dir = $this->config->get('media.base_path');
        $media_resized_insert_map = [];
        $media_insert_map = [];

        if(isset($media['listing_image'])){
            $listing_image = DB::table('media as m')
                ->leftJoin('media_resized as mr', 'mr.media_id','=', 'm.id')
                ->select('m.id', 'm.file', 'mr.file as resized_file' , 'mr.height', 'mr.width')
                ->where('m.id', $media['listing_image'])
                ->first();

            $resized_image = true;
            if(!$listing_image->resized_file){
                $resized_image = false;
            }else{
                if($listing_image->width != $mediaSizes['listing']['width']
                    || $listing_image->height != $mediaSizes['listing']['height']){
                    $resized_image = false;
                }
            }

            if(!$resized_image){
                $listing_file = $listing_image->file;
                $fileInfo = new \SplFileInfo($listing_file);
                $fileExtension = $fileInfo->getExtension();
                $listing_filename = $fileInfo->getBasename('.'.$fileExtension);
                $listing_resized_imagename = $listing_filename.'_'.$mediaSizes['listing']['width'].'x'.$mediaSizes['listing']['height'].'.'.$fileExtension;

                $listing_image_upload = $this->cloud->put(
                    $uploads_dir.$listing_resized_imagename,
                    Image::make($this->cloud->get($uploads_dir.$listing_file))->fit(
                        $mediaSizes['listing']['width'],
                        $mediaSizes['listing']['height']
                    )->encode()
                );

                if(!$listing_image_upload){
                    DB::rollBack();
                    return [
                        'status' => 'failure',
                        'action' => 'Saving the Restaurant Location listing Media into the Cloud'
                    ];
                }else{
                    $media_resized_insert_map[] = [
                        'media_id' => $listing_image->id,
                        'file' => $listing_resized_imagename,
                        'height' => $mediaSizes['listing']['height'],
                        'width' => $mediaSizes['listing']['width']
                    ];
                }


                $media_insert_map[] = [
                    'vendor_location_id' => $productId,
                    'media_type' => 'listing',
                    'media_id' => $media['listing_image'],
                    'order' => 0
                ];
            }
        }

        if(isset($media['gallery_images'])){
            $galleryfiles = DB::table('media as m')
                ->leftJoin('media_resized as mr', 'mr.media_id','=', 'm.id')
                ->select(
                    'm.id',
                    'm.file',
                    DB::raw('MAX(IF(mr.height = '.$mediaSizes['gallery']['height'].' && mr.width = '.$mediaSizes['gallery']['width'].', true, false)) as resized_exists'))
                ->whereIn('m.id', $media['gallery_images'])
                ->groupBy('m.id')
                ->get();

            $gallery_cloud_uploads = true;

            foreach($galleryfiles as $image){
                if(!$image->resized_exists) {
                    $gallery_file = $image->file;
                    $fileInfo = new \SplFileInfo($gallery_file);
                    $fileExtension = $fileInfo->getExtension();
                    $gallery_filename = $fileInfo->getBasename('.' . $fileExtension);
                    $gallery_resized_imagename = $gallery_filename.'_'.$mediaSizes['gallery']['width'].'x'.$mediaSizes['gallery']['height'].'.'. $fileExtension;



                    $listing_image_upload = $this->cloud->put(
                        $uploads_dir . $gallery_resized_imagename,
                        Image::make($this->cloud->get($uploads_dir . $gallery_file))->fit(
                            $mediaSizes['gallery']['width'],
                            $mediaSizes['gallery']['height']
                        )->encode()
                    );

                    if (!$listing_image_upload) {
                        $gallery_cloud_uploads = false;
                        break;
                    } else {
                        $media_resized_insert_map[] = [
                            'media_id' => $image->id,
                            'file' => $gallery_resized_imagename,
                            'height' => $mediaSizes['gallery']['height'],
                            'width' => $mediaSizes['gallery']['width']
                        ];
                    }
                }

                $media_insert_map[] = [
                    'vendor_location_id' => $productId,
                    'media_type' => 'gallery',
                    'media_id' => $image->id,
                    'order' => array_search($image->id, $media['gallery_images'])
                ];
            }

            if(!$gallery_cloud_uploads){
                DB::rollBack();
                return [
                    'status' => 'failure',
                    'action' => 'Saving the Restaurant Location gallery Media into the Cloud'
                ];
            }
        }

        if(count($media_resized_insert_map)){
            $mediaResizeInsert = DB::table('media_resized')->insert($media_resized_insert_map);

            if(!$mediaResizeInsert){
                DB::rollBack();
                return [
                    'status' => 'failure',
                    'action' => 'Saving the Restaurant Location listing Media into the DB'
                ];
            }
        }

        if(count($media_insert_map)){
            $mediaMapInsert = DB::table('product_media_map')->insert($media_insert_map);

            if(!$mediaMapInsert){
                DB::rollBack();
                return [
                    'status' => 'failure',
                    'action' => 'Saving the Restaurant Location Media into the DB'
                ];
            }else{
                return ['status' => 'success'];
            }
        }else{
            return ['status' => 'success'];
        }
    }

    protected function savePricing($product_id, $pricing){

    }

    protected function mapTags($product_id, $tags){

    }

    protected function mapCurators($product_id, $curators){

    }

    protected function mapFlags($product_id, $flags){

    }
}