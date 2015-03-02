<?php namespace WowTables\Http\Models;

use DB;
use Image;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;

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

    public function __construct(Config $config, Cloud $cloud){
        $this->config = $config;
        $this->cloud = $cloud;
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
                    'commission' => isset($data['commission'])? $data['commission'] : null
                ];

                if(isset($data['post_tax_price'])){
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
                $image_base_url = $this->config->get('media.base_s3_url');

                if(!empty($data['media']['listing'])){
                    $listing_filename = DB::table('media')->where('id', $data['media']['listing'])->pluck('file');
                    $listsize =  $this->config->get('media.sizes.listing');

                    if($listing_filename){
                        $listImage = Image::make($image_base_url.$listing_filename)->fit($listsize['width'], $listsize['height'])->encode();

                        if($listImage){
                            $fileInfo = new \SplFileInfo($listing_filename);
                            $fileExtension = $fileInfo->getExtension();

                            $this->cloud->put($listing_filename.'_'.$listsize['width'].'x'.$listsize['height'].'.'.$fileExtension, $listImage);

                            $resizedMediaInsert = DB::table('media_resized')->insert([
                                'file'      => $listing_filename.'.'.$fileExtension,
                                'width'     => $listsize['width'],
                                'height'    => $listsize['height'],
                                'media_id'  => $data['media']['listing']
                            ]);

                            if($resizedMediaInsert){
                                $experienceMediaMapping = DB::table('product_media_map')->insert([
                                    'product_id' => $experienceId,
                                    'media_type' => 'listing',
                                    'media_id' => $data['media']['listing']
                                ]);

                                if(!$experienceMediaMapping){
                                    DB::rollBack();
                                    return [
                                        'status' => 'failure',
                                        'action' => 'Inserting the experience listing image into the DB',
                                        'message' => 'The product could not be created. Please contact the sys admin'
                                    ];
                                }
                            }else{
                                DB::rollBack();
                                return [
                                    'status' => 'failure',
                                    'action' => 'Inserting the experience listing image into the DB',
                                    'message' => 'The product could not be created. Please contact the sys admin'
                                ];
                            }
                        }else{
                            DB::rollBack();
                            return [
                                'status' => 'failure',
                                'action' => 'Inserting the experience listing image into the DB',
                                'message' => 'The product could not be created. Please contact the sys admin'
                            ];
                        }
                    }
                }

                if(!empty($data['media']['gallery'])){
                    //$mediaFiles = DB::table('media')->whereIn('id', $data['media']['gallery'])->lists('filename', 'id');

                    if($mediaFiles){
                        $gallerysize =  $this->config->get('media.sizes.gallery');

                        foreach($media_files as $media){

                        }
                    }

                }
            }

            if(!empty($data['tags'])){
                $query = 'INSERT IGNORE INTO tags (`name`) VALUES (';
                $insertValues = [];

                foreach($data['tags'] as $tag){
                    $insertValues[] = '?';
                }

                $query .= implode(', ', $insertValues);

                $query .= ')';

                DB::insert($query, $data['tags']);

                $mediaTagIds = DB::table('tags')->whereIn('name', $data['tags'])->lists('id');

                $tagInserts = [];

                foreach($mediaTagIds as $tagID){
                    $tagInserts[] = ['product_id' => $experienceId, 'tag_id' => $tagID];
                }

                $tagInsert = DB::table('product_tag_map')->insert($tagInserts);

                if(!$tagInsert){
                    DB::rollBack();
                    return [
                        'status' => 'failure',
                        'action' => 'Inserting the experience tags into the DB',
                        'message' => 'The product could not be created. Please contact the sys admin'
                    ];
                }
            }

            if(!empty($data['curators'])){
                $curatorInserts = [];

                foreach($data['curators'] as $curator){
                    $curatorInserts[] = ['product_id' => $experienceId, 'curator_id' => $curator];
                }

                $curatorInsert = DB::table('product_tag_map')->insert($curatorInserts);

                if(!$curatorInsert){
                    DB::rollBack();
                    return [
                        'status' => 'failure',
                        'action' => 'Inserting the experience tags into the DB',
                        'message' => 'The product could not be created. Please contact the sys admin'
                    ];
                }
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
}