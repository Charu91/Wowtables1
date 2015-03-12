<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;

class VendorLocation extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vendor_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vendor_id','location_id','slug','status'];

    protected $hidden = ['vendor_id','location_id'];

    protected $with = ['vendor','location'];

    public function vendor()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\Vendor','vendor_id','id');
    }

    public function location()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Location','location_id','id');
    }

    public function attributesBoolean()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesBoolean', 'vendor_location_id', 'id');
    }

    public function attributesDate()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesDate', 'vendor_location_id', 'id');
    }

    public function attributesInteger()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesInteger', 'vendor_location_id', 'id');
    }

    public function attributesFloat()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesFloat', 'vendor_location_id', 'id');
    }

    public function attributesText()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesText', 'vendor_location_id', 'id');
    }

    public function attributesVarChar()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesVarChar', 'vendor_location_id', 'id');
    }

    public function attributesSingleSelect()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesSingleSelect', 'vendor_location_id', 'id');
    }

    public function attributesMultiSelect()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesMultiSelect', 'vendor_location_id', 'id');
    }

    public function schedules()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationBookingSchedule', 'vendor_location_id', 'id');
    }

    public function add($vendor_id, $location_id, $title, $slug, $status, $short_description, $description, $seo_title, $seo_meta_description, $seo_meta_keywords, $main_image, $listing_image, $gallery_images, $min_people_per_reservation, $max_people_per_reservation, $max_reservation_per_time_slot, $max_reservation_per_day, $min_reservation_time_buffer, $max_reservation_time_buffer, $schedules, $allow_alacarte_reservation, $alacarte_terms_conditions, $address, $city, $state, $country, $pin_code, $latitude, $longitude, $driving_locations, $location_map, $cuisine, $collections, $commision_per_reservation, $prepayment, $reward_points_per_reservation, $publish_date, $publish_time)
    {
        $vendorLocation = new static(compact('vendor_id', 'location_id','slug','status'));

        return $vendorLocation;
    }

}
