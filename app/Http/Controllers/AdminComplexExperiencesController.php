<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Requests\Admin\CreateComplexExperienceRequest;
use WowTables\Http\Requests\Admin\UpdateComplexExperienceRequest;
use WowTables\Http\Requests\Admin\DeleteComplexExperienceRequest;
use WowTables\Http\Models\ComplexExperience;

/**
 * Class AdminExperiencesController
 *
 */

class AdminComplexExperiencesController extends Controller {

    protected $complexExperience;

    /**
     * The constructor Method
     *
     * @param Request $request
     */
    function __construct(Request $request, ComplexExperience $complexExperience)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
        $this->complexExperience = $complexExperience;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $experiences = [];

        return view('admin.experiences.complex.index',['experiences'=> $experiences]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('admin.experiences.complex.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(CreateComplexExperienceRequest $createComplexExperienceRequest)
    {
        $input = $this->request->all();

        $complexExperienceCreate = $this->complexExperience->create($input);

        if($complexExperienceCreate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }

            flash()->success('The Complex Experieince has been successfully created.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $complexExperienceCreate['action'],
                'message' => $complexExperienceCreate['message']
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin.experiences.complex.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.experiences.complex.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, UpdateComplexExperienceRequest $updateComplexExperienceRequest)
    {
        $input = $this->request->all();

        $complexExperienceUpdate = $this->complexExperience->update($id, $input);

        if($complexExperienceUpdate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }
            flash()->success('The Complex Experieince has been successfully updated.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $complexExperienceUpdate['action'],
                'message' => $complexExperienceUpdate['message']
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id, DeleteComplexExperienceRequest $deleteComplexExperienceRequest)
    {
        $complexExperienceDelete = $this->complexExperience->delete($id);

        if($complexExperienceDelete['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }
            flash()->success('The Complex Experieince has been successfully deleted.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $complexExperienceDelete['action'],
                'message' => $complexExperienceDelete['message']
            ], 400);
        }
    }

}
