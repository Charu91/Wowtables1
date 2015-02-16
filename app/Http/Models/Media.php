<?php namespace WowTables\Http\Models;

use DB;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Cloud;

class Media {

    protected $config;

    protected $filesystem;

    protected $cloud;

    /**
     * The contructor method
     *
     * @return void
     */
    public function __construct(Config $config, Filesystem $filesystem, Cloud $cloud){
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->cloud = $cloud;
    }


    public function getAll($filters){

        $totalMediaCount = DB::table('media')->count();

        if($totalMediaCount){

            $params = [];

            $countQuery = 'SELECT count(`media_id`) as `total_count` FROM ';
            $mediaQuery = 'SELECT `media_id`, `file`, `name`, `alt`, `title`, `resized_file`, `created_date` FROM ';

            $query = '
                (SELECT
                  m.`id` as `media_id`,
                  m.`file`,
                  m.`name`,
                  m.`alt`,
                  m.`title`,
                  mr.`file` as `resized_file`,
                  DATE_FORMAT(m.`created_at`, "%m/%d/%Y") as created_date,
                  m.`created_at`
                FROM media m
                LEFT JOIN media_resized mr ON m.`id` = mr.`media_id` AND mr.`height` = ? AND mr.`width` = ?
            ';

            $params[] = $filters['height'];
            $params[] = $filters['width'];

            if(!empty($filters['search'])){
                $query .= 'WHERE ';
                $whereLike = [];

                foreach($filters['search'] as $term){
                    $whereLike[] = ' m.`name` LIKE ? ';
                    $params[] = '%'.$term.'%';
                }

                $query .= implode(' OR ', $whereLike);
            }

            if(!empty($filters['search'])){
                $whereIn = [];
                $query .= '
                    UNION ALL
                    SELECT
                      m.`id` as `media_id`,
                      m.`file`,
                      m.`name`,
                      m.`alt`,
                      m.`title`,
                      mr.`file` as `resized_file`,
                      DATE_FORMAT(m.`created_at`, "%m/%d/%Y") as created_date,
                      m.`created_at`
                    FROM media m
                    LEFT JOIN media_resized mr ON m.`id` = mr.`media_id` AND mr.`height` = ? AND mr.`width` = ?
                    LEFT JOIN media_tags_map mtm ON m.`id` = mtm.media_id
                    LEFT JOIN media_tags mt ON mtm.media_tag_id = mt.id
                    WHERE mt.name IN (
                ';

                $params[] = $filters['height'];
                $params[] = $filters['width'];

                foreach($filters['search'] as $term){
                    $whereIn[] = '?';
                    $params[] = $term;
                }

                $query .= implode(',',$whereIn);

                $query .= '
                    )
                    GROUP BY m.`id`
                    HAVING count(m.`id`) = ?
                ';
                $params[] = count($filters['search']);

            }

            $query .= ') as T';

            $imgCount = DB::select($countQuery.$query, $params);

            if($imgCount[0]->total_count <= $filters['limit']){
                $totalPages = 1;
                $filters['pagenum'] = 1;
                $offset = 0;
            }else{
                $totalPages = ceil($imgCount[0]->total_count/$filters['limit']);
                $offset = ($filters['pagenum'] - 1) * $filters['limit'];
            }

            $orderLimitQuery = '
                ORDER BY T.`created_at` DESC
                LIMIT ?,?
            ';

            $params[] = $offset;
            $params[] = $filters['limit'];

            $images = DB::select($mediaQuery.$query.$orderLimitQuery, $params);

            foreach($images as $image){
                if(!$image->resized_file){
                    $image->resized_file = $this->resizeAndSavetoDatabase([
                       'media_id'   => $image->media_id,
                       'media_file' => $image->file,
                       'width'      => $filters['width'],
                       'height'     => $filters['height']
                    ]);

                    if(!$image->resized_file){
                        unset($image);
                    }
                }
            }

            return [
                'count'     => $totalMediaCount,
                'images'    => $images,
                'pages'     => $totalPages,
                'pagenum'   => $filters['pagenum']
            ];
        }else{
            return ['count' => 0];
        }
    }

    public function save(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        $file_extension = $file->getClientOriginalExtension();
        $file_mime_type = $file->getMimeType();

        $original_filename = pathinfo($file->getClientOriginalName())['filename'];

        $uploads_dir = $this->config->get('media.base_path');

        $new_filename = uniqid('media_');
        $media_upload = $this->cloud->put($uploads_dir.$new_filename.'.'.$file_extension, Image::make($file)->encode());

        if($media_upload){
            $thumbsize = $this->config->get('media.sizes.thumbnail');
            $thumb_filename = $new_filename.'_'.$thumbsize['width'].'x'.$thumbsize['height'];

            $thumb_upload = $this->cloud->put($uploads_dir.$thumb_filename.'.'.$file_extension, Image::make($file)->fit($thumbsize['width'], $thumbsize['height'])->encode());

            if($thumb_upload){
                DB::beginTransaction();

                $media_id = DB::table('media')->insertGetId([
                    'file'      => $new_filename.'.'.$file_extension,
                    'mime_type' => $file_mime_type,
                    'name'      => $original_filename
                ]);

                if($media_id){
                    $resizedMediaInsert = DB::table('media_resized')->insert([
                        'file'      => $thumb_filename.'.'.$file_extension,
                        'width'     => $thumbsize['width'],
                        'height'    => $thumbsize['height'],
                        'media_id'  => $media_id
                    ]);

                    if($resizedMediaInsert){
                        DB::commit();
                        return ['status' => 'success'];
                    }else{
                        DB::rollBack();
                        return [
                            'status' => 'failure',
                            'action' => 'Error adding the thumbnail media file to the DB',
                            'message' => 'There was an error adding the resized media to DB. Contact the website admin'
                        ];
                    }
                }else{
                    DB::rollBack();
                    return [
                        'status' => 'failure',
                        'action' => 'Error adding the new media file to the DB',
                        'message' => 'There was an error adding the media to DB. Contact the website admin'
                    ];
                }
            }else{
                return [
                    'status' => 'failure',
                    'action' => 'Save the resized thumb file to s3',
                    'message' => 'There was an issue uploading the thumbnail file. Contact the website admin'
                ];
            }
        }else{
            return [
                'status' => 'failure',
                'action' => 'Save the original file to s3',
                'message' => 'There was an issue uploading the media file. Contact the website admin'
            ];
        }


    }

    public function getImagebySize($media_id, $height, $width){
        $query = '
            SELECT m.`file`, m.`name`, m.`alt`, m.`title`, mr.`file` as resized_file
            FROM media m
            LEFT JOIN media_resized mr ON m.`id` = mr.`media_id` AND mr.`height` = ? AND mr.`width` = ?
            WHERE m.`id` = ?
            LIMIT 1
        ';

        $resized_image = DB::select($query, [$height, $width, $media_id]);

        if($resized_image[0]->resized_file){
            return [
                'success'       => true,
                'original_path' => $resized_image[0]->file,
                'resized_path'  => $resized_image[0]->resized_file,
                'name'          => $resized_image[0]->name,
                'alt'           => $resized_image[0]->alt,
                'title'         => $resized_image[0]->title
            ];
        }else{
            try {
                $img = Image::make(base_path().'/'.$resized_image[0]->file)->fit($width, $height);

                $imgpathinfo = pathinfo(base_path().'/'.$resized_image[0]->file);

                $img->save(base_path().'/uploads/'.$imgpathinfo['filename'].'_'.$width.'x'.$height.'.'.$imgpathinfo['extension']);

                $resizedImagePath = '/uploads/'.$imgpathinfo['filename'].'_'.$width.'x'.$height.'.'.$imgpathinfo['extension'];

                $resizedMediaInsert = DB::table('media_resized')->insert([
                    'file'      => $resizedImagePath,
                    'width'     => $width,
                    'height'    => $height,
                    'media_id'  => $media_id
                ]);

                if($resizedMediaInsert){
                    return [
                        'success'       => true,
                        'original_path' => $resized_image[0]->file,
                        'resized_path'  => $resizedImagePath,
                        'name'          => $resized_image[0]->name,
                        'alt'           => $resized_image[0]->alt,
                        'title'         => $resized_image[0]->title
                    ];
                }else{
                    return [
                        'success'   => false,
                        'message'   => 'Could not insert resized image to DB',
                        'action'    => 'Insert the resized DB to the database',
                    ];
                }


            } catch (NotReadableException $e){
                return [
                    'success'   => false,
                    'message'   => 'Could not resize the image to thumbnail',
                    'action'    => 'Resizing the image using the resizer library',
                ];
            }
        }
    }

    public function fetchById($id){
        $query = '
            SELECT
              m.`id` as `media_id`,
              m.`name`,
              m.`alt`,
              m.`title`,
              mr.`file` as `resized_file`,
              mt.`name` as `tag_name`,
              mt.`id` as `tag_id`
            FROM media m
            LEFT JOIN media_resized mr ON m.`id` = mr.`media_id` AND mr.`height` = ? AND mr.`width` = ?
            LEFT JOIN media_tags_map mtm ON m.`id` = mtm.media_id
            LEFT JOIN media_tags mt ON mtm.media_tag_id = mt.id
            WHERE m.`id` = ?
        ';

        $results = DB::select($query, [450, 450, $id]);

        if($results){
            $media = [
                'id'            => $results[0]->media_id,
                'name'          => $results[0]->name,
                'title'         => $results[0]->title,
                'alt'           => $results[0]->alt,
                'resized_file'  => $results[0]->resized_file,
                'tags'          => [],
            ];

            if(count($results) > 1){
                foreach($results as $result){
                    $media['tags'][$result->tag_id] = $result->tag_name;
                }
            }else{
                if($results[0]->tag_id){
                    $media['tags'][$results[0]->tag_id] = $results[0]->tag_name;
                }
            }

            return ['status' => 'success', 'media' => $media];
        }else{
            return [
                'status' => 'failure',
                'action' => 'Selecting the media element based on the id',
                'message' => 'Could not find the image you were trying to edit'
            ];
        }
    }

    public function updateAttributes($id, array $data)
    {
        $query = '
            UPDATE media SET
            name = ?, title = ?, alt = ?
            WHERE id = ?
        ';

        DB::beginTransaction();

        DB::update($query, [$data['name'], $data['title'], $data['alt'], $id]);

        if(!empty($data['tags'])){
            $query = 'INSERT IGNORE INTO media_tags (`name`) VALUES ';
            $insertValues = [];

            foreach($data['tags'] as $tag){
                $insertValues[] = '(?)';
            }

            $query .= implode(', ', $insertValues);

            DB::insert($query, $data['tags']);

            $mediaTagIds = DB::table('media_tags')->whereIn('name', $data['tags'])->lists('id');

            if($mediaTagIds){
                //Delete exiting Tags FROM the DB first
                DB::table('media_tags_map')->where('media_id', '=', $id)->delete();

                $mediaTagMap = [];
                foreach($mediaTagIds as $tagId){
                    $mediaTagMap[] = ['media_id' => $id, 'media_tag_id' => $tagId];
                }

                $mediaTagMapInsert = DB::table('media_tags_map')->insert($mediaTagMap);

                if($mediaTagMapInsert){
                    DB::commit();
                    return ['status' => 'success'];
                }else{
                    DB::rollBack();
                    return [
                        'status'    => 'failure',
                        'action'    => 'Insert the new media tag map',
                        'message'   => 'Unable to insert the new tags to the media file'
                    ];
                }
            }else{
                DB::rollBack();
                return [
                    'status'    => 'failure',
                    'action'    => 'Fetch Inserted tag Ids from the DB',
                    'message'   => 'Cound not fetch the media tags inserted to the DB'
                ];
            }
        }else{
            DB::table('media_tags_map')->where('media_id', '=', $id)->delete();

            DB::commit();
            return ['status' => 'success'];
        }
    }

    public function delete(array $mediaIds)
    {
        $media_mappings = DB::table('product_media_map')->whereIn('media_id', $mediaIds)->union(
            DB::table('vendor_media_map')->whereIn('media_id', $mediaIds)->limit(5)
        )->count();

        if(!$media_mappings){
            return ['status' => 'success'];
        }else{
            return [
                'status' => 'failure',
                'action' => 'Check if the media is mapped to any product or vendor',
                'message' => 'The media cannot be currently deleted as it is mapped to an existing product or vendor'
            ];
        }
    }

    public function autocomplete($term)
    {
        $query = '
            SELECT term, type
            FROM
            (SELECT
                m.`name` AS `term`,
                "name" AS `type`,
                m.created_at AS `created_at`
            FROM media m WHERE m.name LIKE ?
            UNION
            SELECT
                mt.`name` as `term`,
                "tag" AS `type`,
                mt.`created_at`
            FROM media_tags mt
            INNER JOIN media_tags_map AS `mtm` ON mtm.`media_tag_id` = mt.`id`
            WHERE mt.`name` LIKE ?) AS T
            ORDER BY T.`created_at`
            LIMIT 10
        ';

        $terms = DB::select($query, ['%'.$term.'%', '%'.$term.'%']);

        if($terms){
            $autofillOptions = [];
            foreach($term as $term){
                $autofillOptions[] = ['id' => $term->term, 'text' => $term->term];
            }
        }else{
            return [];
        }
    }
} 