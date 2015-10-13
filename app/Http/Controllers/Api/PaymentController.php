<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Payment;
use Illuminate\Http\Request;
use Validator;
use Config;

class PaymentController extends Controller {

	/**
	 * Instance of Request class.
	 * 
	 * @var		Request
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $request;

	public function __construct(Request $request) {
		$this->request = $request;		
	}
	//-----------------------------------------------------------------

	/**
	 * Handle the request to generate Mobile PayU hash code.
	 * 
	 * @access	public
	 * @param	array	 
	 * @return	json
	 * @since	1.0.0
	 */
	 public function getMobileHash() {
	 	
	 	//reading data input by the user
	 	$data = $this->request->all();

	 	//validating user data
		$validator = Validator::make($data,Payment::$arrRules);
		
		if($validator->fails()) {
			$message = $validator->messages();
			$errorMessage = "";
			foreach($data as $key => $value) {
				if($message->has($key)) {
					$errorMessage .= $message->first($key).'\n ';
				}
			}
			
			$arrResponse['status'] = Config::get('constants.API_ERROR');
			$arrResponse['error'] = $errorMessage;				
		}
		else {
			
			$code = Payment::getPayUHash($data);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			$arrResponse['data'] = array("hash" => $code);			
		}
		
		return response()->json($arrResponse,200);
	 }

}
//end of class PaymentController
//end of file PaymentController.php