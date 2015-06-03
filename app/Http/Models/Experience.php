<?php namespace WowTables\Http\Models;

use DB;
use Image;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Contracts\Queue\Queue;
use WowTables\Commands\ImageResizeSendToCloud;

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

    protected $queue;

    public function __construct(Config $config, Cloud $cloud, Queue $queue){
        $this->config = $config;
        $this->cloud = $cloud;
        $this->queue = $queue;
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
                'message' => 'Could not find the Experience you are trying to delete. Try again or contact the sys admin'
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

                        if($attributesMap[$attribute]['type'] === 'single-select' && $value != ""){
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                'product_id' => $productId,
                                'product_attributes_select_option_id' => $value
                            ];
                        }else if($attributesMap[$attribute]['value'] === 'multi' && is_array($value)) {
                            if($attributesMap[$attribute]['type'] === 'multi-select' && $value != ""){
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
                            if($attribute === 'menu' && $value != ""){
                                $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                    'product_id' => $productId,
                                    'product_attribute_id' => $attributeIdMap[$attribute],
                                    'attribute_value' => $this->parseMenu($value)
                                    //'attribute_value' => $value
                                ];
                            }else{
                                $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                    'product_id' => $productId,
                                    'product_attribute_id' => $attributeIdMap[$attribute],
                                    'attribute_value' => $value
                                ];
                            }

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
                        'action' => 'Inserting the Experience attributes into the DB'
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
        $media_insert_map = [];

        if(isset($media['listing_image'])){
            /*$listing_image = DB::table('media as m')
                ->leftJoin('media_resized as mr', 'mr.media_id','=', 'm.id')
                ->select(
                    'm.id',
                    'm.file',
                    DB::raw('MAX(IF(mr.height = '.$mediaSizes['listing']['height'].' && mr.width = '.$mediaSizes['listing']['width'].', true, false)) as resized_exists')
                )
                ->where('m.id', $media['listing_image'])
                ->first();


            if(!$listing_image->resized_exists){
                $listing_file = $listing_image->file;
                $fileInfo = new \SplFileInfo($listing_file);
                $fileExtension = $fileInfo->getExtension();
                $listing_filename = $fileInfo->getBasename('.'.$fileExtension);
                $listing_resized_imagename = $listing_filename.'_'.$mediaSizes['listing']['width'].'x'.$mediaSizes['listing']['height'].'.'.$fileExtension;

                $this->queue->push(new ImageResizeSendToCloud(
                    $listing_image->id,
                    $uploads_dir,
                    $listing_resized_imagename,
                    $uploads_dir.$listing_file,
                    $mediaSizes['listing']['width'],
                    $mediaSizes['listing']['height']
                ));

            }*/

            $media_insert_map[] = [
                'product_id' => $productId,
                'media_type' => 'listing',
                'media_id' => $media['listing_image'],
                'order' => 0
            ];
        }

        if(isset($media['gallery_images'])){
            /*$galleryfiles = DB::table('media as m')
                ->leftJoin('media_resized_new as mr', 'mr.media_id','=', 'm.id')
                ->select(
                    'm.id',
                    'm.file',
                    //DB::raw('MAX(IF(mr.height = '.$mediaSizes['gallery']['height'].' && mr.width = '.$mediaSizes['gallery']['width'].', true, false)) as resized_exists'))
                ->whereIn('m.id', $media['gallery_images'])
                ->groupBy('m.id')
                ->get();*/

            foreach($media['gallery_images'] as $key => $image){ //echo "<pre>"; print_r($key); print_r($image);
                /*if(!$image->resized_exists) {
                    $gallery_file = $image->file;
                    $fileInfo = new \SplFileInfo($gallery_file);
                    $fileExtension = $fileInfo->getExtension();
                    $gallery_filename = $fileInfo->getBasename('.' . $fileExtension);
                    $gallery_resized_imagename = $gallery_filename.'_'.$mediaSizes['gallery']['width'].'x'.$mediaSizes['gallery']['height'].'.'. $fileExtension;

                    $this->queue->push(new ImageResizeSendToCloud(
                        $image->id,
                        $uploads_dir,
                        $gallery_resized_imagename,
                        $uploads_dir . $gallery_file,
                        $mediaSizes['gallery']['width'],
                        $mediaSizes['gallery']['height']
                    ));
                }*/

                $media_insert_map[] = [
                    'product_id' => $productId,
                    'media_type' => 'gallery',
                    'media_id' => $image,
                    'order' => $key
                ];
            }

        }
        /*mobile listing ios experience*/
        if(isset($media['mobile'])){
            /*$listing_image = DB::table('media as m')
                ->leftJoin('media_resized as mr', 'mr.media_id','=', 'm.id')
                ->select(
                    'm.id',
                    'm.file',
                    DB::raw('MAX(IF(mr.height = '.$mediaSizes['mobile_listing_ios_experience']['height'].' && mr.width = '.$mediaSizes['mobile_listing_ios_experience']['width'].', true, false)) as resized_exists')
                )
                ->where('m.id', $media['listing_image'])
                ->first();


            if(!$listing_image->resized_exists){
                $listing_file = $listing_image->file;
                $fileInfo = new \SplFileInfo($listing_file);
                $fileExtension = $fileInfo->getExtension();
                $listing_filename = $fileInfo->getBasename('.'.$fileExtension);
                $listing_resized_imagename = $listing_filename.'_'.$mediaSizes['mobile_listing_ios_experience']['width'].'x'.$mediaSizes['mobile_listing_ios_experience']['height'].'.'.$fileExtension;

                $this->queue->push(new ImageResizeSendToCloud(
                    $listing_image->id,
                    $uploads_dir,
                    $listing_resized_imagename,
                    $uploads_dir.$listing_file,
                    $mediaSizes['mobile_listing_ios_experience']['width'],
                    $mediaSizes['mobile_listing_ios_experience']['height']
                ));

            }*/

            $media_insert_map[] = [
                'product_id' => $productId,
                'media_type' => 'mobile',
                'media_id' => $media['mobile'],
                'order' => 0
            ];
        }
        /*mobile listing andriod experiences*/
        /*if(isset($media['mobile_listing_image'])){
            $listing_image = DB::table('media as m')
                ->leftJoin('media_resized as mr', 'mr.media_id','=', 'm.id')
                ->select(
                    'm.id',
                    'm.file',
                    DB::raw('MAX(IF(mr.height = '.$mediaSizes['mobile_listing_andriod_experience']['height'].' && mr.width = '.$mediaSizes['mobile_listing_andriod_experience']['width'].', true, false)) as resized_exists')
                )
                ->where('m.id', $media['listing_image'])
                ->first();


            if(!$listing_image->resized_exists){
                $listing_file = $listing_image->file;
                $fileInfo = new \SplFileInfo($listing_file);
                $fileExtension = $fileInfo->getExtension();
                $listing_filename = $fileInfo->getBasename('.'.$fileExtension);
                $listing_resized_imagename = $listing_filename.'_'.$mediaSizes['mobile_listing_andriod_experience']['width'].'x'.$mediaSizes['mobile_listing_andriod_experience']['height'].'.'.$fileExtension;

                $this->queue->push(new ImageResizeSendToCloud(
                    $listing_image->id,
                    $uploads_dir,
                    $listing_resized_imagename,
                    $uploads_dir.$listing_file,
                    $mediaSizes['mobile_listing_andriod_experience']['width'],
                    $mediaSizes['mobile_listing_andriod_experience']['height']
                ));

            }

            $media_insert_map[] = [
                'product_id' => $productId,
                'media_type' => 'mobile_listing_andriod_experience',
                'media_id' => $media['mobile_listing_image'],
                'order' => 0
            ];
        }*/

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
            'price' => isset($pricing['price']) ? $pricing['price'] : null,
            'tax' => isset($pricing['tax'])? $pricing['tax'] : null,
            'post_tax_price' => isset($pricing['post_tax_price'])? $pricing['post_tax_price'] : null,
            'commission' => isset($pricing['commission'])? $pricing['commission'] : null,
            'price_type' => $pricing['price_types'],
            'taxes' => isset($pricing['taxes'])? $pricing['taxes'] : "Inclusive-Taxes",
        ];

        if(isset($data['commission_on'])){
            $pricing_insert_data['commission_on'] = $pricing['commission_on'];
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

    protected function mapCurator($productId, $curator)
    {
        $curator_insert_map = [
            'product_id' => $productId,
            'curator_id' => $curator,
            //'curator_tips' => $curator['tips']
        ];


        if(DB::table('product_curator_map')->insert($curator_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Experience Curator'
            ];
        }
    }

    protected function mapFlags($productId, $flags){
        $flags_insert_map = [];

        //foreach($flags as $flag){
            $flags_insert_map[] = [
                'product_id' => $productId,
                'flag_id' => $flags
            ];
        //}

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

    protected function parseMenu($menu){

        $crawler = new Crawler($menu);

        $menu = [];

        $menu['title'] = $crawler->filter('h1')->text();
        if( $crawler->filter('h1 + p > em')->count()){
            $menu['description'] = $crawler->filter('h1 + p > em')->text();
        }

        $n = 0; $o = 0; $p = 0;
        $current_item = '';

        $crawler->filter('h1')->siblings()->each(function(Crawler $node, $i) use (&$menu, &$n, &$o, &$p, &$current_item){

            if($node->nodeName() === 'h2'){
                if(!isset($menu['menu'])) $menu['menu'] = [];
                ++$n;
                $menu['menu'][$n] = [];
                $menu['menu'][$n]['heading'] = $node->text();


                $current_item = 'menu-heading';
            }

            if($node->nodeName() === 'h3'){

                ++$o;
                $menu['menu'][$n]['sub-menu'][$o] = [];
                $menu['menu'][$n]['sub-menu'][$o]['heading'] = $node->text();

                $current_item = 'submenu-heading';
            }

            if($node->nodeName() === 'h4'){
                ++$p;

                if($current_item === 'menu-heading' || $current_item === 'menu-heading-item'){
                    if(!isset($menu['menu'][$n]['items'])) $menu['menu'][$n]['items'] = [];
                    if(!isset($menu['menu'][$n]['items'][$p])) $menu['menu'][$n]['items'][$p] = [];
                    $menu['menu'][$n]['items'][$p]['title'] = $node->text();
                    $current_item = 'menu-heading-item';
                }else if($current_item === 'submenu-heading' || $current_item === 'submenu-heading-item') {
                    if(!isset($menu['menu'][$n]['sub-menu'][$o]['items'])) $menu['menu'][$n]['sub-menu'][$o]['items'] = [];
                    if(!isset($menu['menu'][$n]['sub-menu'][$o]['items'][$p])) $menu['menu'][$n]['sub-menu'][$o]['items'][$p] = [];
                    $menu['menu'][$n]['sub-menu'][$o]['items'][$p]['title'] = $node->text();
                    $current_item = 'submenu-heading-item';
                }


            }

            if($node->nodeName() === 'p' && $node->children()->eq(0)->nodeName() === 'em'){
                if(isset($menu['menu'])){
                    if($current_item === 'menu-heading'){
                        $menu['menu'][$n]['description'] = $node->text();
                    }else if($current_item === 'submenu-heading'){
                        $menu['menu'][$n]['sub-menu'][$o]['description'] = $node->text();
                    }else if($current_item === 'menu-heading-item'){
                        $menu['menu'][$n]['items'][$p]['description'] = $node->text();
                    }else if($current_item === 'submenu-heading-item'){
                        $menu['menu'][$n]['sub-menu'][$o]['items'][$p]['description'] = $node->text();
                    }
                }
            }

            if($node->nodeName() === 'p' && $node->children()->eq(0)->nodeName() === 'strong'){
                if(isset($menu['menu'])){
                    if($current_item === 'menu-heading-item'){
                        $node->children()->filter('strong')->each(function(Crawler $node, $i) use (&$menu, &$n, &$o, &$p){
                            if(!isset($menu['menu'][$n]['items'][$p]['tags'])) $menu['menu'][$n]['items'][$p]['tags'] = [];
                            $menu['menu'][$n]['items'][$p]['tags'][] = $node->text();
                        });
                    }else if($current_item === 'submenu-heading-item'){
                        $node->children()->filter('strong')->each(function(Crawler $node, $i) use (&$menu, &$n, &$o, &$p){
                            if(!isset($menu['menu'][$n]['sub-menu'][$o]['items'][$p]['tags'])) $menu['menu'][$n]['sub-menu'][$o]['items'][$p]['tags'] = [];
                            $menu['menu'][$n]['sub-menu'][$o]['items'][$p]['tags'][] = $node->text();
                        });
                    }
                }
            }
        });

        $newMenu = [];

        $newMenu['title'] = $menu['title'];
        if(isset($newMenu['description'])){
            $newMenu['description'] = $menu['description'];
        }

        $newMenu['menu'] = [];

        foreach($menu['menu'] as $mm){
            $current_menu = [];

            $current_menu['heading'] = $mm['heading'];
            if(isset($mm['description'])){
                $current_menu['description'] = $mm['description'];
            }

            if(isset($mm['items'])){
                foreach($mm['items'] as $item){
                    $itemArray = [];

                    $itemArray['title'] = $item['title'];
                    if(isset($item['tags'])) $itemArray['tags'] = $item['tags'];
                    if(isset($item['description'])) $itemArray['description'] = $item['description'];

                    if(!isset($current_menu['items'])){
                        $current_menu['items'] = [];
                    }

                    $current_menu['items'][] = $itemArray;
                }
            }else if(isset($mm['sub-menu'])){

                foreach($mm['sub-menu'] as $mmm){
                    $submenu = [];
                    if(!isset($current_menu['sub-menu'])) $current_menu['sub-menu'] = [];

                    $submenu['heading'] = $mmm['heading'];
                    if(isset($mmm['description'])) $submenu['description'] = $mmm['description'];

                    foreach($mmm['items'] as $item){
                        $itemArray = [];

                        $itemArray['title'] = $item['title'];
                        if(isset($item['tags'])) $itemArray['tags'] = $item['tags'];
                        if(isset($item['description'])) $itemArray['description'] = $item['description'];

                        if(!isset($submenu['items'])){
                            $submenu['items'] = [];
                        }

                        $submenu['items'][] = $itemArray;
                    }

                    $current_menu['sub-menu'][] = $submenu;
                }
            }

            $newMenu['menu'][] = $current_menu;
        }

        return json_encode($newMenu);
    }
}