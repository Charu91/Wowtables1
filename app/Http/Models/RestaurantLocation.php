<?php namespace WowTables\Http\Models;

use DB;
use Image;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;

class RestaurantLocation extends VendorLocation{

    public $restaurant;

    public $slug;

    public $attributes;

    public $location;

    public $schedules;

    public $block_dates;

    public $time_range_limits;

    protected $config;

    protected $cloud;

    public function __construct(Config $config, Cloud $cloud)
    {
        $this->config = $config;
        $this->cloud = $cloud;
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        $vendorLocationInsertData = [
            'vendor_id' => $data['restaurant_id'],
            'slug' => $data['slug'],
            'location_id' => $data['location_id'],
            'status' => $data['status']
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

            if(!empty($data['media'])){
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

            if(!empty($data['tags'])){
                $tagMapping = $this->mapTags($restaurantLocationId, $data['tags']);

                if($tagMapping['status'] !== 'success'){
                    $tagMapping['message'] = 'Could not map the Restaurant Location Tags. Contact the system admin';
                    return $tagMapping;
                }
            }

            if(!empty($data['curators'])){
                $curatorMapping = $this->mapCurators($restaurantLocationId, $data['tags']);

                if($curatorMapping['status'] !== 'success'){
                    $curatorMapping['message'] = 'Could not map the Restaurant Location curators. Contact the system admin';
                    return $curatorMapping;
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
                  vlbls, vlbs, vlbtrl, vlc, vlcm, vlmm, vla, vltm
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
            WHERE vl.id = ?
        ';

        DB::delete($query, [$vendor_location_id]);

        $vendorLocationUpdateData = [
            'slug' => $data['slug'],
            'location_id' => $data['location_id'],
            'status' => $data['status']
        ];

        $restaurantUpdate = DB::table('vendor_locations')->where('id', $vendor_location_id)->update($vendorLocationUpdateData);


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
            if(isset($data['off_peak_schedules'])){
                $schedulesSaved = $this->saveSchedules($vendor_location_id ,$data['schedules'], $data['off_peak_schedules']);
            }else{
                $schedulesSaved = $this->saveSchedules($vendor_location_id ,$data['schedules']);
            }


            if($schedulesSaved['status'] !== 'success'){
                $schedulesSaved['message'] = 'Could not create the Restaurant Location Schedules. Contact the system admin';
                return $schedulesSaved;
            }
        }

        if(!empty($data['media'])){
            $mediaSaved = $this->saveMedia($vendor_location_id, $data['media']);

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

        if(!empty($data['tags'])){
            $tagMapping = $this->mapTags($vendor_location_id, $data['tags']);

            if($tagMapping['status'] !== 'success'){
                $tagMapping['message'] = 'Could not map the Restaurant Location Tags. Contact the system admin';
                return $tagMapping;
            }
        }

        if(!empty($data['curators'])){
            $curatorMapping = $this->mapCurators($vendor_location_id, $data['tags']);

            if($curatorMapping['status'] !== 'success'){
                $curatorMapping['message'] = 'Could not map the Restaurant Location curators. Contact the system admin';
                return $curatorMapping;
            }
        }

        DB::commit();
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
                    if(isset($attributeIdMap[$attribute])){
                        if(!isset($attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']]))
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']] = [];

                        if($attributesMap[$attribute]['type'] === 'single-select'){
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                'vendor_location_id' => $vendor_location_id,
                                'vendor_attributes_select_option_id' => $value
                            ];
                        }else if($attributesMap[$attribute]['value'] === 'multi' && is_array($value)) {
                            if($attributesMap[$attribute]['type'] === 'multi-select'){
                                foreach ($value as $singleValue) {
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'vendor_location_id' => $vendor_location_id,
                                        'vendor_attributes_select_option_id' => $singleValue
                                    ];
                                }
                            }else{
                                foreach ($value as $singleValue) {
                                    $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                        'vendor_location_id' => $vendor_location_id,
                                        'vendor_attribute_id' => $attributeIdMap[$attribute],
                                        'attribute_value' => $singleValue
                                    ];
                                }
                            }
                        }else{
                            $attribute_inserts[$typeTableAliasMap[$attributesMap[$attribute]['type']]['table']][] = [
                                'vendor_location_id' => $vendor_location_id,
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

        if($locations){
            $addressInsert = DB::table('vendor_location_address')->insert([
                'vendor_location_id' => $vendor_location_id,
                'address' => $address['address'],
                'pin_code' => $address['pin_code'],
                'area_id' => $locations->area_id,
                'city_id' => $locations->city_id,
                'state_id' => $locations->state_id,
                'country_id' => $locations->country_id,
                'latitude' => $address['latitude'],
                'longitude' => $address['longitude']
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

    protected function saveMedia($vendor_location_id, $media)
    {
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
                    'vendor_location_id' => $vendor_location_id,
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
                    'vendor_location_id' => $vendor_location_id,
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
            $schedules_insert_map[] = [
                'vendor_location_id' => $vendor_location_id,
                'schedule_id' => $schedule['id'],
                'off_peak_schedule' => $schedule['off_peak'],
                'max_reservations' => $schedule['max_reservations']
            ];
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
                    'block_date' => $date
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
                        'start_time' => $time_range_limit['from_time'],
                        'end_time' => $time_range_limit['to_time'],
                        'max_covers_limit' => $time_range_limit['max_covers_limit'],
                        'date' => $time_range_limit['date'],
                        'day' => null
                    ];
                }
            }else{
                $time_range_limit_insert_map[] = [
                    'vendor_location_id' => $vendor_location_id,
                    'start_time' => $time_range_limit['from_time'],
                    'end_time' => $time_range_limit['to_time'],
                    'max_covers_limit' => $time_range_limit['max_covers_limit'],
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

    protected function mapCurators($vendor_location_id, $curators)
    {
        $curator_insert_map = [];

        foreach($curators as $curator){
            $curator_insert_map[] = [
                'vendor_location_id' => $vendor_location_id,
                'curator_id' => $curator
            ];
        }

        if(DB::table('vendor_locations_curator_map')->insert($curator_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Restaurant Location Contacts into the DB'
            ];
        }
    }
} 