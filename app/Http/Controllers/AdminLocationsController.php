<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Locations;
/**
 * Class AdminLocationsController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="admin/locations")
 */

class AdminLocationsController extends Controller {

    /**
     * The constructor Method
     *
     * @param Request $request
     */
    function __construct(Request $request)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
    }

    /**
     * Fetch the list of Locations
     *
     * @Get("/", as="AdminLocationsIndex")
     */
    public function index(Request $request, Locations $locations)
    {
        return response()->json($locations->fetch($request->all()));
    }

    /**
     * Output the Form to create a new Location
     *
     * @Get("/create", as="AdminLocationCreate")
     */
    public function create()
    {

    }

    /**
     * Add a new Location with or without a parent
     *
     * @Post("/", as="AdminLocationStore")
     */
    public function store(Request $request, Locations $locations)
    {

        $input = $request->all();

        return response()->json(
            $locations->add(
                $input['location_name'],
                $input['slug'],
                $input['location_type'],
                $input['location_parent_id']
            )
        );
    }

    /**
     * Output the update form to the screen
     *
     * @param int $id
     * @Get("/{id}", as="AdminLocationEdit")
     * @Where({"id": "\d+"})
     */
    public function edit($id)
    {
        return 'Word!!';
    }
    /**
     * Update an existing Location
     *
     * @param  int  $id
     * @Put("/{id}", as="AdminLocationUpdate")
     */
    public function update(Request $request, Locations $locations, $id)
    {
        $input = $request->all();

        return response()->json(
            $locations->change(
                $id,
                $input['location_name'],
                $input['slug'],
                $input['location_type'],
                $input['location_parent_id'],
                $input['visible']
            )
        );
    }

    /**
     * Delete a location if no dependencies found
     *
     * @param  int  $id
     * @Delete("/{id}", as="AdminLocationDelete")
     */
    public function delete($id, Locations $locations)
    {
        return response()->json($locations->remove($id));
    }

    /**
     * Generate the slug for the location based on the name
     *
     * @Get("/slug", as="AdminLocationSlug")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function slug(Request $request, Locations $locations)
    {
        return response()->json($locations->slugGenerate($request->input('location_name')));
    }

    /**
     * Create the options for the select locations input based on type
     *
     * @Get("/selectParents", as="AdminLocationsSelectParents")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function selectParents(Request $request, Locations $locations)
    {
        $parent_type = trim($request->input('location_type'));
        if(!empty($parent_type)){
            $options = $locations->getParents($parent_type);
            if(!empty($locations)){
                return view('admin.locations.selectLocation', ['locations' => $options]);
            }else{
                return response('');
            }
        }else{
            return response('');
        }

        //return response()->json();
    }
} 