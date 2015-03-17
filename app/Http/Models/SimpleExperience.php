<?php namespace WowTables\Http\Models;

use DB;
use WowTables\Http\Models\ExperienceAddons;

class SimpleExperience extends Experience {

    public function create($data)
    {
        DB::beginTransaction();

        $productTypeId = DB::table('product_types')->where('slug', 'experiences')->pluck('id');

        $simpleExperienceInsertData = [
            'product_type_id' => $productTypeId,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status'],
            'type' => 'simple'
        ];

        if($data['status'] === 'Publish' && isset($data['publish_date'])){
            $simpleExperienceInsertData['publish_time'] = $data['publish_date'].' '.$data['publish_time'];
        }

        $experienceId = DB::table('products')->insertGetId($simpleExperienceInsertData);

        if($experienceId){
            if(!empty($data['attributes'])){
                $AttributesSaved = $this->saveAttributes($experienceId, $productTypeId, $data['attributes']);

                if($AttributesSaved['status'] !== 'success'){
                    $AttributesSaved['message'] = 'Could not create the Simple Experience Attributes. Contact the system admin';
                    return $AttributesSaved;
                }
            }

            if(!empty($data['media'])){
                $mediaSaved = $this->saveMedia($experienceId, $data['media']);

                if($mediaSaved['status'] !== 'success'){
                    $mediaSaved['message'] = 'Could not create the Simple Experience Media. Contact the system admin';
                    return $mediaSaved;
                }
            }

            if(!empty($data['pricing'])){
                $pricingSaved = $this->savePricing($experienceId, $data['pricing']);

                if($pricingSaved['status'] !== 'success'){
                    $pricingSaved['message'] = 'Could not create the Simple Experience Pricing. Contact the system admin';
                    return $pricingSaved;
                }
            }

            if(!empty($data['addons'])){
                $experinceAddons = new ExperienceAddons();

                $addonsSaved = $experinceAddons->create($experienceId, $productTypeId, $data['addons']);

                if($addonsSaved['status'] !== 'success'){
                    $addonsSaved['message'] = 'Could not map the Simple Experience Addons. Contact the system admin';
                    return $addonsSaved;
                }
            }

            if(!empty($data['tags'])){
                $tagMapping = $this->mapTags($experienceId, $data['tags']);

                if($tagMapping['status'] !== 'success'){
                    $tagMapping['message'] = 'Could not map the Simple Experience Tags. Contact the system admin';
                    return $tagMapping;
                }
            }

            if(!empty($data['curators'])){
                $curatorMapping = $this->mapCurators($experienceId, $data['tags']);

                if($curatorMapping['status'] !== 'success'){
                    $curatorMapping['message'] = 'Could not map the Simple Experience curators. Contact the system admin';
                    return $curatorMapping;
                }
            }

            if(!empty($data['curators'])){
                $flagMapping = $this->mapFlags($experienceId, $data['flags']);

                if($flagMapping['status'] !== 'success'){
                    $flagMapping['message'] = 'Could not map the Simple Experience Flags. Contact the system admin';
                    return $flagMapping;
                }
            }

            DB::commit();
            return ['status' => 'success'];
        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Create the Simple Experience based with the assigned params',
                'message' => 'Could not create the Simple Experience. Contact the system admin'
            ];
        }
    }

    public function update($experienceId, $data)
    {
        $query = '
            DELETE pab, pad, paf, pai, pam, pas, pat, pav,
                    pcm, pmm, ptm, padd, pp, pfm
            FROM products AS `p`
            LEFT JOIN product_attributes_boolean AS `pab` ON pab.product_id = p.`id`
            LEFT JOIN product_attributes_date AS `pad` ON pad.product_id = p.`id`
            LEFT JOIN product_attributes_float AS `paf` ON paf.product_id = p.`id`
            LEFT JOIN product_attributes_integer AS `pai` ON pai.product_id = p.`id`
            LEFT JOIN product_attributes_multiselect AS `pam` ON pam.product_id = p.`id`
            LEFT JOIN product_attributes_singleselect AS `pas` ON pas.product_id = p.`id`
            LEFT JOIN product_attributes_text AS `pat` ON pat.product_id = p.`id`
            LEFT JOIN product_attributes_varchar AS `pav` ON pav.product_id = p.`id`
            LEFT JOIN product_curator_map AS `pcm` ON pcm.product_id = p.`id`
            LEFT JOIN product_media_map AS `pmm` ON pmm.product_id = p.`id`
            LEFT JOIN product_tag_map AS `ptm` ON ptm.product_id = p.`id`
            LEFT JOIN products AS `padd` ON padd.`product_parent_id` = p.`id`
            LEFT JOIN product_pricing AS `pp` ON pp.`product_id` = p.`id`
            LEFT JOIN product_flag_map AS `pfm` ON pfm.`product_id` = p.`id`
            WHERE p.id = ?
        ';

        DB::delete($query, [$experienceId]);

        $simpleExperienceUpdateData = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status'],
            'type' => 'simple'
        ];

        if($data['status'] === 'Publish' && isset($data['publish_date'])){
            $simpleExperienceInsertData['publish_time'] = $data['publish_date'].' '.$data['publish_time'];
        }

        DB::table('products')->where('id', $experienceId)->update($simpleExperienceUpdateData);

        $productTypeId = DB::table('product_types')->where('slug', 'experiences')->pluck('id');

        if(!empty($data['attributes'])){
            $AttributesSaved = $this->saveAttributes($experienceId, $productTypeId, $data['attributes']);

            if($AttributesSaved['status'] !== 'success'){
                $AttributesSaved['message'] = 'Could not create the Simple Experience Attributes. Contact the system admin';
                return $AttributesSaved;
            }
        }

        if(!empty($data['media'])){
            $mediaSaved = $this->saveMedia($experienceId, $data['media']);

            if($mediaSaved['status'] !== 'success'){
                $mediaSaved['message'] = 'Could not create the Simple Experience Media. Contact the system admin';
                return $mediaSaved;
            }
        }

        if(!empty($data['pricing'])){
            $pricingSaved = $this->savePricing($experienceId, $data['pricing']);

            if($pricingSaved['status'] !== 'success'){
                $pricingSaved['message'] = 'Could not create the Simple Experience Pricing. Contact the system admin';
                return $pricingSaved;
            }
        }

        if(!empty($data['addons'])){
            $experinceAddons = new ExperienceAddons();

            $addonsSaved = $experinceAddons->create($experienceId, $productTypeId, $data['addons']);

            if($addonsSaved['status'] !== 'success'){
                $addonsSaved['message'] = 'Could not map the Simple Experience Addons. Contact the system admin';
                return $addonsSaved;
            }
        }

        if(!empty($data['tags'])){
            $tagMapping = $this->mapTags($experienceId, $data['tags']);

            if($tagMapping['status'] !== 'success'){
                $tagMapping['message'] = 'Could not map the Simple Experience Tags. Contact the system admin';
                return $tagMapping;
            }
        }

        if(!empty($data['curators'])){
            $curatorMapping = $this->mapCurators($experienceId, $data['tags']);

            if($curatorMapping['status'] !== 'success'){
                $curatorMapping['message'] = 'Could not map the Simple Experience curators. Contact the system admin';
                return $curatorMapping;
            }
        }

        if(!empty($data['curators'])){
            $flagMapping = $this->mapFlags($experienceId, $data['flags']);

            if($flagMapping['status'] !== 'success'){
                $flagMapping['message'] = 'Could not map the Simple Experience Flags. Contact the system admin';
                return $flagMapping;
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
} 