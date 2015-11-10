<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use Input;
use Mail;

use Illuminate\Http\Request;
use WowTables\Sharing;

class SharingController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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

	public function shareDetails(){
	
		$emails_list = Input::get('emails');
		$emails_content = Input::get('content');
		$reservid = Input::get('reservid');
		$userid = Input::get('userid');
		$expid = Input::get('expid');
		$explocid = Input::get('explocid');
		$restid = Input::get('restid');
		$reservation_type = Input::get('reservation_type');
		$user = (Input::get('user') != "" ? Input::get('user') : "" );
		$restaurant_name = Input::get('restaurant');
		$outlet_name = Input::get('outlet_name');
		$date_reservation = Input::get('date_reservation');
		$date_seating = Input::get('date_seating');
		$guests = Input::get('guests');
		$url_product1 = Input::get('url_product');
		$short_description = Input::get('short_description');
		$restlocid = Input::get('restlocid');
		$expname = Input::get('expname');
		//echo "sd = ".$reservation_type;
		if($reservation_type == "experience"){
			$append_url = "?utm_source=Website&utm_campaign=Share%20Experiences%20Thank%20You";
		} else if($reservation_type == "alacarte"){
			$append_url = "?utm_source=Website&utm_campaign=Share%20A%20La%20Carte%20Thank%20You";
		} else if($reservation_type == "experience_detail"){
			$append_url = "?utm_source=Website&utm_campaign=Share%20Experiences%20Detail";
		} else if($reservation_type == "alacarte_detail"){
			$append_url = "?utm_source=Website&utm_campaign=Share%20A%20La%20Carte%20Detail";
		}
		$url_product = $url_product1.$append_url;
		//echo $url_product; die;
		$sharearray = array('content'=>$emails_content,
			'user'=>((isset($user) && $user != "") ? $user : "Your Friend" ),
			'restaurant_name'=>$restaurant_name,
			'outlet_name'=>$outlet_name,
			'date_reservation'=>$date_reservation,
			'date_seating'=>$date_seating,
			'guests'=>$guests,
			'url_product'=>$url_product,
			'short_description'=>$short_description,
			'reservation_type'=>$reservation_type,
			'expname'=>$expname,
		);
		if($user != ""){
			$by_user = " by ".$user;
		} else {
			$by_user = "";
		}
		if($reservation_type == "experience_detail"){
			$subject = "Your friend has suggested you view Wowtables dining experience at ".$restaurant_name;
			$static_subject = "User Suggested dining experience to Party";
		}else{
			$subject = "You have been invited to a WowTables dining experience".$by_user;
			$static_subject = "User Forwarded Reservation Details to Party";
		}
		//echo "sd<pre>"; print_r($sharearray); die;
		if(!empty($emails_list)){
			$emails= explode(',',$emails_list);
			foreach($emails as $email){
				//echo "<pre>"; print_r($email);

				$sharing = new Sharing();

				$sharing->user_id = $userid;
				$sharing->reservation_id = $reservid;
				if($reservation_type == "experience"){
					$sharing->product_id = $expid;
					$sharing->product_vendor_location_id = $explocid;
				} else if($reservation_type == "experience_detail"){
					$sharing->product_id = $expid;
				}
				$sharing->restaurant_id = $restid;
				if($reservation_type == "alacarte"){
					$sharing->restaurant_location_id = $restlocid;
				}
				$sharing->email_address = $email;
				$sharing->type = $reservation_type;
				//echo "<pre>"; print_r($sharing);
				$sharing->save();
				//die;
				Mail::send('site.pages.share',[
					'share_data'=> $sharearray
				], function($message) use ($email,$user,$subject)
				{
					$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

					$message->to($email)->subject($subject);
					//$message->cc(['kunal@wowtables.com', 'deepa@wowtables.com']);
				});
			}
			//echo "<pre>"; print_r($sharearray); die;
			$sharearray['is_admin'] = "yes";
			$sharearray['emails_list'] = $emails_list;
			//echo "<pre>"; print_r($sharearray); die;
			Mail::send('site.pages.share',[
				'share_data'=> $sharearray
			], function($message) use ($sharearray,$static_subject)
			{
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('kunal@wowtables.com')->subject($static_subject);
				//$message->cc(['kunal@wowtables.com', 'deepa@wowtables.com']);
			}); //die;
			echo 1;
		}
	}

	
	
	public function shareDetailsFriend(){
		$emails_list = Input::get('emails');
		$emails_content = Input::get('content');
		$reservid = Input::get('reservid');
		$userid = Input::get('userid');
		$reservation_type = Input::get('reservation_type');
		$user = (Input::get('user_name') != "" ? Input::get('user_name') : "" );
		$restaurant_name = Input::get('restaurant');
		$outlet_name = Input::get('outlet_name');
		$date_reservation = Input::get('date_reservation');
		$date_seating = Input::get('date_seating');
		$guests = Input::get('guests');
		$short_description = Input::get('short_description');
		$address = Input::get('address');
		$product_id = Input::get('product_id');
		$vl_id = Input::get('vl_id');
		$vendor_location_id = Input::get('vendor_location_id');
	
	
		//echo $url_product; die;
		$sharearray = array('content'=>$emails_content,
			'user'=>((isset($user) && $user != "") ? $user : "Your Friend" ),
			'restaurant_name'=>$restaurant_name,
			'outlet_name'=>$outlet_name,
			'date_reservation'=>$date_reservation,
			'date_seating'=>$date_seating,
			'guests'=>$guests,
			'short_description'=>$short_description,
			'reservation_type'=>$reservation_type,	
			'address'=>$address,	
			'reservation_id'=>$reservid,	
		);
		if($user != ""){
			$by_user = " by ".$user;
		} else {
			$by_user = "";
		}
		if($reservation_type == "experience_detail"){
			$subject = "Your friend has suggested you view Wowtables dining experience at ".$restaurant_name;
			$static_subject = "User Suggested dining experience to Party";
		}else{
			$subject = "You have been invited to a WowTables dining experience".$by_user;
			$static_subject = "User Forwarded Reservation Details to Party";
		}
		//echo "sd<pre>"; print_r($sharearray); die;
		if(!empty($emails_list)){
			$emails= explode(',',$emails_list);
			foreach($emails as $email){
				//echo "<pre>"; print_r($email);

				$sharing = new Sharing();

				$sharing->user_id = $userid;
				$sharing->reservation_id = $reservid;
				if($reservation_type == "experience"){
					$sharing->product_id = $product_id;
					$sharing->product_vendor_location_id = $vl_id;
				} else if($reservation_type == "experience_detail"){
					$sharing->product_id = $product_id;
				}
				$sharing->restaurant_id = $reservid;
				if($reservation_type == "alacarte"){
					$sharing->product_id = $reservid;
					
					$sharing->restaurant_location_id = $vendor_location_id;
				}
				$sharing->email_address = $email;
				$sharing->type = $reservation_type;
				//echo "<pre>"; print_r($sharing);
				$sharing->save();
				//die;
				if($reservation_type == "experience"){
				$template='site.pages.share_experience';
				}
				elseif($reservation_type == "alacarte"){
				$template='site.pages.share_alacarte';
				}
				
				
				
				
				Mail::send($template,[
					'share_data'=> $sharearray
				], function($message) use ($email,$user,$subject)
				{
					$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

					$message->to($email)->subject($subject);
					//$message->cc(['kunal@wowtables.com', 'deepa@wowtables.com']);
				});
				
			}
			//echo "<pre>"; print_r($sharearray); die;
			$sharearray['is_admin'] = "yes";
			$sharearray['emails_list'] = $emails_list;
			//echo "<pre>"; print_r($sharearray); die;
			
			Mail::send($template,[
				'share_data'=> $sharearray
			], function($message) use ($sharearray,$static_subject)
			{
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('kunal@wowtables.com')->subject($static_subject);
				//$message->cc(['kunal@wowtables.com', 'deepa@wowtables.com']);
			}); //die;
			
			echo 1;
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
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
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
