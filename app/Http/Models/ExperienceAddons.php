<?php namespace WowTables\Http\Models;

use DB;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;

class ExperienceAddons extends Experience{

    public function __construct()
    {

    }

    public function create($productId, $productTypeId, $addons){
        $addonInserts = true;

        $attributeIdMap = DB::table('product_attributes AS pa')
            ->join('product_type_attributes_map AS ptam', 'ptam.product_attribute_id', '=', 'pa.id')
            ->where('ptam.product_type_id', $productTypeId)
            ->whereIn('pa.alias', ['menu', 'menu_markdown', 'experience_info'])
            ->select('pa.id as attribute_id', 'pa.alias')
            ->lists('attribute_id', 'alias');

        foreach($addons as $addon){
            $addonId = DB::table('products')->insertGetId([
                'name' => $addon['name'],
                'slug' => uniqid('addon'),
                'type' => 'addon',
                'visible' => 1,
                'status' => 'Publish',
                'product_type_id' => $productTypeId,
                'product_parent_id' => $productId
            ]);


            if($addonId){

                if(!isset($addon_pricing_insert_data)){
                    $addon_pricing_insert_data = [];
                }

                $addon_pricing_insert_data[] = [
                    'product_id' => $productId,
                    'price' => $addon['price'],
                    'tax' => $addon['tax'],
                    'post_tax_price' => $addon['post_tax_price'],
                    'commission' => $addon['commission_per_cover'],
                    'commission_on' => $addon['commission_on']
                ];

                if(!isset($addon_attributes_insert_data)){
                    $addon_attributes_insert_data = [];
                }

                if(isset($addon['menu'])){
                    $addon_attributes[] = ['product_id' => $addonId, 'product_attribute_id' => $attributeIdMap['menu'], 'attribute_value' => $addon['menu']];
                    $addon_attributes[] = ['product_id' => $addonId, 'product_attribute_id' => $attributeIdMap['menu_markdown'], 'attribute_value' => $addon['menu_markdown']];
                }

                if(isset($addon['experience_info'])){
                    $addon_attributes[] = ['product_id' => $addonId, 'product_attribute_id' => $attributeIdMap['experience_info'], 'attribute_value' => $addon['experience_info']];
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
                'action' => 'Inserting the experience addons into the DB'
            ];
        }

        if(!empty($addon_pricing_insert_data)){
            if(!DB::table('product_pricing')->insert($addon_pricing_insert_data)){
                DB::rollBack();
                return [
                    'status' => 'failure',
                    'action' => 'Inserting the experience addon pricing into the DB'
                ];
            }
        }

        if(!empty($addon_attributes_insert_data)){
            if(!DB::table('product_attributes_text')->insert($addon_attributes_insert_data)){
                DB::rollBack();
                return [
                    'status' => 'failure',
                    'action' => 'Inserting the experience addon attributes into the DB'
                ];
            }
        }

        return ['status' => 'success'];
    }
} 