<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Http\Requests\Admin\CreateSimpleExperienceRequest;
use WowTables\Http\Requests\Admin\UpdateSimpleExperienceRequest;
use WowTables\Http\Requests\Admin\DeleteSimpleExperienceRequest;
use WowTables\Http\Models\SimpleExperience;

/**
 * Class AdminExperiencesController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="/admin/experiences")
 */

class AdminExperiencesController extends Controller {


    protected $simpleExperience;

	/**
	 * The constructor Method
	 *
	 * @param Request $request
	 * @param ExperiencesRepository $repository
	 */
	function __construct(Request $request,ExperiencesRepository $repository, SimpleExperience $simpleExperience)
	{
        $this->middleware('admin.auth');
        $this->request = $request;
		$this->repository = $repository;
        $this->simpleExperience = $simpleExperience;
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

		return view('admin.experiences.index', ['experiences' => $experiences]);
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
	public function store(CreateSimpleExperienceRequest $request)
	{
        $input = $this->request->all();
        //dd($input);
        $simpleExperienceCreate = $this->simpleExperience->create($input);

        if($simpleExperienceCreate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }
            flash()->success('The Simple Experience has been successfully created.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $simpleExperienceCreate['action'],
                'message' => $simpleExperienceCreate['message']
            ], 400);
        }
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

        $experience = $this->repository->getByExperienceId($id);
        echo "<pre>"; print_r($experience);

        return view('admin.experiences.add_update',[
                        'experience'=>$experience,
                    ]);
	}

	/**
	 * Update the specified resource in storage.
	 *
     * @Put("/{id}", as="AdminExperienceUpdate")
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, UpdateSimpleExperienceRequest $updateSimpleExperienceRequest)
	{
        $input = $this->request->all();

        $simpleExperienceUpdate = $this->simpleExperience->update($id, $input);

        if($simpleExperienceUpdate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }
            flash()->success('The Simple Experieince has been successfully updated.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $simpleExperienceUpdate['action'],
                'message' => $simpleExperienceUpdate['message']
            ], 400);
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
     * @Delete("/", as="AdminExperienceDelete")
	 * @return Response
	 */
	public function destroy($id, DeleteSimpleExperienceRequest $deleteSimpleExperienceRequest)
	{
		$simpleExperienceDelete = $this->simpleExperience->delete($id);

        if($simpleExperienceDelete['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }
            flash()->success('The Simple Experieince has been successfully deleted.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $simpleExperienceDelete['action'],
                'message' => $simpleExperienceDelete['message']
            ], 400);
        }
	}

}
