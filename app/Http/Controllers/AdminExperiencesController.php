<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Http\Requests\CreateExperienceRequest;

/**
 * Class AdminExperiencesController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="/admin/experiences")
 */

class AdminExperiencesController extends Controller {

	/**
	 * The constructor Method
	 *
	 * @param Request $request
	 * @param ExperiencesRepository $repository
	 */
	function __construct(Request $request,ExperiencesRepository $repository)
	{
        $this->middleware('admin.auth');
        $this->request = $request;
		$this->repository = $repository;
	}

	/**
	 * Display a listing of the resource.
	 *
     * @Get("/", as="AdminExperiences")
	 * @return Response
	 */
	public function index()
	{
		$experiences = $this->repository->getAll();

		return view('admin.experiences.index',['experiences'=>$experiences]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
     * @Get("/create", as="AdminExperienceCreate")
	 * @return Response
	 */
	public function create()
	{
        return view('admin.experiences.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @Post("/", as="AdminExperienceStore")
	 * @param CreateExperienceRequest $request
	 * @return Response
	 */
	public function store(CreateExperienceRequest $request)
	{
		$this->dispatchFrom('WowTables\Commands\Admin\CreateExperienceCommand', $request, ['type'=>'simple']);

		flash()->success('Restaurant Location has been successfully created!!!');

		return redirect()->route('AdminExperiences');
	}

	/**
	 * Display the specified resource.
	 *
     * @Get("/{id}", as="AdminExperienceShow")
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return view('admin.experiences.single');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
     * @Get("/edit/{id}", as="AdminExperinceEdit")
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return view('admin.experiences.add_update');
	}

	/**
	 * Update the specified resource in storage.
	 *
     * @Put("/{id}", as="AdminExperienceUpdate")
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
     * @Delete("/", as="AdminExperienceDelete")
	 * @return Response
	 */
	public function destroy()
	{
		//
	}

}
