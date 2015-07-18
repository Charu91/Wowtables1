<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Models\Product;
use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Products;
use Illuminate\Http\Request;
use WowTables\Http\Models\Experiences;
use Validator;
use Config;

class ExperiencesController extends Controller {

    /**
     * The products Object
     *
     * @var object
     */
    protected $products;

    /**
     * The Http Request Object
     *
     * @var object
     */
    protected $request;

    /**
     * The Single Product Object
     *
     * @var object
     */
    protected $product;
	
	/**
	 * The Single Experiences object
	 * 
	 * @var		object
	 * @access	protected
	 */
	protected $experience;
	

    /**
     * Default Constructor
	 * 
	 * @param Request $request
     */
    public function __construct(Request $request, Products $products, Product $product, Experiences $experience)
    {
        //$this->middleware('mobile.app.access');

        $this->products = $products;
        $this->product = $product;
        $this->request = $request;
		$this->experience = $experience;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $input = $this->request->all();

        $experiences = $this->products->fetchAll('mobile', ['events, experiences'], $input);

        return response()->json($experiences['data'], $experiences['code']);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {  

        //data to be validated
        $data=array('id' => $id);

        //Validation user's profile data
        if(is_numeric($id)) {   
            $validator = Validator::make($data,Experiences::$arrRules);    
        }
        else {
            $validator = Validator::make($data,Experiences::$arrRulesSlug);   
        }
        

        if($validator->fails()){
            $arrResponse['status'] = Config::get('constants.API_SUCCESS');
            $arrResponse['no_result_msg'] = 'No matching values found.';
            $arrResponse['data'] = array();
            $arrResponse['total_count'] = 0;
        }
        else{
            $arrResponse=$this->experience->find($id);
        }

        return response()->json( $arrResponse,200);

	}

}
//End of Class ExperiencesController
//End of File ExperiencesController.php
