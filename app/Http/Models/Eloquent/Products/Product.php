<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','slug','status','type'];

    protected $with = ['productType'];

    public function add($vendor_id, $product_type_id, $name, $slug, $status, $restaurant_locations, $short_description, $description, $seo_title, $seo_meta_description, $seo_meta_keywords, $main_image, $listing_image, $gallery_images, $min_people_per_reservation, $max_people_per_reservation, $max_reservation_per_time_slot, $max_reservation_per_day, $min_reservation_time_buffer, $max_reservation_time_buffer, $commision_per_reservation, $prepayment, $reward_points_per_reservation, $tax, $price_before_tax, $price_after_tax, $price_type, $commission_calculated_on, $addons, $allow_gift_card_redemption, $allow_cancellations, $terms_conditions, $publish_date, $publish_time)
    {
        $experience = new static(compact('vendor_id', 'product_type_id', 'name','slug','status','publish_time'));

        return $experience;
    }

    public function productType()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Products\ProductType', 'product_type_id', 'id');
    }

    public function attributesBoolean()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\ProductAttributesBoolean', 'product_id', 'id');
    }

    public function attributesDate()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\ProductAttributesDate', 'product_id', 'id');
    }

    public function attributesInteger()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\ProductAttributesInteger', 'product_id', 'id');
    }

    public function attributesFloat()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\ProductAttributesFloat', 'product_id', 'id');
    }

    public function attributesText()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\ProductAttributesText', 'product_id', 'id');
    }

    public function attributesVarChar()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\ProductAttributesVarChar', 'product_id', 'id');
    }

    public function attributesSingleSelect()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\ProductAttributesSingleSelect', 'product_id', 'id');
    }

    public function attributesMultiSelect()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\ProductAttributesMultiSelect', 'product_id', 'id');
    }

}
