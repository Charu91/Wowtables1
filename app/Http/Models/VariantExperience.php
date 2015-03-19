<?php namespace WowTables\Http\Models;

use DB;

class VariantExperience extends Experience {

    public function create($data)
    {
        DB::beginTransaction();

        $productTypeId = DB::table('product_types')->where('slug', 'experiences')->pluck('id');

        $variantExperienceInsertData = [
            'product_type_id' => $productTypeId,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status'],
            'type' => 'variant'
        ];

        $experienceId = DB::table('products')->insertGetId($variantExperienceInsertData);

        if($experienceId) {
            if (!empty($data['attributes'])) {
                $AttributesSaved = $this->saveAttributes($experienceId, $productTypeId, $data['attributes']);

                if ($AttributesSaved['status'] !== 'success') {
                    $AttributesSaved['message'] = 'Could not create the Experience Variant Attributes. Contact the system admin';
                    return $AttributesSaved;
                }
            }

            if (!empty($data['pricing'])) {
                $pricingSaved = $this->savePricing($experienceId, $data['pricing']);

                if ($pricingSaved['status'] !== 'success') {
                    $pricingSaved['message'] = 'Could not create the Experience Variant Pricing. Contact the system admin';
                    return $pricingSaved;
                }
            }

            if (!empty($data['mapping'])) {
                $mappingSaved = $this->mapVariantToComplexProduct($experienceId, $data['mapping']['complex_product_id'], $data['mapping']['variant_option_id']);

                if ($mappingSaved['status'] !== 'success') {
                    $mappingSaved['message'] = 'Could not create the Experience Variant Mapping. Contact the system admin';
                    return $mappingSaved;
                }
            }

            DB::commit();
            return ['status' => 'success'];

        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Create the Experience Variant based with the assigned params',
                'message' => 'Could not create the Experience Variant. Contact the system admin'
            ];
        }
    }

    public function update($experienceId, $data)
    {
        $query = '
            DELETE pab, pad, paf, pai, pam, pas, pat, pav, pcom, pp
            FROM products AS `p`
            LEFT JOIN product_attributes_boolean AS `pab` ON pab.product_id = p.`id`
            LEFT JOIN product_attributes_date AS `pad` ON pad.product_id = p.`id`
            LEFT JOIN product_attributes_float AS `paf` ON paf.product_id = p.`id`
            LEFT JOIN product_attributes_integer AS `pai` ON pai.product_id = p.`id`
            LEFT JOIN product_attributes_multiselect AS `pam` ON pam.product_id = p.`id`
            LEFT JOIN product_attributes_singleselect AS `pas` ON pas.product_id = p.`id`
            LEFT JOIN product_attributes_text AS `pat` ON pat.product_id = p.`id`
            LEFT JOIN product_attributes_varchar AS `pav` ON pav.product_id = p.`id`
            LEFT JOIN product_complex_options_map AS `pcom` ON pcom.`variant_product_id` = p.`id`
            LEFT JOIN product_pricing AS `pp` ON pp.`product_id` = p.`id`
            WHERE p.id = ?
        ';

        DB::delete($query, [$experienceId]);

        $variantExperienceUpdateData = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status'],
            'visible' => $data['visibility'],
            'type' => 'variant'
        ];

        DB::table('products')->where('id', $experienceId)->update($variantExperienceUpdateData);

        $productTypeId = DB::table('product_types')->where('slug', 'experiences')->pluck('id');

        if(!empty($data['attributes'])){
            $AttributesSaved = $this->saveAttributes($experienceId, $productTypeId, $data['attributes']);

            if($AttributesSaved['status'] !== 'success'){
                $AttributesSaved['message'] = 'Could not create the Simple Experience Attributes. Contact the system admin';
                return $AttributesSaved;
            }
        }

        if(!empty($data['pricing'])){
            $pricingSaved = $this->savePricing($experienceId, $data['pricing']);

            if($pricingSaved['status'] !== 'success'){
                $pricingSaved['message'] = 'Could not create the Simple Experience Pricing. Contact the system admin';
                return $pricingSaved;
            }
        }

        if (!empty($data['mapping'])) {
            $mappingSaved = $this->mapVariantToComplexProduct($experienceId, $data['mapping']['complex_product_id'], $data['mapping']['variant_option_id']);

            if ($mappingSaved['status'] !== 'success') {
                $mappingSaved['message'] = 'Could not create the Experience Variant Mapping. Contact the system admin';
                return $mappingSaved;
            }
        }

        DB::commit();

        return ['status' => 'success'];
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