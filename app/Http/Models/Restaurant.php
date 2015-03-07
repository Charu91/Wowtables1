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

        $vendorTypeId = DB::table('vendor_types')->where('type','Restaurants')->pluck('id');

        if($vendorTypeId){
            $vendorInsertData = ['name' => $data['name'], 'slug' => $data['slug'], 'vendor_type_id' => $vendorTypeId, 'status' => $data['status']];

            if($data['status'] === 'Publish'){
                if(isset($data['publish_date']) && isset($data['publish_time'])){
                    $vendorInsertData['publish_time'] = $data['publish_date'].' '.$data['publish_time'];
                }else{
                    $vendorInsertData['publish_time'] = DB::raw('NOW()');
                }
            }

            $restaurantId = DB::table('vendors')->insertGetId($vendorInsertData);

            if($restaurantId){
                if(count($data['attributes'])){
                    $AttributesSaved = $this->saveAttributes($restaurantId, $data['attributes']);

                    if($AttributesSaved['status'] === 'success'){
                        return ['status' => 'success'];
                    }else{
                        $AttributesSaved['message'] = 'Could not create the Restaurant. Contact the system admin';
                        return $AttributesSaved;
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

        if(DB::table('vendors')->where('id', $restaurant_id)->count()){
            //delete all existing attributes
            $query = '
                DELETE vab, vad, vaf, vai, vam, vas, vat, vav
                FROM vendors AS `v`
                LEFT JOIN vendor_attributes_boolean AS `vab` ON vab.vendor_id = v.`id`
                LEFT JOIN vendor_attributes_date AS `vad` ON vad.vendor_id = v.`id`
                LEFT JOIN vendor_attributes_float AS `vaf` ON vaf.vendor_id = v.`id`
                LEFT JOIN vendor_attributes_integer AS `vai` ON vai.vendor_id = v.`id`
                LEFT JOIN vendor_attributes_multiselect AS `vam` ON vam.vendor_id = v.`id`
                LEFT JOIN vendor_attributes_singleselect AS `vas` ON vas.vendor_id = v.`id`
                LEFT JOIN vendor_attributes_text AS `vat` ON vat.vendor_id = v.`id`
                LEFT JOIN vendor_attributes_varchar AS `vav` ON vav.vendor_id = v.`id`
                WHERE v.id = ?
            ';

            DB::delete($query, [$restaurant_id]);

            $vendorUpdateData = ['name' => $data['name'], 'slug' => $data['slug'], 'status' => $data['status']];

            if($data['status'] === 'Publish'){
                if(isset($data['publish_date']) && isset($data['publish_time'])){
                    $vendorUpdateData['publish_time'] = $data['publish_date'].' '.$data['publish_time'];
                }else{
                    $vendorUpdateData['publish_time'] = DB::raw('NOW()');
                }
            }

            DB::table('vendors')->where('id', $restaurant_id)->update($vendorUpdateData);

            if(count($data['attributes'])){
                $AttributesSaved = $this->saveAttributes($restaurant_id, $data['attributes']);

                if($AttributesSaved['status'] === 'success'){
                    return ['status' => 'success'];
                }else{
                    $AttributesSaved['message'] = 'Could not update the Restaurant. Contact the system admin';
                    return $AttributesSaved;
                }
            }else{
                DB::commit();
                return ['status' => 'success'];
            }
        }else{
            return [
                'status' => 'failure',
                'action' => 'Check if restaurant based on the id',
                'message' => 'Could not find the restaurant you are trying to update. Try again or contact the sys admin'
            ];
        }
    }

    public function delete($restaurant_id)
    {
        if(DB::table('vendors')->where('id', $restaurant_id)->count()){
            if(DB::table('vendors')->delete($restaurant_id)){
                return ['status' => 'success'];
            }else{
                return [
                    'status' => 'failure',
                    'action' => 'Delete the restaurant using the restaurant id',
                    'message' => 'There was a problem while deleting the restaurant. Please check if the restaurant still exists or contact the system admin'
                ];
            }
        }else{
            return [
                'status' => 'failure',
                'action' => 'Check if restaurant based on the id',
                'message' => 'Could not find the restaurant you are trying to delete. Try again or contact the sys admin'
            ];
        }

    }

    protected function saveAttributes($vendorId, array $attributes){
        $attributeAliases = array_keys($attributes);

        if(count($attributeAliases)){
            $attributeIdMap = DB::table('vendor_attributes as va')
                                ->join('vendor_type_attributes_map as vtam', 'vtam.vendor_attribute_id','=','va.id')
                                ->join('vendor_types as vt','vt.id','=','vtam.vendor_type_id')
                                ->whereIn('alias', $attributeAliases)
                                ->select('va.id as attribute_id', 'alias')
                                ->lists('attribute_id', 'alias');

            if($attributeIdMap){
                $attributesMap = $this->config->get('restaurant_attributes.attributesMap');
                $typeTableAliasMap = $this->config->get('restaurant_attributes.typeTableAliasMap');
                $attribute_inserts = [];

                foreach($attributes as $attribute => $value){
                    if(isset($attributeIdMap[$attribute])){
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
                        'action' => 'Inserting the Restaurant attributes into the DB'
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
    }


}