<?php namespace WowTables\Http\Models;

use DB;

class VariantExperience extends Experience {

    public function create($data)
    {

    }

    public function update($experienceId, $data)
    {

    }

    public function delete($experienceId)
    {

    }

    public function fetch($experienceId)
    {

    }

    public function fetchBySlug($slug)
    {

    }

    protected function mapVariantToComplexProduct($experienceId, $complexProductId, $variantOptionId)
    {
        $complexProductMapping = DB::table('product_complex_options_map')->insert([
            'complex_product_id' => $complexProductId,
            'variant_product_id' => $experienceId,
            'product_variant_option_id' => $variantOptionId
        ]);

        if($complexProductMapping){
            return ['status' => 'success'];
        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Inserting the complex product mapping to the variant option and variant product'
            ];
        }
    }
} 