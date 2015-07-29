<?php namespace WowTables\Http\Models;

use DB;
use Image;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Queue\Queue;
use WowTables\Commands\ImageResizeSendToCloud;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RestaurantLocation extends VendorLocation{

    public $restaurant;

    public $slug;

    public $attributes = [];

    public $address = [
        'address' => '',
        'pin_code' => '',
        'latitude' => '',
        'longitude' => '',
        'locality_id' => '',
        'locality' => '',
        'area_id' => '',
        'area' => '',
        'city_id' => '',
        'city' => '',
        'state_id' => '',
        'state' => '',
        'country_id' => '',
        'country' => ''
    ];

    public $feast;

    public $schedules;

    public $block_dates;

    public $total_reviews;

    public $average_rating;

    public $time_range_limits;

    protected $config;

    protected $cloud;

    protected $queue;

    protected $attributesMap;

    protected $typeTableAliasMap;

    public function __construct(Config $config, Cloud $cloud, Queue $queue, Media $media)
    {
        $this->config = $config;
        $this->cloud = $cloud;
        $this->queue = $queue;
        $this->media = $media;

        $this->attributesMap = $config->get('restaurant_locations_attributes.attributesMap');
        $this->typeTableAliasMap = $config->get('restaurant_locations_attributes.typeTableAliasMap');
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        $vendorLocationInsertData = [
            'vendor_id' => $data['restaurant_id'],
            'slug' => $data['slug'],
            'location_id' => $data['location_id'],
            'status' => $data['status'],
            'a_la_carte' => ($data['a_la_carte'] ? $data['a_la_carte'] : 0)
        ];

        if(!empty($data['pricing_level'])){
            $vendorLocationInsertData['pricing_level'] = $data['pricing_level'];
        }

        $restaurantLocationId = DB::table('vendor_locations')->insertGetId($vendorLocationInsertData);

        if($restaurantLocationId){
            if(!empty($data['attributes'])){
                $AttributesSaved = $this->saveAttributes($restaurantLocationId, $data['attributes']);

                if($AttributesSaved['status'] !== 'success'){
                    $AttributesSaved['message'] = 'Could not create the Restaurant Location Attributes. Contact the system admin';
                    return $AttributesSaved;
                }
            }

            if(!empty($data['location_attributes'])){
                $LocationAttributesSaved = $this->saveLocationAttributes($restaurantLocationId, $data['location_attributes']);

                if($LocationAttributesSaved['status'] !== 'success'){
                    $LocationAttributesSaved['message'] = 'Could not create the Restaurant Location Attributes. Contact the system admin';
                    return $LocationAttributesSaved;
                }
            }

            if(!empty($data['address'])){
                $AddressSaved = $this->saveAddress($restaurantLocationId, $data['location_id'], $data['address']);

                if($AddressSaved['status'] !== 'success'){
                    $AddressSaved['message'] = 'Could not create the Restaurant Location Address. Contact the system admin';
                    return $AddressSaved;
                }
            }

            if(!empty($data['schedules'])){
                $schedulesSaved = $this->saveSchedules($restaurantLocationId ,$data['schedules']);

                if($schedulesSaved['status'] !== 'success'){
                    $schedulesSaved['message'] = 'Could not create the Restaurant Location Schedules. Contact the system admin';
                    return $schedulesSaved;
                }
            }
            if(!empty($data['media']) && !empty($data['media']['listing_image']) && (!empty($data['media']['gallery_images']) && $data['media']['gallery_images'][0] != "") && !empty($data['media']['mobile'])){
            //if(!empty($data['media'])){
                $mediaSaved = $this->saveMedia($restaurantLocationId, $data['media']);

                if($mediaSaved['status'] !== 'success'){
                    $mediaSaved['message'] = 'Could not create the Restaurant Location Media. Contact the system admin';
                    return $mediaSaved;
                }
            }

            if(!empty($data['block_dates'])){
                $blockSchedulesSaved = $this->saveBlockDates($restaurantLocationId ,$data['block_dates']);

                if($blockSchedulesSaved['status'] !== 'success'){
                    $blockSchedulesSaved['message'] = 'Could not create the Restaurant Location Block Schedules. Contact the system admin';
                    return $blockSchedulesSaved;
                }
            }

            if(!empty($data['reset_time_range_limits'])){
                $resetTimeRangeLimtsSaved = $this->saveTimeRangeLimits($restaurantLocationId, $data['reset_time_range_limits']);

                if($resetTimeRangeLimtsSaved['status'] !== 'success'){
                    $resetTimeRangeLimtsSaved['message'] = 'Could not create the Restaurant Location Time Range Limits. Contact the system admin';
                    return $resetTimeRangeLimtsSaved;
                }
            }

            if(!empty($data['contacts'])){
                $contactsSaved = $this->saveContacts($restaurantLocationId, $data['contacts']);

                if($contactsSaved['status'] !== 'success'){
                    $contactsSaved['message'] = 'Could not create the Restaurant Location Contacts. Contact the system admin';
                    return $contactsSaved;
                }
            }

            if(!empty($data['attributes']['collections'])){
                $tagMapping = $this->mapTags($restaurantLocationId, $data['attributes']['collections']);

                if($tagMapping['status'] !== 'success'){
                    $tagMapping['message'] = 'Could not map the Restaurant Location Tags. Contact the system admin';
                    return $tagMapping;
                }
            }

            if(!empty($data['curators'])){
                $curatorMapping = $this->mapCurator($restaurantLocationId, $data['curators'],$data['curator_tips']);

                if($curatorMapping['status'] !== 'success'){
                    $curatorMapping['message'] = 'Could not map the Restaurant Location curators. Contact the system admin';
                    return $curatorMapping;
                }
            }

            if(!empty($data['attributes']['flags'])){
                $flagMapping = $this->mapFlags($restaurantLocationId, $data['attributes']['flags']);

                if($flagMapping['status'] !== 'success'){
                    $flagMapping['message'] = 'Could not map the Vendor Location Flags. Contact the system admin';
                    return $flagMapping;
                }
            }

            DB::commit();
            return ['status' => 'success'];
        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Create the restaurant based with the assigned params',
                'message' => 'Could not create the Restaurant. Contact the system admin'
            ];
        }
    }

    public function update($vendor_location_id, $data)
    {

        DB::beginTransaction();

        //delete all existing attributes
        $query = '
            DELETE vlab, vlad, vlaf, vlai, vlam, vlas, vlat, vlav,
                  vlbls, vlbs, vlbtrl, vlc, vlcm, vlmm, vla, vltm,vlfm,vll
            FROM vendor_locations AS `vl`
            LEFT JOIN vendor_location_attributes_boolean AS `vlab` ON vlab.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_attributes_date AS `vlad` ON vlad.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_attributes_float AS `vlaf` ON vlaf.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_attributes_integer AS `vlai` ON vlai.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_attributes_multiselect AS `vlam` ON vlam.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_attributes_singleselect AS `vlas` ON vlas.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_attributes_text AS `vlat` ON vlat.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_attributes_varchar AS `vlav` ON vlav.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_blocked_schedules AS `vlbls` ON vlbls.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_booking_schedules AS `vlbs` ON vlbs.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_booking_time_range_limits AS `vlbtrl` ON vlbtrl.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_contacts AS `vlc` ON vlc.vendor_location_id = vl.`id`
            LEFT JOIN vendor_locations_curator_map AS `vlcm` ON vlcm.vendor_location_id = vl.`id`
            LEFT JOIN vendor_locations_media_map AS `vlmm` ON vlmm.vendor_location_id = vl.`id`
            LEFT JOIN vendor_location_address AS `vla` ON vla.vendor_location_id = vl.`id`
            LEFT JOIN vendor_locations_tags_map AS `vltm` ON vltm.vendor_location_id = vl.`id`
            LEFT JOIN vendor_locations_flags_map AS `vlfm` ON vlfm.vendor_location_id = vl.`id`
            LEFT JOIN vendor_locations_limits AS `vll` ON vll.vendor_location_id = vl.`id`
            WHERE vl.id = ?
        ';

        DB::delete($query, [$vendor_location_id]);
        DB::commit();
        /*$vendorLocationUpdateData = [
            'slug' => $data['slug'],
            'location_id' => $data['location_id'],
            'status' => $data['status']
        ];*/

        $vendorLocationUpdateData = [
            'vendor_id' => $data['restaurant_id'],
            'slug' => $data['slug'],
            'location_id' => $data['location_id'],
            'status' => $data['status'],
            'a_la_carte' => ((isset($data['a_la_carte']) && $data['a_la_carte'] != "") ? $data['a_la_carte'] : 0)
        ];

        if(!empty($data['pricing_level'])){
            $vendorLocationUpdateData['pricing_level'] = $data['pricing_level'];
        }

        DB::table('vendor_locations')->where('id', $vendor_location_id)->update($vendorLocationUpdateData);


        if(!empty($data['attributes'])){
            $AttributesSaved = $this->saveAttributes($vendor_location_id, $data['attributes']);

            if($AttributesSaved['status'] !== 'success'){
                $AttributesSaved['message'] = 'Could not create the Restaurant Location Attributes. Contact the system admin';
                return $AttributesSaved;
            }
        }

        if(!empty($data['address'])){
            $AddressSaved = $this->saveAddress($vendor_location_id, $data['location_id'], $data['address']);

            if($AddressSaved['status'] !== 'success'){
                $AddressSaved['message'] = 'Could not create the Restaurant Location Address. Contact the system admin';
                return $AddressSaved;
            }
        }

        if(!empty($data['schedules'])){
            /*if(isset($data['off_peak_schedules'])){
                $schedulesSaved = $this->saveSchedules($vendor_location_id ,$data['schedules'], $data['off_peak_schedules']);
            }else{*/
                $schedulesSaved = $this->saveSchedules($vendor_location_id ,$data['schedules']);
            //}


            if($schedulesSaved['status'] !== 'success'){
                $schedulesSaved['message'] = 'Could not create the Restaurant Location Schedules. Contact the system admin';
                return $schedulesSaved;
            }
        }

        if(!empty($data['location_attributes'])){
            $LocationAttributesSaved = $this->saveLocationAttributes($vendor_location_id, $data['location_attributes']);

            if($LocationAttributesSaved['status'] !== 'success'){
                $LocationAttributesSaved['message'] = 'Could not create the Restaurant Location Attributes. Contact the system admin';
                return $LocationAttributesSaved;
            }
        }

        /*
         * Comparing the old and new media array and accordingly picking up the values
         * */

        $listing_image = $data['media']['listing_image'];
        $gallery_images = $data['media']['gallery_images'];
        $mobile_listing_images = $data['media']['mobile'];

        if(empty($listing_image)){
            $listing_image_array = (isset($data['old_media']['listing_image']) && $data['old_media']['listing_image'] != "" ? $data['old_media']['listing_image'] : '' );
        }else{
            $listing_image_array = $data['media']['listing_image'];
        }

        if($gallery_images[0] == ""){
            $gallery_image_array = (isset($data['old_media']['gallery_images']) && $data['old_media']['gallery_images'] != "" ? $data['old_media']['gallery_images'] : '' );
        }else{
            $gallery_image_array = $data['media']['gallery_images'];
        }
        if(empty($mobile_listing_images)){
            $mobile_listing_image_array = (isset($data['old_media']['mobile']) && $data['old_media']['mobile'] != "" ? $data['old_media']['mobile'] : '' );
        }else{
            $mobile_listing_image_array = $data['media']['mobile'];
        }
        $new_media['media'] = ['listing_image'=>$listing_image_array,'gallery_images'=>$gallery_image_array,'mobile'=>$mobile_listing_image_array];

        //if(!empty($new_media['media'])){
        if(!empty($new_media['media']) && !empty($new_media['media']['listing_image']) && (!empty($new_media['media']['gallery_images']) && $new_media['media']['gallery_images'][0] != "") && !empty($new_media['media']['mobile'])){
            $mediaSaved = $this->saveMedia($vendor_location_id, $new_media['media']);

            if($mediaSaved['status'] !== 'success'){
                $mediaSaved['message'] = 'Could not create the Restaurant Location Media. Contact the system admin';
                return $mediaSaved;
            }
        }

        if(!empty($data['block_dates'])){
            $blockSchedulesSaved = $this->saveBlockDates($vendor_location_id ,$data['block_dates']);

            if($blockSchedulesSaved['status'] !== 'success'){
                $blockSchedulesSaved['message'] = 'Could not create the Restaurant Location Block Schedules. Contact the system admin';
                return $blockSchedulesSaved;
            }
        }

        if(!empty($data['reset_time_range_limits'])){
            $resetTimeRangeLimtsSaved = $this->saveTimeRangeLimits($vendor_location_id, $data['reset_time_range_limits']);

            if($resetTimeRangeLimtsSaved['status'] !== 'success'){
                $resetTimeRangeLimtsSaved['message'] = 'Could not create the Restaurant Location Time Range Limits. Contact the system admin';
                return $resetTimeRangeLimtsSaved;
            }
        }

        if(!empty($data['contacts'])){
            $contactsSaved = $this->saveContacts($vendor_location_id, $data['contacts']);

            if($contactsSaved['status'] !== 'success'){
                $contactsSaved['message'] = 'Could not create the Restaurant Location Contacts. Contact the system admin';
                return $contactsSaved;
            }
        }

        /*if(!empty($data['tags'])){
            $tagMapping = $this->mapTags($vendor_location_id, $data['tags']);

            if($tagMapping['status'] !== 'success'){
                $tagMapping['message'] = 'Could not map the Restaurant Location Tags. Contact the system admin';
                return $tagMapping;
            }
        }*/

        if(!empty($data['attributes']['collections'])){
            $tagMapping = $this->mapTags($vendor_location_id, $data['attributes']['collections']);

            if($tagMapping['status'] !== 'success'){
                $tagMapping['message'] = 'Could not map the Restaurant Location Tags. Contact the system admin';
                return $tagMapping;
            }
        }

        if(!empty($data['curators']) && $data['curators'] != " "){
            $curatorMapping = $this->mapCurator($vendor_location_id, $data['curators'],$data['curator_tips']);

            if($curatorMapping['status'] !== 'success'){
                $curatorMapping['message'] = 'Could not map the Restaurant Location curators. Contact the system admin';
                return $curatorMapping;
            }
        }

        /*if(!empty($data['curators'])){
            $curatorMapping = $this->mapCurator($vendor_location_id, $data['curators'],$data['curator_tips']);

            if($curatorMapping['status'] !== 'success'){
                $curatorMapping['message'] = 'Could not map the Restaurant Location curators. Contact the system admin';
                return $curatorMapping;
            }
        }*/

        /*if(!empty($data['flags'])){
            $flagMapping = $this->mapFlags($vendor_location_id, $data['flags']);

            if($flagMapping['status'] !== 'success'){
                $flagMapping['message'] = 'Could not map the Vendor Location Flags. Contact the system admin';
                return $flagMapping;
            }
        }*/

        if(!empty($data['attributes']['flags']) && $data['attributes']['flags'] != " "){
            $flagMapping = $this->mapFlags($vendor_location_id, $data['attributes']['flags']);

            if($flagMapping['status'] !== 'success'){
                $flagMapping['message'] = 'Could not map the Vendor Location Flags. Contact the system admin';
                return $flagMapping;
            }
        }

        return ['status' => 'success'];

    }

    public function delete($vendor_location_id)
    {
        if(DB::table('vendor_locations')->where('id', $vendor_location_id)->count()){
            if(DB::table('vendor_locations')->delete($vendor_location_id)){
                return ['status' => 'success'];
            }else{
                return [
                    'status' => 'failure',
                    'action' => 'Delete the restaurant location using the restaurant id',
                    'message' => 'There was a problem while deleting the restaurant location. Please check if the restaurant still exists or contact the system admin'
                ];
            }
        }else{
            return [
                'status' => 'failure',
                'action' => 'Check if restaurant location exists based on the id',
                'message' => 'Could not find the restaurant location you are trying to delete. Try again or contact the sys admin'
            ];
        }
    }

    public function fetch($vendor_location_id)
    {

    }

    public function fetchBySlug($slug, array $filters)
    {

    }

    public function fetchBasicsAndSingleAttributes($vendor_location_id)
    {

        $selectCols = [
            'vl.slug',
            'v.name AS restaurant',
            'vl.location_id AS locality_id',
            'l.name AS locality',
            'va.address as address',
            'va.pin_code',
            'va.latitude',
            'va.longitude',
            'la.name AS area',
            'la.id AS area_id',
            'lc.name AS city',
            'lc.id AS city_id',
            'ls.name AS state',
            'ls.id AS state_id',
            'lco.name AS country',
            'lco.id AS country_id',
            DB::raw(('COUNT(DISTINCT vlr.id) AS total_reviews')),
            DB::raw('If(count(DISTINCT vlr.id) = 0, 0, ROUND(AVG(vlr.rating), 2)) AS average_rating'),
            DB::raw('MAX(vlbs.off_peak_schedule) AS feast')
        ];

        $select = DB::table('vendor_locations AS vl')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_location_address AS va', 'vl.id', '=', 'va.vendor_location_id')
            ->join('locations AS l', 'vl.location_id', '=', 'l.id')
            ->join('locations AS la', 'va.area_id', '=', 'la.id')
            ->join('locations AS lc', 'va.city_id', '=', 'lc.id')
            ->join('locations AS ls', 'va.state_id', '=', 'ls.id')
            ->join('locations AS lco', 'va.country_id', '=', 'lco.id')
            ->join('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_reviews AS vlr',function($join){
                $join->on('vlr.vendor_location_id', '=', 'vl.id')
                    ->on('vlr.status', '=', DB::raw('"Approved"'));
            })
            ->where('vl.id', $vendor_location_id)
            ->where('vl.status', 'Active')
            ->where('v.status', 'Publish')
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->groupBy('vl.id');

        $unique_attribute_types = [];

        if(count($this->attributesMap)){
            $singleSelectMultiAttrs = [];

            foreach($this->attributesMap as $attribute => $attData){
                if(!in_array($attData['type'], $unique_attribute_types))
                    $unique_attribute_types[] = $attData['type'];

                if($attData['value'] === 'single' && $attData['type'] !== 'single-select'){
                    $selectCols[] = DB::raw(
                        "MAX(IF(
                            {$this->typeTableAliasMap[$attData['type']]['va_alias']}.`alias` = '{$attribute}',
                            {$this->typeTableAliasMap[$attData['type']]['alias']}.`attribute_value`,
                            null
                        )) AS `{$attribute}`"
                    );
                }else{
                    $singleSelectMultiAttrs[] = ['type' => $attData['type'], 'attribute' => $attribute];
                }
            }

            foreach($unique_attribute_types as $type){
                if($type !== 'single-select' && $type !== 'multi-select'){
                    $select->leftJoin(
                        "{$this->typeTableAliasMap[$type]['table']} AS {$this->typeTableAliasMap[$type]['alias']}",
                        'vl.id', '=', "{$this->typeTableAliasMap[$type]['alias']}.vendor_location_id"
                    );

                    $select->leftJoin(
                        "vendor_attributes AS {$this->typeTableAliasMap[$type]['va_alias']}",
                        "{$this->typeTableAliasMap[$type]['va_alias']}.id", '=', "{$this->typeTableAliasMap[$type]['alias']}.vendor_attribute_id"
                    );
                }
            }
        }

        $restaurantBasics = $select->select($selectCols)->first();

        foreach($restaurantBasics as $key => $value){
            if($value){
                if(isset($this->attributesMap[$key])){
                    $this->attributes[$key] = $value;
                }else if(isset($this->address[$key])){
                    $this->address[$key] = $value;
                }else if(property_exists($this, $key)){
                    $this->$key = $value;
                }
            }
        }
    }

    protected function fetchSingleOptionsAndMultiAttributes($vendor_location_id, $mutiAttrs)
    {
        DB::table('vendor_locations AS vl')
            ->where('vl.id', $vendor_location_id);

        $selectCols = [];
    }

    protected function fetchMedia()
    {

    }

    protected function fetchTagsAndFlags()
    {

    }

    protected function fetchOtherLocations()
    {

    }

    protected function fetchScheduleAndBlockDates()
    {

    }

    protected function fetchTimeSlotsByDate()
    {

    }

    protected function fetchLimitsByDateAndTime()
    {

    }

    protected function saveAttributes($vendor_location_id, $attributes)
    {
        $attributeAliases = array_keys($attributes);

        if(count($attributeAliases)){
            $attributeIdMap = DB::table('vendor_attributes as va')
                ->join('vendor_type_attributes_map as vtam', 'vtam.vendor_attribute_id','=','va.id')
                ->join('vendor_types as vt','vt.id','=','vtam.vendor_type_id')
                ->whereIn('alias', $attributeAliases)
                ->where('vtam.attribute_for','Location')
                ->select('va.id as attribute_id', 'alias')
                ->lists('attribute_id', 'alias');

            if($attributeIdMap){
                $attributesMap = $this->config->get('restaurant_locations_attributes.attributesMap');
                $typeTableAliasMap = $this->config->get('restaurant_locations_attributes.typeTableAliasMap');
                $attribute_inserts = [];

                foreach($attributes as $attribute => $value){
                    if($value != "" || $value != " "){
                        //echo "attr <pre>"; print_r($attribute); echo ", value = ,".$value.", <br/>";
                        if(isset($attributeIdMap[$attribute])){
                            if(!isset($attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']]))
                                $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']] = [];

                            if($attributesMap[$attribute]['type'] === 'single-select'){
                                $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                    'vendor_location_id' => $vendor_location_id,
                                    'vendor_attributes_select_option_id' => $value
                                ];
                            }else if($attributesMap[$attribute]['value'] === 'multi' && is_array($value)) {
                                if($attributesMap[$attribute]['type'] === 'multi-select' && $value != ""){
                                    foreach ($value as $singleValue) {
                                        $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                            'vendor_location_id' => $vendor_location_id,
                                            'vendor_attributes_select_option_id' => $singleValue
                                        ];
                                    }
                                }else{
                                    if($value != "") {
                                        foreach ($value as $singleValue) {
                                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                                'vendor_location_id' => $vendor_location_id,
                                                'vendor_attribute_id' => $attributeIdMap[$attribute],
                                                'attribute_value' => $singleValue
                                            ];
                                        }
                                    }
                                }
                            }else{
                                if($value != ""){
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'vendor_location_id' => $vendor_location_id,
                                        'vendor_attribute_id' => $attributeIdMap[$attribute],
                                        'attribute_value' => $value
                                    ];
                                }

                            }
                        }
                    }

                }

                $attributeInserts = true;

                foreach($attribute_inserts as $table => $insertData){
                    //echo "table <pre>"; print_r($table); echo " , insert data = "; print_r($insertData);
                    $restauranrAttrInsert = DB::table($table)->insert($insertData);

                    if(!$restauranrAttrInsert){
                        $attributeInserts = false;
                        break;
                    }
                }
                //die;
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

    protected function saveAddress($vendor_location_id, $location_id, $address)
    {
        $locations = DB::table('locations_tree as lt')
                        ->join('locations as l', 'l.id','=','lt.ancestor')
                        ->select(
                            DB::raw('MAX(IF(l.type = "Country", lt.ancestor, null)) as country_id'),
                            DB::raw('MAX(IF(l.type = "State", lt.ancestor, null)) as state_id'),
                            DB::raw('MAX(IF(l.type = "City", lt.ancestor, null)) as city_id'),
                            DB::raw('MAX(IF(l.type = "Area", lt.ancestor, null)) as area_id')
                        )->where('lt.descendant', $location_id)->first();

        //echo "<pre>"; print_r($locations); die;

        if($locations){
            $addressInsert = DB::table('vendor_location_address')->insert([
                'vendor_location_id' => $vendor_location_id,
                'address' => (isset($address['address']) && $address['address'] != "" ? $address['address'] : ''),
                'pin_code' => (isset($address['pin_code']) && $address['pin_code'] != "" ? $address['pin_code'] : ''),
                'area_id' => (isset($locations->area_id) && $locations->area_id != "" ? $locations->area_id : ''),
                'city_id' => (isset($locations->city_id) && $locations->city_id != "" ? $locations->city_id : ''),
                'state_id' => (isset($locations->state_id) && $locations->state_id != "" ? $locations->state_id : ''),
                'country_id' => (isset($locations->country_id) && $locations->country_id != "" ? $locations->country_id : ''),
                'latitude' => (isset($address['latitude']) && $address['latitude'] != "" ? $address['latitude'] : 0),
                'longitude' => (isset($address['longitude']) && $address['longitude'] != "" ? $address['longitude'] : 0)
            ]);

            if($addressInsert){
                return ['status' => 'success'];
            }else{
                DB::rollBack();
                return [
                    'status' => 'failure',
                    'action' => 'Inserting the restaurant address into the DB'
                ];
            }
        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Fetching the restaurant locations properties'
            ];
        }
    }

    protected function saveLocationAttributes($vendor_location_id, $location_attributes)
    {
        $location_attributes_insert = [
            'vendor_location_id' => $vendor_location_id,
            'min_people_per_reservation' => (isset($location_attributes['min_people_per_reservation']) && $location_attributes['min_people_per_reservation'] != "" ? $location_attributes['min_people_per_reservation'] : 0),
            'max_people_per_reservation' => (isset($location_attributes['min_people_per_reservation']) && $location_attributes['max_people_per_reservation'] != "" ? $location_attributes['max_people_per_reservation'] : 0),
            'max_reservations_per_time_slot' => (isset($location_attributes['max_reservations_per_time_slot']) && $location_attributes['max_reservations_per_time_slot'] != "" ? $location_attributes['max_reservations_per_time_slot'] : 0),
            'max_reservations_per_day' => (isset($location_attributes['max_reservations_per_day']) && $location_attributes['max_reservations_per_day'] != "" ? $location_attributes['max_reservations_per_day'] : 0),
            'off_peak_hour_discount_min_covers' => (isset($location_attributes['off_peak_hour_discount_min_covers']) && $location_attributes['off_peak_hour_discount_min_covers'] != "" ? $location_attributes['off_peak_hour_discount_min_covers'] : 0),
            'max_people_per_day' => (isset($location_attributes['max_people_per_day']) && $location_attributes['max_people_per_day'] != "" ? $location_attributes['max_people_per_day'] : 0),
            'minimum_reservation_time_buffer' => (isset($location_attributes['minimum_reservation_time_buffer']) && $location_attributes['minimum_reservation_time_buffer'] != "" ? $location_attributes['minimum_reservation_time_buffer'] : 0),
            'maximum_reservation_time_buffer' => (isset($location_attributes['maximum_reservation_time_buffer']) && $location_attributes['maximum_reservation_time_buffer'] != "" ? $location_attributes['maximum_reservation_time_buffer'] : 0),
            'min_people_increments' => (isset($location_attributes['min_people_increments_per_reservation']) && $location_attributes['min_people_increments_per_reservation'] != "" ? $location_attributes['min_people_increments_per_reservation'] : 0),
        ];


        if(DB::table('vendor_locations_limits')->insert($location_attributes_insert)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Restaurant Location Limits into the DB'
            ];
        }
    }

    /*protected function saveMedia($vendor_location_id, $medias)
    {
        foreach($medias as $media_key => $media_value) {
            //echo "<pre>"; print_r($media_key); print_r($media_value);
            $file = $this->media->file($media_value);
            if ($media_key == "gallery_images") {
                foreach ($media_value as $gallery_images) {
                    $file = $this->media->file($gallery_images);
                }
            }
            $this->media->save_manually($file , $vendor_location_id);
        }
    }*/

    protected function saveMedia($vendor_location_id, $media)
    {
        /*$mediaSizes = $this->config->get('media.sizes');
        $uploads_dir = $this->config->get('media.base_path');*/
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
                'vendor_location_id' => $vendor_location_id,
                'media_type' => 'listing',
                'media_id' => $media['listing_image'],
                'order' => 0
            ];
        }

        if(isset($media['gallery_images'])){
            /*$galleryfiles = DB::table('media as m')
                                ->leftJoin('media_resized as mr', 'mr.media_id','=', 'm.id')
                                ->select(
                                    'm.id',
                                    'm.file',
                                    DB::raw('MAX(IF(mr.height = '.$mediaSizes['gallery']['height'].' && mr.width = '.$mediaSizes['gallery']['width'].', true, false)) as resized_exists'))
                                ->whereIn('m.id', $media['gallery_images'])
                                ->groupBy('m.id')
                                ->get();*/

            foreach($media['gallery_images'] as $key => $image){
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
                    'vendor_location_id' => $vendor_location_id,
                    'media_type' => 'gallery',
                    'media_id' => $image,
                    'order' => $key
                ];
            }
        }

        if(isset($media['mobile'])){
            /*$listing_image = DB::table('media as m')
                ->leftJoin('media_resized as mr', 'mr.media_id','=', 'm.id')
                ->select(
                    'm.id',
                    'm.file',
                    DB::raw('MAX(IF(mr.height = '.$mediaSizes['mobile_listing_ios_alacarte']['height'].' && mr.width = '.$mediaSizes['mobile_listing_ios_alacarte']['width'].', true, false)) as resized_exists')
                )
                ->where('m.id', $media['listing_image'])
                ->first();


            if(!$listing_image->resized_exists){
                $listing_file = $listing_image->file;
                $fileInfo = new \SplFileInfo($listing_file);
                $fileExtension = $fileInfo->getExtension();
                $listing_filename = $fileInfo->getBasename('.'.$fileExtension);
                $listing_resized_imagename = $listing_filename.'_'.$mediaSizes['mobile_listing_ios_alacarte']['width'].'x'.$mediaSizes['mobile_listing_ios_alacarte']['height'].'.'.$fileExtension;

                $this->queue->push(new ImageResizeSendToCloud(
                    $listing_image->id,
                    $uploads_dir,
                    $listing_resized_imagename,
                    $uploads_dir.$listing_file,
                    $mediaSizes['mobile_listing_ios_alacarte']['width'],
                    $mediaSizes['mobile_listing_ios_alacarte']['height']
                ));

            }*/

            $media_insert_map[] = [
                'vendor_location_id' => $vendor_location_id,
                'media_type' => 'mobile',
                'media_id' => $media['mobile'],
                'order' => 0
            ];
        }

        /*if(isset($media['mobile_listing_image'])){
            $listing_image = DB::table('media as m')
                ->leftJoin('media_resized as mr', 'mr.media_id','=', 'm.id')
                ->select(
                    'm.id',
                    'm.file',
                    DB::raw('MAX(IF(mr.height = '.$mediaSizes['mobile_listing_andriod_alacarte']['height'].' && mr.width = '.$mediaSizes['mobile_listing_andriod_alacarte']['width'].', true, false)) as resized_exists')
                )
                ->where('m.id', $media['listing_image'])
                ->first();


            if(!$listing_image->resized_exists){
                $listing_file = $listing_image->file;
                $fileInfo = new \SplFileInfo($listing_file);
                $fileExtension = $fileInfo->getExtension();
                $listing_filename = $fileInfo->getBasename('.'.$fileExtension);
                $listing_resized_imagename = $listing_filename.'_'.$mediaSizes['mobile_listing_andriod_alacarte']['width'].'x'.$mediaSizes['mobile_listing_andriod_alacarte']['height'].'.'.$fileExtension;

                $this->queue->push(new ImageResizeSendToCloud(
                    $listing_image->id,
                    $uploads_dir,
                    $listing_resized_imagename,
                    $uploads_dir.$listing_file,
                    $mediaSizes['mobile_listing_andriod_alacarte']['width'],
                    $mediaSizes['mobile_listing_andriod_alacarte']['height']
                ));

            }

            $media_insert_map[] = [
                'vendor_location_id' => $vendor_location_id,
                'media_type' => 'mobile_listing_andriod_alacarte',
                'media_id' => $media['mobile_listing_image'],
                'order' => 0
            ];
        }*/

        if(count($media_insert_map)){
            $mediaMapInsert = DB::table('vendor_locations_media_map')->insert($media_insert_map);

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

    protected function saveSchedules($vendor_location_id, $schedules)
    {
        $schedules_insert_map = [];

        foreach($schedules as $schedule){
            //echo "<pre>"; print_r($schedule);
            if(isset($schedule['id']) && ($schedule['id'] != "" || $schedule['id'] != 0)) {
                $schedules_insert_map[] = [
                    'vendor_location_id' => $vendor_location_id,
                    'schedule_id' => $schedule['id'],
                    'off_peak_schedule' => (isset($schedule['off_peak']) ? $schedule['off_peak'] : 0),
                    //'max_reservations' => $schedule['max_reservations']
                ];
            }
        }

        if(DB::table('vendor_location_booking_schedules')->insert($schedules_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Restaurant Location schedule into the DB'
            ];
        }
    }

    protected function saveBlockDates($vendor_location_id, $block_dates)
    {
        $block_dates_insert_map = [];

        foreach($block_dates as $date){
            if(strtotime($date) > strtotime('midnight')){
                $block_dates_insert_map[] = [
                    'vendor_location_id' => $vendor_location_id,
                    'block_date' => date('Y-m-d',strtotime($date))
                ];
            }
        }

        if(count($block_dates_insert_map)){
            if(DB::table('vendor_location_blocked_schedules')->insert($block_dates_insert_map)){
                return ['status' => 'success'];
            }else{
                DB::rollback();
                return [
                    'status' => 'failure',
                    'action' => 'Inserting the Restaurant Location Block Schedules into the DB'
                ];
            }
        }else{
            return ['status' => 'success'];
        }
    }

    protected function saveTimeRangeLimits($vendor_location_id, $time_range_limits)
    {
        $time_range_limit_insert_map = [];

        foreach($time_range_limits as $time_range_limit){
            if($time_range_limit['limit_by'] === 'Date'){
                if(strtotime($time_range_limit['date']) > strtotime('midnight')){
                    $time_range_limit_insert_map[] = [
                        'vendor_location_id' => $vendor_location_id,
                        'limit_by' => $time_range_limit['limit_by'],
                        'start_time' => $time_range_limit['from_time'],
                        'end_time' => $time_range_limit['to_time'],
                        'max_covers_limit' => ($time_range_limit['max_covers_limit'] ? $time_range_limit['max_covers_limit'] : 0),
                        'max_tables_limit' => ($time_range_limit['max_tables_limit'] ? $time_range_limit['max_tables_limit'] : 0),
                        'date' => $time_range_limit['date'],
                        'day' => null
                    ];
                }
            }else{
                $time_range_limit_insert_map[] = [
                    'vendor_location_id' => $vendor_location_id,
                    'limit_by' => $time_range_limit['limit_by'],
                    'start_time' => $time_range_limit['from_time'],
                    'end_time' => $time_range_limit['to_time'],
                    'max_covers_limit' => ($time_range_limit['max_covers_limit'] ? $time_range_limit['max_covers_limit'] : 0),
                    'max_tables_limit' => ($time_range_limit['max_tables_limit'] ? $time_range_limit['max_tables_limit'] : 0),
                    'date' => null,
                    'day' => $time_range_limit['day']
                ];
            }
        }

        if(count($time_range_limit_insert_map)){
            if(DB::table('vendor_location_booking_time_range_limits')->insert($time_range_limit_insert_map)){
                return ['status' => 'success'];
            }else{
                DB::rollback();
                return [
                    'status' => 'failure',
                    'action' => 'Inserting the Restaurant Location Time Range Limits into the DB'
                ];
            }
        }else{
            return ['status' => 'success'];
        }
    }

    protected function saveContacts($vendor_location_id, $contacts)
    {
        $contact_insert_map = [];

        foreach($contacts as $contact){
            $contact_insert_map[] = [
                'vendor_location_id' => $vendor_location_id,
                'name' => $contact['name'],
                'designation' => $contact['designation'],
                'phone_number' => $contact['phone_number'],
                'email' => $contact['email']
            ];
        }

        if(DB::table('vendor_location_contacts')->insert($contact_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Restaurant Location Contacts into the DB'
            ];
        }
    }

    protected function mapTags($vendor_location_id, $tags)
    {
        $tag_insert_map = [];

        foreach($tags as $tag){
            $tag_insert_map[] = [
                'vendor_location_id' => $vendor_location_id,
                'tag_id' => $tag
            ];
        }

        if(DB::table('vendor_locations_tags_map')->insert($tag_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Restaurant Location Contacts into the DB'
            ];
        }
    }

    protected function mapCurator($vendor_location_id, $curator,$curator_tips)
    {
        $curator_insert_map = [
            'vendor_location_id' => $vendor_location_id,
            'curator_id' => $curator,
            'curator_tips' => $curator_tips
        ];


        if(DB::table('vendor_locations_curator_map')->insert($curator_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Restaurant Location Curator into the DB'
            ];
        }
    }

    protected function mapFlags($vendor_location_id, $flags){
        //$flags_insert_map = [];

        //foreach($flags as $flag){
            $flags_insert_map = [
                'vendor_location_id' => $vendor_location_id,
                'flag_id' => $flags
            ];
        //}

        if(DB::table('vendor_locations_flags_map')->insert($flags_insert_map)){
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