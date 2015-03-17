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

    protected $app;

    public function __construct(Config $config, Cloud $cloud){
        $this->config = $config;
        $this->cloud = $cloud;
    }

    public function delete($experience_id)
    {
        if(DB::table('products')->where('id', $experience_id)->count()){
            if(DB::table('products')->delete($experience_id)){
                return ['status' => 'success'];
            }else{
                return [
                    'status' => 'failure',
                    'action' => 'Delete the Experience using the id',
                    'message' => 'There was a problem while deleting the Experience. Please check if the restaurant still exists or contact the system admin'
                ];
            }
        }else{
            return [
                'status' => 'failure',
                'action' => 'Check if Experience exists based on the id',
                'message' => 'Could not find the Experiecne you are trying to delete. Try again or contact the sys admin'
            ];
        }
    }

    protected function saveAttributes($productId, $productTypeId, $attributes)
    {
        $attributeAliases = array_keys($attributes);

        if(count($attributeAliases)){
            $attributeIdMap = $attributeIdMap =  DB::table('product_attributes AS pa')
                ->join('product_type_attributes_map AS ptam', 'ptam.product_attribute_id', '=', 'pa.id')
                ->where('ptam.product_type_id', $productTypeId)
                ->whereIn('pa.alias', $attributeAliases)
                ->select('pa.id as attribute_id', 'pa.alias')
                ->lists('attribute_id', 'alias');

            if($attributeIdMap){
                $attributesMap = $this->config->get('experience_attributes.attributesMap');
                $typeTableAliasMap = $this->config->get('experience_attributes.typeTableAliasMap');
                $attribute_inserts = [];

                foreach($attributes as $attribute => $value){
                    if(isset($attributeIdMap[$attribute])){
                        if(!isset($attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']]))
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']] = [];

                        if($attributesMap[$attribute]['type'] === 'single-select'){
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                'product_id' => $productId,
                                'product_attributes_select_option_id' => $value
                            ];
                        }else if($attributesMap[$attribute]['value'] === 'multi' && is_array($value)) {
                            if($attributesMap[$attribute]['type'] === 'multi-select'){
                                foreach ($value as $singleValue) {
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'product_id' => $productId,
                                        'product_attributes_select_option_id' => $singleValue
                                    ];
                                }
                            }else{
                                foreach ($value as $singleValue) {
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'product_id' => $productId,
                                        'product_attribute_id' => $attributeIdMap[$attribute],
                                        'attribute_value' => $singleValue
                                    ];
                                }
                            }
                        }else{
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                'product_id' => $productId,
                                'product_attribute_id' => $attributeIdMap[$attribute],
                                'attribute_value' => $value
                            ];
                        }
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

                if($attributeInserts){
                    return ['status' => 'success'];
                }else{
                    DB::rollBack();
                    return [
                        'status' => 'failure',
                        'action' => 'Inserting the Restaurant Location attributes into the DB'
                    ];
                }
            }else{
                return ['status' => 'success'];
            }

        }else{
            return ['status' => 'success'];
        }
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
                    'product_id' => $productId,
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
                    'product_id' => $productId,
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

    protected function savePricing($productId, $pricing){
        $pricing_insert_data = [
            'product_id' => $productId,
            'price' => isset($data['price']) ? $data['price'] : null,
            'tax' => isset($data['tax'])? $data['tax'] : null,
            'post_tax_price' => isset($data['post_tax_price'])? $data['post_tax_price'] : null,
            'commission' => isset($data['commission'])? $data['commission'] : null,
        ];

        if(isset($data['commission_on'])){
            $pricing_insert_data['commission_on'] = $data['commission_on'];
        }

        $pricingInsert = DB::table('product_pricing')->insert($pricing_insert_data);

        if($pricingInsert){
            return ['status' => 'success'];
        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Inserting the product pricing into the DB'
            ];
        }
    }

    protected function mapTags($productId, $tags){
        $tag_insert_map = [];

        foreach($tags as $tag){
            $tag_insert_map[] = [
                'product_id' => $productId,
                'tag_id' => $tag
            ];
        }

        if(DB::table('product_tag_map')->insert($tag_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Product Tags into the DB'
            ];
        }
    }

    protected function mapCurators($productId, $curators){
        $curator_insert_map = [];

        foreach($curators as $curator){
            $curator_insert_map[] = [
                'product_id' => $productId,
                'curator_id' => $curator
            ];
        }

        if(DB::table('product_curator_map')->insert($curator_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Product Curators into the DB'
            ];
        }
    }

    protected function mapFlags($productId, $flags){
        $flags_insert_map = [];

        foreach($flags as $flag){
            $flags_insert_map[] = [
                'product_id' => $productId,
                'flag_id' => $flag
            ];
        }

        if(DB::table('product_flag_map')->insert($flags_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Product Curators into the DB'
            ];
        }
    }
}