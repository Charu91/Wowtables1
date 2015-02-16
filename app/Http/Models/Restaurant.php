<?php namespace WowTables\Http\Models;

use DB;

class Restaurant extends Vendor{

    public $name;

    public $slug;

    protected $attributes = [

    ];

    public function add($data){

        DB::beginTransaction();

        //if($data['attributes'])


    }

    public function update($restaurant_id, $data)
    {
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
    }

    public function delete($restaurant_id)
    {

    }


}