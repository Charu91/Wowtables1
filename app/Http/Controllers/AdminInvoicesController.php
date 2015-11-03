<?php namespace WowTables\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\UserMeta;
use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Reservations\ReservationDetails;
use Mail;
use WowTables\Http\Models\Eloquent\User;
use Illuminate\Support\Facades\DB;
use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;

class AdminInvoicesController extends Controller {



	protected $request;
	/**
	 * The constructor Method
	 *
	 *
	 */
	function __construct(Request $request,ReservationDetails $reservationDetails)
	{
		$this->middleware('admin.auth');
		$this->request = $request;
		$this->reservationDetails = $reservationDetails;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('admin.invoices.index');
	}




	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	public function createInvoices(){
		return view('admin.invoices.create');
	}

	public function generateInvoices(){
		$fromDate = Carbon::createFromFormat('m/d/Y',$this->request->get('from_date'))->startOfDay()->toDateTimeString();
		$toDate = Carbon::createFromFormat('m/d/Y',$this->request->get('to_date'))->endOfDay()->toDateTimeString();


		//$closedReservation = DB::select(DB::raw('select * from reservation_status_log having new_reservation_status_id in (8) and created_at in (SELECT MAX(created_at) FROM reservation_status_log group by reservation_id)'));
		$closedReservation = DB::table('reservation_details')
			->leftJoin(DB::raw('reservation_attributes_integer as rai1'),'rai1.reservation_id','=','reservation_details.id')
			->leftJoin(DB::raw('reservation_attributes as ra1'), 'ra1.id','=','rai1.reservation_attribute_id')
			->where('ra1.alias','reservation_status_id')
			->where('rai1.attribute_value',8)
			->where('reservation_details.created_at','>=',$fromDate)
			->where('reservation_details.created_at','<=',$toDate)
			->select('reservation_details.*')->get();

		//print_r($closedReservation);die;
		foreach($closedReservation as $reserv){
			$reservationAttrs = $this->reservationDetails->getByReservationId($reserv->id);
		}
		die;
		/*$reservationIds = DB::select(DB::raw('select reservation_id'.
											 ' from reservation_status_log'.
											 ' left join reservation_statuses on reservation_status_log.new_reservation_status_id = reservation_statuses.id'.
			                                 ' where reservation_statuses.status in (\'Closed\')'.
			                                 ' and reservation_status_log.new_reservation_status_id in (select max(new_reservation_status_id)'.
											 ' from reservation_status_log group by reservation_id)'));
		$reservationIdArr = array();
		foreach($reservationIds as $reservId){
			$reservationIdArr[] = $reservId->reservation_id;
		}*/
		//print_r($reservationIdArr);die;
		$reservationDetails = ReservationDetails::closed()->get();
		print_r($reservationDetails);die;
		//echo $toDate;die;
	}
}
