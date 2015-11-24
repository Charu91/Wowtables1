<?php namespace WowTables\Http\Controllers;

use Carbon\Carbon;
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
use Barryvdh\Snappy\PdfWrapper;
use Knp\Snappy\Pdf;
class AdminInvoicesController extends Controller {



	protected $request;
	/**
	 * The constructor Method
	 *
	 *
	 */
	function __construct(Request $request,ReservationDetails $reservationDetails,ExperiencesRepository $repository,RestaurantLocationsRepository $alacarterepository)
	{
		$this->middleware('admin.auth');
		$this->request = $request;
		$this->reservationDetails = $reservationDetails;
		$this->repository = $repository;
		$this->alacarterepository = $alacarterepository;
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

		$closedReservation = DB::table('reservation_details')
			->leftJoin(DB::raw('reservation_attributes_integer as rai1'),'rai1.reservation_id','=','reservation_details.id')
			->leftJoin(DB::raw('reservation_attributes as ra1'), 'ra1.id','=','rai1.reservation_attribute_id')
			->leftJoin(DB::raw('vendor_locations as vc'),'vc.id','=','reservation_details.vendor_location_id')
			->leftJoin(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','vc.id')
			->leftJoin(DB::raw('vendors as v'),'v.id','=','vc.vendor_id')
			->leftJoin(DB::raw('locations as l'),'l.id','=','vc.location_id')
			->leftJoin(DB::raw('products as p'),'p.id','=','reservation_details.product_id')
			->where('ra1.alias','reservation_status_id')
			->where('rai1.attribute_value',8)
			->where('reservation_details.created_at','>=',$fromDate)
			->where('reservation_details.created_at','<=',$toDate)
			->select(DB::raw('reservation_details.id,reservation_details.reservation_type,reservation_details.user_id,reservation_details.guest_name,reservation_details.reservation_date,v.id as vendor_id,v.name as vendor_name,l.id as location_id,l.name as location_name ,p.name as product_name,vla.address'))->paginate(15);

		//print_r($closedReservation);die;
		$locationArr = array();
		$vendorReservId = array();
		$vendorLocationReservId = array();

		foreach($closedReservation as $cr){

			if(array_key_exists($cr->location_name.'-'.$cr->location_id,$locationArr)){
				$locationArr[$cr->vendor_name.'-'.$cr->vendor_id][$cr->location_name.'-'.$cr->location_id][] = array('reservation_id'=>$cr->id,
					'location_id'=>$cr->location_id,
					'product_name'=>$cr->product_name,
					'cust_name'=>$cr->guest_name,
					'date'=>$cr->reservation_date,
					'vendor_name'=>$cr->vendor_name,
					'vendor_location'=>$cr->location_name,
					'address'=>$cr->address,
					'vendor_id'=>$cr->vendor_id);
			} else {
				$locationArr[$cr->vendor_name.'-'.$cr->vendor_id][$cr->location_name.'-'.$cr->location_id][] = array('reservation_id'=>$cr->id,
					'location_id'=>$cr->location_id,
					'product_name'=>$cr->product_name,
					'cust_name'=>$cr->guest_name,
					'date'=>$cr->reservation_date,
					'vendor_name'=>$cr->vendor_name,
					'vendor_location'=>$cr->location_name,
					'address'=>$cr->address,
					'vendor_id'=>$cr->vendor_id);
			}

			if(array_key_exists($cr->vendor_id,$vendorReservId)){
				$vendorReservId['v'.$cr->vendor_id][] = $cr->id;
			} else {
				$vendorReservId['v'.$cr->vendor_id][] = $cr->id;
			}
			if(array_key_exists($cr->location_id,$vendorLocationReservId)){
				$vendorLocationReservId['vl'.$cr->location_id][] = $cr->id;
			} else {
				$vendorLocationReservId['vl'.$cr->location_id][] = $cr->id;
			}
		}

		//print_r($vendorLocationReservId);die;

		//create array for multiple vendors having multiple locations
		/*foreach($closedReservation as $cr){
			if(array_key_exists($cr->location_id,$locationArr)){
				$locationArr[$cr->vendor_id][$cr->vendor_name][$cr->location_id][$cr->location_name][] = array('reservation_id'=>$cr->id,
					                                   'location_id'=>$cr->location_id,
														'product_name'=>$cr->product_name,
														'cust_name'=>$cr->guest_name,
														'date'=>$cr->reservation_date,
														'vendor_name'=>$cr->vendor_name,
														'vendor_location'=>$cr->location_name,
														'address'=>$cr->address);
			} else {
				$locationArr[$cr->vendor_id][$cr->vendor_name][$cr->location_id][$cr->location_name][] = array('reservation_id'=>$cr->id,
														'location_id'=>$cr->location_id,
														'product_name'=>$cr->product_name,
														'cust_name'=>$cr->guest_name,
														'date'=>$cr->reservation_date,
														'vendor_name'=>$cr->vendor_name,
														'vendor_location'=>$cr->location_name,
														'address'=>$cr->address);
			}

		}*/
		//print_r($locationArr);die;
		//die;
		return view('admin.invoices.create')->with('invoices',$locationArr)
			->with('closedreserv',$closedReservation)
			->with('vendor_reservation_id',urlencode(json_encode($vendorReservId)))
			->with('vendor_location_reservation_id',urlencode(json_encode($vendorLocationReservId)));

	}

	public function generatePdf(){

		$reservation_ids = $this->request->get('reservation_ids');
		$vendor_id = $this->request->get('vendor_id');
		$vendor_id =  str_replace('v','',$vendor_id);
		//print_r(json_decode($reservation_ids));die;

		$invoiceDetails = DB::table('reservation_details')
			->leftJoin(DB::raw('vendor_locations as vc'),'vc.id','=','reservation_details.vendor_location_id')
			->leftJoin(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','vc.id')
			->leftJoin(DB::raw('vendors as v'),'v.id','=','vc.vendor_id')
			->leftJoin(DB::raw('locations as l'),'l.id','=','vc.location_id')
			->leftJoin(DB::raw('products as p'),'p.id','=','reservation_details.product_id');
		if(isset($vendor_id) && !empty($vendor_id)){
			$invoiceDetails->where('v.id','>=',$vendor_id);
		}

		$invoiceDetails
			->whereIn('reservation_details.id',json_decode($reservation_ids))
			->select(DB::raw('reservation_details.id,reservation_details.product_id,reservation_details.vendor_location_id,reservation_details.reservation_type,reservation_details.user_id,reservation_details.guest_name,reservation_details.reservation_date,v.id as vendor_id,v.name as vendor_name,l.id as location_id,l.name as location_name ,p.name as product_name,vla.address'));
		$data = $invoiceDetails->get();
		$finalData = array();

		foreach($data as $cr){

			if(array_key_exists($cr->location_id,$finalData)){
				$finalData[$cr->vendor_id][$cr->location_id][] = array('reservation_id'=>$cr->id,
					'location_id'=>$cr->location_id,
					'product_name'=>$cr->product_name,
					'cust_name'=>$cr->guest_name,
					'date'=>$cr->reservation_date,
					'vendor_name'=>$cr->vendor_name,
					'vendor_location'=>$cr->location_name,
					'address'=>$cr->address,
					'vendor_id'=>$cr->vendor_id,
					'type'=>$cr->reservation_type,
					'product_id'=>$cr->product_id);
			} else {
				$finalData[$cr->vendor_id][$cr->location_id][] = array('reservation_id'=>$cr->id,
					'location_id'=>$cr->location_id,
					'product_name'=>$cr->product_name,
					'cust_name'=>$cr->guest_name,
					'date'=>$cr->reservation_date,
					'vendor_name'=>$cr->vendor_name,
					'vendor_location'=>$cr->location_name,
					'address'=>$cr->address,
					'vendor_id'=>$cr->vendor_id,
					'type'=>$cr->reservation_type,
					'product_id'=>$cr->product_id);
			}
		}

		//billing details
		$billingArr = array();
		foreach($data as $values){
			$billingArr[$values->id] = array();
			$billingArr[$values->id]['total_commission'] = 0;
			if($values->reservation_type == 'experience'){
				$reservationDetailsAttr = $this->reservationDetails->getByReservationId($values->id);
				if($reservationDetailsAttr['attributes']['actual_experience_takers'] != 0) {
					$priceDetails = $this->repository->populateProductPricing($values->product_id);
					$billingArr[$values->id]['base']['seated'] = $reservationDetailsAttr['attributes']['actual_experience_takers'];
					$billingArr[$values->id]['base']['unit'] = $priceDetails[0]->commission;
					$billingArr[$values->id]['base']['total'] = $reservationDetailsAttr['attributes']['actual_experience_takers']*$priceDetails[0]->commission;
					$billingArr[$values->id]['total_commission'] += $billingArr[$values->id]['base']['total'];
				}

				if(isset($reservationDetailsAttr['attributes']['actual_alacarte_takers']) && $reservationDetailsAttr['attributes']['actual_alacarte_takers'] != 0) {
					$priceDetails = $this->alacarterepository->getByRestaurantLocationId($values->vendor_location_id);
					if(isset($priceDetails['attributes']['commission_per_cover'])){
						$billingArr[$values->id]['alacarte']['seated'] = $reservationDetailsAttr['attributes']['actual_alacarte_takers'];
						$billingArr[$values->id]['alacarte']['unit'] = $priceDetails['attributes']['commission_per_cover'];
						$billingArr[$values->id]['alacarte']['total'] = $reservationDetailsAttr['attributes']['actual_alacarte_takers']*$priceDetails['attributes']['commission_per_cover'];
						$billingArr[$values->id]['total_commission'] += $billingArr[$values->id]['alacarte']['total'];
					}
				}

				$actual_addon_takers = $this->reservationDetails->getActualAddonTakers($values->id);
				if(!empty($actual_addon_takers)){
					$expAddOns = $this->repository->populateProductAddOns($values->product_id);
					foreach($actual_addon_takers as $aat){
						$billingArr[$values->id]['addon']['seated'] = $aat->no_of_persons;
						$billingArr[$values->id]['addon']['unit'] = $expAddOns[$aat->options_id]['commission'];
						$billingArr[$values->id]['addon']['total'] = $aat->no_of_persons*$expAddOns[$aat->options_id]['commission'];
						$billingArr[$values->id]['total_commission'] += $billingArr[$values->id]['addon']['total'];
					}

				}


			} else if($values->reservation_type == 'alacarte') {
				//echo $values->id;
				$alacarte = $this->alacarterepository->getByRestaurantLocationId($values->vendor_location_id);
				$reservationDetailsAttr = $this->reservationDetails->getByReservationId($values->id);
				if(isset($alacarte['attributes']['commission_per_cover'])){

					$billingArr[$values->id]['seated'] = $reservationDetailsAttr['attributes']['total_seated'];
					$billingArr[$values->id]['unit'] = substr($alacarte['attributes']['commission_per_cover'], 0, strlen($alacarte['attributes']['commission_per_cover']) - 2);
					$billingArr[$values->id]['total'] = $reservationDetailsAttr['attributes']['total_seated'] * $alacarte['attributes']['commission_per_cover'];
					$billingArr[$values->id]['total_commission'] += $billingArr[$values->id]['total'];
				}
			}

		}

		//print_r($billingArr);die ;
		return view('admin.invoices.invoice')->with('finaldata',$finalData)->with('billinginfo',$billingArr);
		//print_r($finalData);die;
	}

	public function viewPdf(){

		//$pdf = App::make('snappy.pdf.wrapper');
		//$pdf->loadView('admin.invoices.sample');
		//return $pdf->download('invoice.pdf');
		$pdfObject = new PdfWrapper(new Pdf());
		$pdf = $pdfObject::loadView('admin.invoices.sample');
		return $pdf->download('invoice.pdf');

		//return view('admin.invoices.invoice');
	}
}
