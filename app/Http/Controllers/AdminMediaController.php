<?php namespace WowTables\Http\Controllers;

use Illuminate\Contracts\Config\Repository as Config;
use WowTables\Http\Models\Media;
use Illuminate\Http\Request;

/**
 * Class AdminMediaController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="/admin/media")
 */

class AdminMediaController extends Controller {

    protected $request;

    /**
     * The constructor Method
     *
     * @param Request $this->request
     */
    function __construct(Request $request, Config $config, Media $media)
    {
        $this->middleware('admin.auth');

        $this->request = $request;
        $this->config = $config;
        $this->media = $media;
    }

	/**
	 * Display a listing of the resource.
     *
     * @Get("/", as="AdminMedia")
	 * @return Response
	 */
	public function index()
    {

        $input = $this->request->all();

        $pagenum = isset($input['pagenum'])? $input['pagenum']: 1;
        $search_terms = empty($input['search'])? [] : explode(',', trim($input['search']));

        $allMedia = $this->media->getAll([
            'pagenum'       => $pagenum,
            'limit'         => 20,
            'height'        => 450,
            'width'         => 450,
            'search'        => $search_terms
        ]);

        if($allMedia['count'] > 0) {
            if($this->request->ajax()){
                if($this->request->has('type') && $this->request->get('type') == 'modal'){
                    return view(
                        'admin.media.ajax',
                        [
                            'mediaCount' => $allMedia['count'],
                            'images' => $allMedia['images'],
                            'pages' => $allMedia['pages'],
                            'pagenum' => $allMedia['pagenum'],
                            'search' => empty($input['search'])? '' : $input['search'],
                            's3_url' => $this->config->get('media.base_s3_url')
                        ]
                    );
                }
                else {
                    return view(
                        'admin.media.body',
                        [
                            'mediaCount' => $allMedia['count'],
                            'images' => $allMedia['images'],
                            'pages' => $allMedia['pages'],
                            'pagenum' => $allMedia['pagenum'],
                            'search' => empty($input['search'])? '' : $input['search'],
                            's3_url' => $this->config->get('media.base_s3_url')
                        ]
                    );
                }
            }
            return view(
                'admin.media.index',
                [
                    'mediaCount' => $allMedia['count'],
                    'images' => $allMedia['images'],
                    'pages' => $allMedia['pages'],
                    'pagenum' => $allMedia['pagenum'],
                    'search' => empty($input['search'])? '' : $input['search'],
                    's3_url' => $this->config->get('media.base_s3_url')
                ]
            );
        }else{
            return view('admin.media.index', ['mediaCount' => $allMedia['count']]);
        }
	}

    public function modal()
    {
        $input = $this->request->all();

        $pagenum = isset($input['pagenum'])? $input['pagenum']: 1;
        $search_terms = empty($input['search'])? [] : explode(',', trim($input['search']));

        $allMedia = $this->media->getAll([
            'pagenum'       => $pagenum,
            'limit'         => 20,
            'height'        => 450,
            'width'         => 450,
            'search'        => $search_terms
        ]);

        if($allMedia['count'] > 0) {
            return view(
                'admin.media.modal',
                [
                    'mediaCount' => $allMedia['count'],
                    'images' => $allMedia['images'],
                    'pages' => $allMedia['pages'],
                    'pagenum' => $allMedia['pagenum'],
                    'search' => empty($input['search'])? '' : $input['search'],
                    's3_url' => $this->config->get('media.base_s3_url')
                ]
            );
        }else{
            return view('admin.media.modal', ['mediaCount' => $allMedia['count']]);
        }
    }

    /**
	 * Store a newly created resource in storage.
     *
	 * @Post("/", as="AdminMediaStore")
	 * @return Response
	 */
	public function store()
	{
        $file = $this->request->file('media');

        $mediaStore = $this->media->save($file);

        if($mediaStore['status'] === 'success'){
            return response()->json($mediaStore, 200);
        }else{
            return response()->json($mediaStore, 500);
        }
	}

    /**
     * Fetch the HTML to edit the media asset
     *
     * @Get("/edit/{id}", as="AdminMediaEdit")
     * @param $id
     * @Where({"id": "\d+"})
     * @return Response
     */
    public function edit($id)
    {
        $mediaData = $this->media->fetchById($id);

        if($mediaData['status'] === 'success'){
            return view('admin.media.edit',
                [
                    'media' => $mediaData['media'],
                    's3_url' => $this->config->get('media.base_s3_url')
                ]);
        }else{
            return response()->json(['action' => $mediaData['action'], 'message' => $mediaData['message']], 400);
        }
    }

	/**
	 * Update the specified resource in storage.
	 *
     * @Put("/{id}", as="AdminMediaUpdate")
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $input = $this->request->all();

		$mediaUpdate = $this->media->updateAttributes($id, [
            'name' => $input['name'],
            'title' => !empty($input['title'])? $input['title']:null,
            'alt' => !empty($input['alt'])? $input['alt']:null,
            'tags' => !empty($input['tags'])? $input['tags']:[]
        ]);

        if($mediaUpdate['status'] === 'success'){
            return response()->json(['status' => 'success']);
        }else{
            return response()->json(['message' => $mediaUpdate['message'], 'action' => $mediaUpdate['action']], 400);
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
     * @Delete("/{id}", as="AdminMediaDelete")
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

    /**
     * Return the list of media names or tags available based on the search input
     *
     * @Get("/search", as="AdminMediaSearch")
     * @return JsonResponse
     */
    public function search(){

        $search_term = $this->request->get('q');

        return response()->json(['items' => $this->media->autocomplete($search_term)]);
    }

    /**
     * Get all the media to be served using ajax
     *
     * @Get("/fetch", as="AdminMediaFetch")
     */
    public function fetch()
    {
        $images = $this->request->get('media');
        $media_type = $this->request->get('media_type');

        $allImages = [];

        foreach ( $images as $image )
        {
            $allImages [] = $this->media->fetchById($image)['media'];
        }

        return view('admin.media.fetch',
            [
                'media' => $allImages ,
                'media_type' => $media_type,
                's3_url' => $this->config->get('media.base_s3_url')
            ]);
    }

}
