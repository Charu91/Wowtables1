<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Requests\Admin\CreateExperienceVariantRequest;
use WowTables\Http\Requests\Admin\UpdateExperienceVariantRequest;
use WowTables\Http\Requests\Admin\DeleteExperienceVariantRequest;
use WowTables\Http\Models\VariantExperience;

/**
 * Class AdminExperiencesController
 */

class AdminExperienceVariantsController extends Controller {

    protected $variantExperience;

    /**
     * The constructor Method
     *
     * @param Request $request
     */
    function __construct(Request $request, VariantExperience $variantExperience)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
        $this->variantExperience = $variantExperience;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $variants = [];

        return view('admin.experiences.variants.index', ['variants' => $variants] );
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('admin.experiences.variants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(CreateExperienceVariantRequest $createExperienceVariantRequest)
    {
        $input = $this->request->all();

        $experienceVariantCreate = $this->variantExperience->create($input);

        if($experienceVariantCreate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }

            flash()->success('The Experieince Variant has been successfully created.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $experienceVariantCreate['action'],
                'message' => $experienceVariantCreate['message']
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show($id)
    {
        return view('admin.experiences.variants.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.experiences.variants.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update($id, UpdateExperienceVariantRequest $updateExperienceVariantRequest)
    {
        $input = $this->request->all();

        $experienceVariantUpdate = $this->variantExperience->update($id, $input);

        if($experienceVariantUpdate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }
            flash()->success('The Experieince Variant has been successfully updated.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $experienceVariantUpdate['action'],
                'message' => $experienceVariantUpdate['message']
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id, DeleteExperienceVariantRequest $deleteExperienceVariantRequest)
    {
        $experienceVariantDelete = $this->variantExperience->delete($id);

        if($experienceVariantDelete['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }
            flash()->success('The Experieince Variant has been successfully deleted.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $experienceVariantDelete['action'],
                'message' => $experienceVariantDelete['message']
            ], 400);
        }
    }

}
