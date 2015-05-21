<?php namespace WowTables\Http\Models;

use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation;
use DB;
use Config;

class Locations {

    protected $hierarchy = [
        'Locality'  => 'Area',
        'Area'      => 'City',
        'City'      => 'State',
        'State'     => 'Country'
    ];

    public function fetch($data)
    {

        $total_locations = $this->getCount();

        if($total_locations){
            $query = '
                SELECT
                    ld.`id` AS `location_id`,
                    ld.`name` AS `location`,
                    ld.`slug` AS `slug`,
                    IF(la.`id` = ld.`id`, "", la.`id`) AS `parent_id`,
                    IF(la.`id` = ld.`id`, "", la.`name`) AS `parent`,
                    CAST(ld.type AS CHAR) AS `location_type`
                FROM locations_tree AS `lt`
                INNER JOIN locations AS `ld` ON lt.`descendant` = ld.`id`
                INNER JOIN locations AS `la` ON lt.`ancestor` = la.`id`
                WHERE (lt.`length` = 1 OR ld.`type` = ?)
            ';

            $params = ['Country'];

            if(!empty($data['search'])){
                $query .= ' AND (ld.`name` LIKE ? OR ld.`type` LIKE ?)';
                $params[] = '%'.$data['search']['value'].'%';
                $params[] = '%'.$data['search']['value'].'%';
            }


            if(isset($data['order'])){
                $orderBy = [];

                foreach($data['order'] as $order){
                    $columnIdx = intval($order['column']);
                    $requestColumn = $data['columns'][$columnIdx];

                    if ( $requestColumn['orderable'] == 'true' ) {
                        $dir = $order['dir'] === 'asc' ? 'ASC' : 'DESC';

                        $orderBy[] = '`'.$requestColumn['name'].'` '.$dir;
                    }
                }

                $query .= ' ORDER BY '.implode(', ', $orderBy);
            }


            $query .= ' LIMIT ?,?';
            $params[] = $data['start'];
            $params[] = $data['length'];



            $locations = DB::select($query, $params);

            if(!empty($locations)){
                $dtLocations = [
                    'draw' => $data['draw'],
                    'recordsTotal' => $total_locations,
                    'recordsFiltered' => $total_locations,
                    'data' => []
                ];

                foreach($locations as $location){
                    $dtLocations['data'][] = [
                        $location->location,
                        $location->slug,
                        $location->location_type,
                        $location->parent,
                        "
                            <a
                                href='javascript:void(0);'
                                title='edit''
                                data-location_id='{$location->location_id}'
                                data-location_parent_id='{$location->parent_id}'
                            >
                                <i class='fa fa-edit'></i>
                            </a>
                            &nbsp;|&nbsp;
                            <a href='javascript:void(0);' title='remove'>
                                <i class='fa fa-trash-o'></i>
                            </a>
                        "
                    ];
                }

                return $dtLocations;
            }else{
                return [
                    'draw' => $data['draw'],
                    'recordsTotal' => $total_locations,
                    'recordsFiltered' => $total_locations,
                    'data' => []
                ];
            }
        }else{
            return [
                'draw' => $data['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];
        }

    }

    /**
     * Add a new location along with its mappings to the database
     *
     * @param string $name
     * @param string $slug
     * @param string $type
     * @param int $visible
     * @param int|null $parent_id
     *
     * @return array
     */
    public function add($name, $slug, $type, $parent_id = null)
    {
        if($type === 'Country'){
            if(is_null($parent_id)){

                $country_slug_exists = DB::table('locations')->where('name', $name)->orWhere('slug', $slug)->count();

                if(!$country_slug_exists){

                    DB::beginTransaction();

                    $country_id = DB::table('locations')->insertGetId(
                        ['name' => $name, 'slug' => $slug, 'type' => $type]
                    );

                    if($country_id){
                        $location_mapping = DB::table('locations_tree')->insert(
                            ['ancestor' => $country_id, 'descendant' => $country_id, 'length' => 0]
                        );

                        if($location_mapping){
                            DB::commit();
                            return [ 'status' => 'success' ];
                        }else{
                            DB::rollback();
                            return [
                                'status' => 'failure',
                                'message' => 'There was a problem inserting the country mapping'
                            ];
                        }
                    }else{
                        DB::rollback();
                        return [
                            'status' => 'failure',
                            'message' => 'There was a problem inserting the country'
                        ];
                    }

                }else{
                    return [
                        'status' => 'failure',
                        'message' => 'The coutry name or slug you mentioned already exists'
                    ];
                }


            }else{
                return [
                    'status' => 'failure',
                    'message' => 'Invalid parent entered. The country cannot have a parent'
                ];
            }

        }else{
            $parent_type = DB::table('locations')->where('id', $parent_id)->pluck('type');

            if($parent_type){
                if($parent_type === $this->hierarchy[$type]){
                    $slug_exists = DB::table('locations')->where('name', $name)->orWhere('slug', $slug)->count();

                    if(!$slug_exists){
                        $query = '
                            SELECT count(*) as `location_mapping_exists`
                            FROM locations_tree
                            WHERE ancestor = ? AND descendant = (
                              SELECT id FROM locations WHERE name = ?
                            )
                        ';

                        $location_map_exists = DB::select($query, [$parent_id, $name]);

                        if($location_map_exists){

                            DB::beginTransaction();

                            $location_id = DB::table('locations')->insertGetId(
                                ['name' => $name, 'slug' => $slug, 'type' => $type]
                            );

                            if($location_id){
                                $query = "
                                    INSERT INTO locations_tree (`ancestor`, `descendant`, `length`)
                                    SELECT t.`ancestor`, {$location_id}, t.`length`+1
                                    FROM locations_tree AS t
                                    WHERE t.`descendant` = {$parent_id}
                                    UNION ALL
                                    SELECT {$location_id}, {$location_id}, 0
                                ";

                                $location_mapping = DB::insert($query);

                                if($location_mapping){
                                    DB::commit();
                                    return [ 'status' => 'success' ];
                                }else{
                                    DB::rollback();
                                    return [
                                        'status' => 'failure',
                                        'message' => 'There was a problem inserting the location mapping'
                                    ];
                                }
                            }else{
                                DB::rollback();
                                return [
                                    'status' => 'failure',
                                    'message' => 'There was a problem inserting the location'
                                ];
                            }

                        }else{
                            return [
                                'status' => 'failure',
                                'message' => 'The location name and parent map you entered already exists. Please try a different one'
                            ];
                        }
                    }else{
                        return [
                            'status' => 'failure',
                            'message' => 'The slug you mentioned already exists'
                        ];
                    }
                }else{
                    return [
                        'status' => 'failure',
                        'message' => 'The parent used is not of the valid type. Please choose a '. $this->hierarchy[$type]. ' for the parent'
                    ];
                }
            }else{
                return [
                    'status' => 'failure',
                    'message' => 'The parent you chose does not exist. Please try again'
                ];
            }
        }

    }

    /**
     * Update an already existing location and its mappings
     *
     * @param int $location_id
     * @param string $name
     * @param string $slug
     * @param string $type
     * @param int $visible
     * @param int|null $parent_id
     *
     * @return array
     */
    public function update($location_id, $name, $slug, $type, $parent_id = null)
    {

    }

    /**
     * Delete a location and all its mappings
     *
     * @param int $location_id
     *
     * return array
     */
    public function remove($location_id)
    {
        $vendorLocationCount = DB::table('vendor_locations')->where('location_id')->count();

        if(!$vendorLocationCount){
            DB::table('locations')->where('id', $location_id)->delete();

            return ['status' => 'success'];
        }else{
            return [
                'status' => 'failure',
                'action' => 'Check the count of vendors mapped to the location',
                'message' => 'The location that you seek to delete cannot be deleted. Plese contact the sys admin'
            ];
        }
    }

    public function slugGenerate($location_name)
    {
        $default_slug = str_replace(' ','-',strtolower(trim($location_name)));
        $slug_options = [$default_slug];

        for($i = 2; $i < 10 ;$i++){
            $slug_options[] = $default_slug . $i;
        }

        $existing_slugs = DB::table('locations')->whereIn('slug',$slug_options)->lists('slug');

        if($existing_slugs){
            $available_slugs = array_diff($slug_options,$existing_slugs);

            if(!empty($available_slugs)){
                return ['slug' => array_shift($available_slugs)];
            }else{
                return ['slug' => uniqid()];
            }
        }else{
            return ['slug' => $default_slug];
        }
    }

    public function getParents($child_type)
    {
        if(isset($this->hierarchy[$child_type])){
            return $this->getLocationsByType($this->hierarchy[$child_type]);
        }else{
            return false;
        }
    }

    public function getLocationsByType($type)
    {
        return DB::table('locations')->where('type',$type)->where('visible', 1)->lists('name','id');
    }

    protected function getCount()
    {
         return DB::table('locations')->count();
    }
	
	//-----------------------------------------------------------------
	
	/**
	 * Returns all the location of a Vendor matching
	 * the passed id.
	 * 
	 * @static	true
	 * @access	public
	 * @param	integer	$vendorID
	 * @return	array
	 * @since	v1.0.0
	 */
	public static function getVendorLocation($vendorID) {
		//array to contain the list of locations
		$arrLocation = array();
		
		foreach(VendorLocation::with('location')->where('vendor_id','=',$vendorID)->get() as $vendorLocation) {
			$arrLocation[] = array(
									'name' => $vendorLocation->location->name,
									'slug' => $vendorLocation->slug 
								);
		}
		
		return $arrLocation;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the vendor location ids for a product.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$productID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getProductVendorLocation($productID) {
		$queryResult = DB::table(DB::raw('product_vendor_locations as pvl'))
						->join(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','pvl.vendor_location_id')
						->join('locations','locations.id','=','vla.area_id')
						->where('pvl.product_id',$productID)
						->select('pvl.vendor_location_id as id','locations.name as area','vla.latitude','vla.longitude')
						->get();
		//array to hold location details
		$arrLocation = array();
		if($queryResult) {
			foreach($queryResult as $row) {
				$arrLocation[] = array(
									'pvl_id' =>	$row->id,
									'area' => $row->area,
									'latitude' => $row->latitude,
									'longitude' => $row->longitude
								);
			}
		}		
		return $arrLocation;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the vendor location ids for a vendor.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$vendorID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getVendorLocationDetails($vendorID) {
		$queryResult = DB::table(DB::raw('vendor_locations as vl'))
						->join(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','vl.id')
						->join('locations','locations.id','=','vla.area_id')
						->where('vl.vendor_id',$vendorID)
						->select('vl.id','locations.name as area','vla.latitude','vla.longitude')
						->get();
		//array to hold location details
		$arrLocation = array();
		if($queryResult) {
			foreach($queryResult as $row) {
				$arrLocation[] = array(
									'vl_id' =>	$row->id,
									'area' => $row->area,
									'latitude' => $row->latitude,
									'longitude' => $row->longitude
								);
			}
		}		
		return $arrLocation;
	}


    //-----------------------------------------------------------------

    /**
     * Reads the cities Area
     *
     * @access	public
     * @static	true
     * @param	integer	$cityID
     * @return	array
     * @since	1.0.0
     */
    public static function  readCityArea($cityID){

                $queryCityArea=DB::table('locations as l')
                                    ->join('locations_tree as lt','l.id','=','lt.descendant')
                                    ->where('lt.ancestor',$cityID)
                                    ->where('lt.length',1)
                                    ->select('l.name', 'l.id')
                                    ->get();

        //array to contain the response to be sent back to client
        $arrResponse = array();

        if($queryCityArea) {
            $arrResponse['status'] = Config::get('constants.API_SUCCESS');
            foreach($queryCityArea as $row){
                $arrResponse['data'][]=array(
                                            'location_id' => $row->id,
                                            'location_name' => $row->name,
                                           );
            }
        }
        else {
            $arrResponse['status'] = Config::get('constants.API_SUCCESS');
            $arrResponse['data'] = array();
        }


        return $arrResponse;
    }

    //-----------------------------------------------------------------

}
//end of class Locations.php
