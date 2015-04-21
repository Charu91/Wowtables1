<?php namespace WowTables\Http\Models;

use Carbon\Carbon;
use DB;

class RestaurantLocations extends VendorLocations{

    public $filters = [
        'date' => [],
        'time' => [],
        'pricing_level' => [
            'options' => ['Low','Medium','High']
        ],
        'cuisines' => [
            'options' => [],
            'active' => []
        ],
        'tags' => [
            'options' => [],
            'active' => []
        ],
        'areas' => [
            'options' => [],
            'active' => []
        ],
    ];

    public $listing;

    public $total_count;

    public $total_pages;

    protected $filterOptions = ['date', 'time', 'tags', 'areas','cuisines','pricing_level'];

    public $sort_options = ['Latest','Popular'];

    public function fetchAll($data){
        $params = [$data['filters']];

        if(isset($data['items_per_page'])){
            $params[] = $data['items_per_page'];
        }

        if(isset($data['sort_by'])){
            $params[] = $data['sort_by'];
        }

        if(isset($data['pagenum'])){
            $params[] = $data['pagenum'];
        }

        call_user_func_array([$this, 'fetchListings'], $params);
    }

    public function fetchListings(array $filters, $items_per_page = 10, $sort_by = 'Latest', $pagenum = null ){
        if(!$pagenum) $pagenum = 1;

        $offset = ($pagenum - 1) * $items_per_page;

        $select = DB::table('vendor_locations AS vl')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('vendor_location_address AS vladd', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('locations AS l', 'l.id', '=', 'vl.location_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            ->join('vendor_locations_media_map AS vmm', function($join){
                $join->on('vmm.vendor_location_id', '=', 'vl.id')
                    ->on('vmm.order','=', DB::raw('0'))
                    ->on('vmm.media_type', '=', DB::raw('"gallery"'));
            })
            ->join('media_resized AS mr', function($join){
                $join->on('vmm.media_id', '=', 'mr.media_id')
                    ->on('mr.width','=', DB::raw('600'))
                    ->on('mr.height', '=', DB::raw('400'));
            })// Make the width and height dynamic
            ->join('media AS m', 'm.id', '=', 'mr.media_id')
            ->join('vendor_location_attributes_varchar AS vlav', 'vl.id', '=', 'vlav.vendor_location_id')
            ->join('vendor_attributes AS va', 'va.id', '=', 'vlav.vendor_attribute_id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlam', 'vl.id', '=', 'vlam.vendor_location_id')
            ->leftJoin('vendor_attributes AS vamso', function($join){
                $join->on('va.id', '=', 'vlav.vendor_attribute_id')
                    ->on('vamso.alias','=', DB::raw('"cuisines"'));
            })
            ->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
            ->join('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_blocked_schedules AS vlbls', 'vlbls.vendor_location_id', '=', 'vl.id')
            ->join('schedules AS s', 'vlbs.schedule_id', '=', 's.id')
            ->join('time_slots AS ts', 's.time_slot_id', '=', 'ts.id')
            ->leftJoin('vendor_locations_tags_map AS vltm', 'vl.id', '=', 'vltm.vendor_location_id')
            ->leftJoin('vendor_location_reviews AS vlr', function($join){
                $join->on('vl.id', '=', 'vlr.vendor_location_id')
                    ->on('vlr.status','=', DB::raw('"Approved"'));
            })
            ->select(
                DB::raw('SQL_CALC_FOUND_ROWS vl.id'),
                'v.name AS restaurant',
                'l.name AS locality',
                'la.name AS area',
                'vl.pricing_level',
                'mr.file AS image',
                'm.alt AS image_alt',
                'm.title AS image_title',
                DB::raw('MAX(IF(va.alias = "short_description", vlav.attribute_value, null)) AS short_description'),
                DB::raw('MAX(vlbs.off_peak_schedule) AS off_peak_available'),
                DB::raw(('COUNT(DISTINCT vlr.id) AS total_reviews')),
                DB::raw('If(count(DISTINCT vlr.id) = 0, 0, ROUND(AVG(vlr.rating), 2)) AS rating')
            )
            ->where('vt.type', DB::raw('"Restaurants"'))
            ->where('v.status', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->groupBy('vl.id')
            ->skip($offset)->take($items_per_page);



        if(isset($filters['areas'])){
            $select->whereIn('la.id', $filters['areas']);
            $this->filters['areas']['active'] = $filters['areas'];
        }

        if(isset($filters['pricing_level'])){
            $select->where('vl.pricing_level', $filters['pricing_level']);
            $this->filters['pricing_level']['active'] = $filters['pricing_level'];
        }

        if(isset($filters['tags'])){
            $select->whereIn('vltm.tag_id', $filters['tags']);
            $this->filters['tags']['active'] = $filters['tags'];
        }

        if(isset($filters['cuisines'])){
            $select->whereIn('vaso.id', $filters['cuisines']);
            $this->filters['tags']['cuisines'] = $filters['cuisines'];
        }

        if(isset($filters['date']) && isset($filters['time'])){
            $select->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']])
                ->where('ts.time', $filters['time']);
            $this->filters['tags']['date'] = $filters['date'];
            $this->filters['tags']['time'] = $filters['time'];
        }else if(isset($filters['date'])){
            $select->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']]);
            $this->filters['tags']['date'] = $filters['date'];
        }else if(isset($filters['time'])){
            $select->where('ts.time', $filters['time']);
            $this->filters['tags']['time'] = $filters['time'];
        }

        if($sort_by === 'Popular'){
            $select->orderBy('vl.id', 'asc');
        }else if($sort_by === 'Latest'){
            $select->orderBy('v.publish_time', 'desc');
        }else{
            $select->orderBy('v.publish_time', 'desc');
        }

        $this->listing = $select->get();

        $totalCountResult = DB::select('SELECT FOUND_ROWS() AS total_count');
        //dd($totalCountResult);
        if($totalCountResult){
            $this->total_count = $totalCountResult[0]->total_count;
            $this->total_pages = (int)ceil($this->total_count/$items_per_page);

            $this->fetchFilters($filters);
            $this->fetchMaxDate();
            $this->fetchTimings();
        }else{
            $this->total_count = 0;
            $this->total_pages = 0;
        }
    }

    public function fetchFilters($filters){
        $tags = DB::table('vendor_locations_tags_map AS vltm')
            ->join('tags AS t', 't.id', '=', 'vltm.tag_id')
            ->join('vendor_locations AS vl', 'vl.id', '=', 'vltm.vendor_location_id')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('vendor_location_address AS vladd', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlam', 'vl.id', '=', 'vlam.vendor_location_id')
            ->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS vamso', function($join){
                $join->on('vamso.id', '=', 'vaso.vendor_attribute_id')
                    ->on('vamso.alias', '=', DB::raw('"cuisines"'));
            })
            ->join('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_blocked_schedules AS vlbls', 'vlbls.vendor_location_id', '=', 'vl.id')
            ->join('schedules AS s', 'vlbs.schedule_id', '=', 's.id')
            ->join('time_slots AS ts', 's.time_slot_id', '=', 'ts.id')
            ->select(
                DB::raw('DISTINCT vltm.tag_id AS filter_id'),
                't.name AS filter_value',
                DB::raw('"tags" AS filter_type')
            )
            ->where('vt.type', '=', DB::raw('"Restaurants"'))
            ->where('v.status', '=', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->groupBy('vltm.tag_id');

        if(isset($filters['areas'])){
            $tags->whereIn('la.id', $filters['areas']);
        }

        if(isset($filters['pricing_level'])){
            $tags->where('vl.pricing_level', $filters['pricing_level']);
        }

        if(isset($filters['tags'])){
            $tags->whereIn('vltm.tag_id', $filters['tags']);
        }

        if(isset($filters['cuisines'])){
            $tags->whereIn('vaso.id', $filters['cuisines']);
        }

        if(isset($filters['date']) && isset($filters['time'])){
            $tags->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']])
                ->where('ts.time', $filters['time']);
        }else if(isset($filters['date'])){
            $tags->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']]);
        }else if(isset($filters['time'])){
            $tags->where('ts.time', $filters['time']);
        }

        $areas = DB::table('vendor_location_address AS vladd')
            ->join('vendor_locations AS vl', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlam', 'vl.id', '=', 'vlam.vendor_location_id')
            ->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS vamso', function($join){
                $join->on('vamso.id', '=', 'vaso.vendor_attribute_id')
                    ->on('vamso.alias', '=', DB::raw('"cuisines"'));
            })
            ->join('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_blocked_schedules AS vlbls', 'vlbls.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_locations_tags_map AS vltm', 'vl.id', '=', 'vltm.vendor_location_id')
            ->join('schedules AS s', 'vlbs.schedule_id', '=', 's.id')
            ->join('time_slots AS ts', 's.time_slot_id', '=', 'ts.id')
            ->select(
                DB::raw('DISTINCT vladd.area_id AS filter_id'),
                'la.name AS filter_value',
                DB::raw('"areas" AS filter_type')
            )
            ->where('vt.type', '=', DB::raw('"Restaurants"'))
            ->where('v.status', '=', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->groupBy('vladd.area_id');

        if(isset($filters['pricing_level'])){
            $areas->where('vl.pricing_level', $filters['pricing_level']);
        }

        if(isset($filters['cuisines'])) {
            $areas->whereIn('vaso.id', $filters['cuisines']);
        }

        if(isset($filters['tags'])){
            $areas->whereIn('vltm.tag_id', $filters['tags']);
        }

        if(isset($filters['date']) && isset($filters['time'])){
            $areas->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']])
                ->where('ts.time', $filters['time']);
        }else if(isset($filters['date'])){
            $areas->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']]);
        }else if(isset($filters['time'])){
            $areas->where('ts.time', $filters['time']);
        }

        $cuisines = DB::table('vendor_location_attributes_multiselect AS vlam')
            ->join('vendor_locations AS vl', 'vl.id', '=', 'vlam.vendor_location_id')
            ->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS vamso', function($join){
                $join->on('vamso.id', '=', 'vaso.vendor_attribute_id')
                    ->on('vamso.alias', '=', DB::raw('"cuisines"'));
            })
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('vendor_location_address AS vladd', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            ->join('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_blocked_schedules AS vlbls', 'vlbls.vendor_location_id', '=', 'vl.id')
            ->join('schedules AS s', 'vlbs.schedule_id', '=', 's.id')
            ->join('time_slots AS ts', 's.time_slot_id', '=', 'ts.id')
            ->leftJoin('vendor_locations_tags_map AS vltm', 'vl.id', '=', 'vltm.vendor_location_id')
            ->select(
                DB::raw('DISTINCT vlam.vendor_attributes_select_option_id AS filter_id'),
                'vaso.option AS filter_value',
                DB::raw('"cuisines" AS filter_type')
            )
            ->where('vt.type', '=', DB::raw('"Restaurants"'))
            ->where('v.status', '=', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->groupBy('vlam.vendor_attributes_select_option_id');

        if(isset($filters['areas'])){
            $cuisines->whereIn('la.id', $filters['areas']);
        }

        if(isset($filters['pricing_level'])){
            $cuisines->where('vl.pricing_level', $filters['pricing_level']);
        }

        if(isset($filters['tags'])){
            $cuisines->whereIn('vltm.tag_id', $filters['tags']);
        }

        if(isset($filters['cuisines'])){
            $cuisines->whereIn('vaso.id', $filters['cuisines']);
        }

        $filters = $tags->unionAll($areas)->unionAll($cuisines)->get();

        foreach($filters as $filter){
            if($filter->filter_type === 'areas'){
                $this->filters['areas']['options'][] = ['id' => $filter->filter_id, 'value' => $filter->filter_value];
            }else if($filter->filter_type === 'tags'){
                $this->filters['tags']['options'][] = ['id' => $filter->filter_id, 'value' => $filter->filter_value];
            }else if($filter->filter_type === 'cuisines'){
                $this->filters['cuisines']['options'][] = ['id' => $filter->filter_id, 'value' => $filter->filter_value];
            }
        }
    }

    public function fetchMaxDate()
    {
        $this->filters['date']['max_date'] = Carbon::today()->addDays(45)->toDateString();
    }

    public function fetchTimings(){
        $this->filters['time']['slots'] = DB::table('time_slots')->get(['time', 'slot_type']);
    }
} 