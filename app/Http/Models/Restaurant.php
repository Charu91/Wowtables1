<?php namespace WowTables\Http\Models;

use DB;
use Illuminate\Contracts\Config\Repository as Config;

class Restaurant extends Vendor{

    protected $config;

    public $name;

    public $slug;

    public $attributes = [];

    public function __construct(Config $config){
        $this->config = $config;
    }

    public function create($data){

        DB::beginTransaction();

        $vendorTypeId = DB::table('vendor_types')->where('name','Restaurants')->pluck('id');

        if($vendorTypeId){
            $vendorInsertData = ['name' => $data['name'], 'slug' => $data['slug'], 'vendor_type_id' => $vendorTypeId, 'status' => $data['status']];

            if($data['status'] === 'Publish'){
                if(isset($data['publish_date']) && isset($data['publish_time'])){
                    $vendorInsertData['publish_time'] = $data['publish_date'].' '.$data['publish_time'];
                }else{
                    $vendorInsertData['publish_time'] = DB::raw('NOW()');
                }
            }

            $vendorId = DB::table('vendors')->insertGetId($vendorInsertData);

            if($vendorId){
                if(count($data['attributes'])){
                    $attributes = array_keys($data['attributes']);

                    if(count($attributes)){
                        $attributeIdMap = DB::table('vendor_attributes')->whereIn('alias', $attributes)->lists('id', 'alias');

                        if($attributeIdMap){
                            $attributesMap = $this->config->get('restaurant_attributes.attributesMap');
                            $typeTableAliasMap = $this->config->get('restaurant_attributes.typeTableAliasMap');
                            $attribute_inserts = [];

                            foreach($data['attributes'] as $attribute => $value){
                                if(!isset($attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']]))
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']] = [];

                                if($attributesMap[$attribute]['type'] === 'single-select'){
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'vendor_id' => $vendorId,
                                        'vendor_attributes_select_option_id' => $value
                                    ];
                                }else if($attributesMap[$attribute]['value'] === 'multi' && is_array($value)) {
                                    if($attributesMap[$attribute]['type'] === 'multi-select'){
                                        foreach ($value as $singleValue) {
                                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                                'vendor_id' => $vendorId,
                                                'vendor_attributes_select_option_id' => $singleValue
                                            ];
                                        }
                                    }else{
                                        foreach ($value as $singleValue) {
                                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                                'vendor_id' => $vendorId,
                                                'vendor_attribute_id' => $attributeIdMap[$attribute],
                                                'attribute_value' => $singleValue
                                            ];
                                        }
                                    }
                                }else{
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'vendor_id' => $vendorId,
                                        'vendor_attribute_id' => $attributeIdMap[$attribute],
                                        'attribute_value' => $value
                                    ];
                                }
                            }

                            $attributeInserts = true;

                            foreach($attribute_inserts as $table => $insertData){
                                $restauranrAttrInsert = DB::table($table)->insert($insertData);

                                if(!$restauranrAttrInsert){
                                    $attributeInserts = false;
                                    break;
                                }
                            }

                            if($attributeInserts){
                                DB::commit();
                                return ['status' => 'success'];
                            }else{
                                DB::rollBack();
                                return [
                                    'status' => 'failure',
                                    'action' => 'Inserting the Restaurant attributes into the DB',
                                    'message' => 'The Restaurant could not be created. Please contact the sys admin'
                                ];
                            }
                        }else{
                            DB::commit();
                            return ['status' => 'success'];

                        }

                    }else{
                        DB::commit();
                        return ['status' => 'success'];
                    }
                }else{
                    DB::commit();
                    return ['status' => 'success'];
                }
            }else{
                DB::rollBack();
                return [
                    'status' => 'failure',
                    'action' => 'Create the restaurant based with the assigned params',
                    'message' => 'Could not create the Restaurant. Contact the system admin'
                ];
            }
        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Fetching the Vendor type Id',
                'message' => 'Could not create the Restaurant. Contact the system admin'
            ];
        }


    }

    public function update($restaurant_id, $data)
    {
        DB::beginTransaction();

        //delete all existing attributes
        $query = '
            DELETE vab, vad, vaf, vai, vam, vas, vat, vav
            FROM vendors AS `v`
            LEFT JOIN vendor_attributes_boolean AS `vab` ON vab.vendor_id = v.`id`
            LEFT JOIN vendor_attributes_date AS `vad` ON vab.vendor_id = v.`id`
            LEFT JOIN vendor_attributes_float AS `vaf` ON vab.vendor_id = v.`id`
            LEFT JOIN vendor_attributes_integer AS `vai` ON vab.vendor_id = v.`id`
            LEFT JOIN vendor_attributes_multiselect AS `vam` ON vab.vendor_id = v.`id`
            LEFT JOIN vendor_attributes_singleselect AS `vas` ON vab.vendor_id = v.`id`
            LEFT JOIN vendor_attributes_text AS `vat` ON vab.vendor_id = v.`id`
            LEFT JOIN vendor_attributes_varchar AS `vav` ON vab.vendor_id = v.`id`
            WHERE v.id = ?
        ';

        DB::delete($query);


    }

    public function delete($restaurant_id)
    {

    }


}