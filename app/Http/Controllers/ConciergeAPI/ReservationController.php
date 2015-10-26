<?php namespace WowTables\Http\Controllers\ConciergeApi;

use Carbon\Carbon;
use Config;
use Davibennun\LaravelPushNotification\Facades\PushNotification;
use DB;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAddonsVariantsDetail;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesBoolean;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesDate;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesDateLog;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesFloat;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesInteger;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesIntegerLog;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesText;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesTextLog;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributesVarchar;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationDetail;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationStatus;
use WowTables\Http\Models\Eloquent\ConciergeApi\ReservationStatusLog;
use WowTables\Http\Models\Eloquent\ConciergeApi\User;
use WowTables\Http\Models\Eloquent\ConciergeApi\UserDevice;
use WowTables\Http\Models\Eloquent\ConciergeApi\UserPreference;
use WowTables\Http\Models\Eloquent\ConciergeApi\UserRating;
use WowTables\Http\Models\Eloquent\ConciergeApi\VendorLocationContact;
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Eloquent\Products\Product;
use WowTables\Http\Models\Eloquent\UserAttributesVarChar;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesText;
use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use Request;

class ReservationController extends Controller {
	public static $status_attr_id = 29;
	private static $special_request_acc_attr_id = 30;
	private static $special_request_resp_attr_id = 31;
	private static $note_to_wow_attr_id = 32;
	private static $seating_status_attr_id = 33;
	private static $table_size_attr_id = 12;
	private static $gift_card_clo_attr_id = 19;
	private static $total_bill_attr_id = 26;
	private static $prepaid_attr_id = 35;
	private static $server_attr_id = 36;
	private static $rejection_reason_attr_id = 38;
	private static $exp_attendees_attr_id = 24;
	private static $alacarte_attendees_attr_id = 25;
	public static $new_status_id = 1;
	private static $accepted_status_id = 6;
	private static $rejected_status_id = 7;
	private static $cancelled_status_id = 3;
	private static $noshow_status_id = 9;
	private static $closed_status_id = 8;
	private static $edited_status_id = 2;
	private static $closed_date_id = 39;
	private static $cancelled_seating_status = 3;
	private static $noshow_seating_status = 4;
	private static $cust_pref_attr_id = 11;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($statuses,$accessToken)
	{
		try {
			$reservationIdArr = array();
			$reservationArr = array();
			$userDevice = UserDevice::where('rest_access_token', $accessToken)->first();
			$conciergeLocations = VendorLocationContact::where('user_id', $userDevice->user_id)->get();
			$reservationIntAttrArr = ReservationAttributesInteger::statusIn($statuses)->get();
			foreach ($reservationIntAttrArr as $reservationIntAttr) {
				$reservationDetail = ReservationDetail::locationIn($conciergeLocations, $reservationIntAttr->reservation_id)->first();
				if ($reservationDetail) {
					array_push($reservationIdArr, $reservationIntAttr->reservation_id);
					if (array_key_exists((string)$reservationIntAttr->reservation_id, $reservationArr))
						$reservation = $reservationArr[(string)$reservationIntAttr->reservation_id];
					else
						$reservation = array();
					$key = 'reservation_id';
					$value = (string)$reservationIntAttr->reservation_id;
					$reservation[$key] = $value;
					$reservationArr[(string)$reservationIntAttr->reservation_id] = $reservation;
				}
			}
			if (sizeof($reservationIdArr) > 0) {
				//Int type
				$reservationIntAttrArr = ReservationAttributesInteger::reservationIdIn($reservationIdArr)->get();
				foreach ($reservationIntAttrArr as $reservationIntAttr) {
					if (array_key_exists((string)$reservationIntAttr->reservation_id, $reservationArr))
						$reservation = $reservationArr[(string)$reservationIntAttr->reservation_id];
					else
						$reservation = array();
					$key = $reservationIntAttr->attribute->alias;
					$value = $reservationIntAttr->attribute_value;
					$reservation[$key] = $value;
					//Check log for same attribute
					$reservationStatusLog = ReservationStatusLog::where(['reservation_id' => $reservationIntAttr->reservation_id,
						'new_reservation_status_id' => ReservationController::$edited_status_id])->orderBy('created_at', 'DESC')->first();
					if ($reservationStatusLog != null) {
						$reservationIntAttrLogArr = ReservationAttributesIntegerLog::where(['reservation_attribute_id' => $reservationIntAttr->attribute->id,
							'reservation_status_log_id' => $reservationStatusLog->id])->get();
						foreach ($reservationIntAttrLogArr as $reservationIntAttrLog) {
							$key = "old_" . $reservationIntAttrLog->attribute->alias;
							$value = $reservationIntAttrLog->old_attribute_value;
							$reservation[$key] = $value;
						}
					}
					$reservationArr[(string)$reservationIntAttr->reservation_id] = $reservation;
				}
				//Float type
				$reservationFloatAttrArr = ReservationAttributesFloat::reservationIdIn($reservationIdArr)->get();
				foreach ($reservationFloatAttrArr as $reservationFloatAttr) {
					if (array_key_exists((string)$reservationFloatAttr->reservation_id, $reservationArr))
						$reservation = $reservationArr[(string)$reservationFloatAttr->reservation_id];
					else
						$reservation = array();
					$key = $reservationFloatAttr->attribute->alias;
					$value = $reservationFloatAttr->attribute_value;
					$reservation[$key] = $value;
					$reservationArr[(string)$reservationFloatAttr->reservation_id] = $reservation;
				}
				//Date type
				$reservationDateAttrArr = ReservationAttributesDate::reservationIdIn($reservationIdArr)->get();
				foreach ($reservationDateAttrArr as $reservationDateAttr) {
					if (array_key_exists((string)$reservationDateAttr->reservation_id, $reservationArr))
						$reservation = $reservationArr[(string)$reservationDateAttr->reservation_id];
					else
						$reservation = array();
					$key = $reservationDateAttr->attribute->alias;
					$value = $reservationDateAttr->attribute_value;
					$reservation[$key] = $value;
					//Check log for same attribute
					$reservationStatusLog = ReservationStatusLog::where(['reservation_id' => $reservationDateAttr->reservation_id,
						'new_reservation_status_id' => ReservationController::$edited_status_id])->orderBy('created_at', 'DESC')->first();
					if ($reservationStatusLog != null) {
						$reservationDateAttrLogArr = ReservationAttributesDateLog::where(['reservation_attribute_id' => $reservationDateAttr->attribute->id,
							'reservation_status_log_id' => $reservationStatusLog->id])->get();
						foreach ($reservationDateAttrLogArr as $reservationDateAttrLog) {
							$key = "old_" . $reservationDateAttrLog->attribute->alias;
							$value = $reservationDateAttrLog->old_attribute_value;
							$reservation[$key] = $value;
						}
					}
					$reservationArr[(string)$reservationDateAttr->reservation_id] = $reservation;
				}

				//Text type
				$reservationTextAttrArr = ReservationAttributesText::reservationIdIn($reservationIdArr)->get();
				foreach ($reservationTextAttrArr as $reservationTextAttr) {
					if (array_key_exists((string)$reservationTextAttr->reservation_id, $reservationArr))
						$reservation = $reservationArr[(string)$reservationTextAttr->reservation_id];
					else
						$reservation = array();
					$key = $reservationTextAttr->attribute->alias;
					$value = $reservationTextAttr->attribute_value;
					$reservation[$key] = $value;
					$reservationStatusLog = ReservationStatusLog::where(['reservation_id' => $reservationTextAttr->reservation_id,
						'new_reservation_status_id' => ReservationController::$edited_status_id])->orderBy('created_at', 'DESC')->first();
					if ($reservationStatusLog != null) {
						$reservationTextAttrLogArr = ReservationAttributesTextLog::where(['reservation_attribute_id' => $reservationTextAttr->attribute->id,
							'reservation_status_log_id' => $reservationStatusLog->id])->get();
						foreach ($reservationTextAttrLogArr as $reservationTextAttrLog) {
							$key = "old_" . $reservationTextAttrLog->attribute->alias;
							$value = $reservationTextAttrLog->old_attribute_value;
							$reservation[$key] = $value;
						}
					}
					$reservationArr[(string)$reservationTextAttr->reservation_id] = $reservation;
				}

				//Boolean type
				$reservationBoolAttrArr = ReservationAttributesBoolean::reservationIdIn($reservationIdArr)->get();
				foreach ($reservationBoolAttrArr as $reservationBoolAttr) {
					if (array_key_exists((string)$reservationBoolAttr->reservation_id, $reservationArr))
						$reservation = $reservationArr[(string)$reservationBoolAttr->reservation_id];
					else
						$reservation = array();
					$key = $reservationBoolAttr->attribute->alias;
					$value = $reservationBoolAttr->attribute_value;
					if ($value == 1)
						$reservation[$key] = true;
					else
						$reservation[$key] = false;
					$reservationArr[(string)$reservationBoolAttr->reservation_id] = $reservation;
				}

				//VarChar type
				$reservationVarcharAttrArr = ReservationAttributesVarchar::reservationIdIn($reservationIdArr)->get();
				foreach ($reservationVarcharAttrArr as $reservationVarcharAttr) {
					if (array_key_exists((string)$reservationVarcharAttr->reservation_id, $reservationArr))
						$reservation = $reservationArr[(string)$reservationVarcharAttr->reservation_id];
					else
						$reservation = array();
					$key = $reservationVarcharAttr->attribute->alias;
					$value = $reservationVarcharAttr->attribute_value;
					$reservation[$key] = $value;
					$reservationArr[(string)$reservationVarcharAttr->reservation_id] = $reservation;
				}
				//Get Customer
				foreach ($reservationIdArr as $reservationId) {
					$reservationDetail = ReservationDetail::where('id', $reservationId)->first();
					if ($reservationDetail) {
						$reservation = $reservationArr[(string)$reservationId];
						$reservation['reservation_type'] = $reservationDetail->reservation_type;
						$customer = array();
						$customer['id'] = $reservationDetail->user->id;
						if ($reservationDetail->user->is_vip == 1)
							$customer['is_vip'] = true;
						else
							$customer['is_vip'] = false;
						$customer['full_name'] = $reservationDetail->user->full_name;
						$customer['email'] = $reservationDetail->user->email;
						$customer['phone_number'] = $reservationDetail->user->phone_number;
						$customer['points_earned'] = $reservationDetail->user->points_earned;
						$customer['rating'] = UserRating::where('user_id',$reservationDetail->user->id)->avg('rating');
						if ($customer['rating'] == null)
							$customer['rating'] = 0.0;
						$customerPreferences  = UserAttributesVarChar::where(['user_id' => $reservationDetail->user->id,
							'user_attribute_id' => ReservationController::$cust_pref_attr_id])->first();
						if($customerPreferences)
							$customer['customer_preferences'] = $customerPreferences->attribute_value;
						$reservation['customer'] = $customer;
						$reservationArr[(string)$reservationId] = $reservation;
					}
				}
				//Get Location
				foreach ($reservationIdArr as $reservationId) {
					$reservationDetail = ReservationDetail::where('id', $reservationId)->first();
					if ($reservationDetail) {
						$reservation = $reservationArr[(string)$reservationId];
						$location = array();
						$vendorLocation = VendorLocation::where('id', $reservationDetail->vendor_location_id)->first();
						$location['location_id'] = $vendorLocation->location_id;
						$location['location'] = Location::where('id', $vendorLocation->location_id)->first()->name;
						$reservation['location'] = $location;
						$reservationArr[(string)$reservationId] = $reservation;
					}
				}

				//Get Product
				foreach ($reservationIdArr as $reservationId) {
					$reservationDetail = ReservationDetail::where('id', $reservationId)->first();
					if ($reservationDetail && $reservationDetail->reservation_type == 'experience') {
						$reservation = $reservationArr[(string)$reservationId];
						$product = array();
						$product['product_id'] = $reservationDetail->product_id;
						$product['product'] = Product::where('id', $reservationDetail->product_id)->first()->name;
						$addonsArr = Product::where('product_parent_id', $reservationDetail->product_id)->get();
						if (sizeof($addonsArr) > 0) {
							$statusStrArr = explode('~', $statuses);
							$statusIntArr = [];
							foreach ($statusStrArr as $statusStr) {
								array_push($statusIntArr, (int)$statusStr);
							}
							$productAddonArr = array();
							foreach ($addonsArr as $addon) {
								$addons = array();
								$addons['addon_id'] = $addon->id;
								$addons['addon_name'] = $addon->name;
								$reservationAddonDetail = ReservationAddonsVariantsDetail::whereIn('reservation_status_id', $statusIntArr)->where(['reservation_id' => $reservationId, 'options_id' => $addon->id])->first();
								if ($reservationAddonDetail != null) {
									$addons['no_of_persons'] = $reservationAddonDetail->no_of_persons;
								}
								array_push($productAddonArr, $addons);
							}
							$product['addons'] = $productAddonArr;
						}
						$reservation['product'] = $product;
						$reservationArr[(string)$reservationId] = $reservation;
					}
				}


				return response()->json(array_values($reservationArr), 200);
			} else {
				return response()->json([
					'message' => 'No reservations found.'
				], 204);
			}
		}catch (\Exception $e){
			return response()->json([
				'message' => 'An application error occured.'
			], 500);
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		try {
			$input = Request::all();
			$reservationId = (int)$id;
			$newStatusId = (int)$input['new_status_id'];
			$responseData = array();
			$oldStatusId = -1;
			DB::beginTransaction();
			switch ($newStatusId) {
				case ReservationController::$accepted_status_id: {
					//Special Request Accepted,Special Request Response,Note to WowTables,Status Id
					$specialRequestAccAttr = new ReservationAttributesBoolean();
					$specialRequestAccAttr->reservation_id = $reservationId;
					$specialRequestAccAttr->reservation_attribute_id = ReservationController::$special_request_acc_attr_id;
					$specialRequestAccAttr->attribute_value = boolval($input['special_request_accepted']);
					$specialRequestAccAttr->save();
					if ($input['special_request_response']) {
						$specialRequestRespAttr = new ReservationAttributesText();
						$specialRequestRespAttr->reservation_id = $reservationId;
						$specialRequestRespAttr->reservation_attribute_id = ReservationController::$special_request_resp_attr_id;
						$specialRequestRespAttr->attribute_value = $input['special_request_response'];
						$specialRequestRespAttr->save();
					}
					if ($input['note_to_wowtables']) {
						$noteToWowTablesAttr = ReservationAttributesText::where(['reservation_id' => $reservationId, 'reservation_attribute_id' => ReservationController::$note_to_wow_attr_id])->first();
						if (!$noteToWowTablesAttr)
							$noteToWowTablesAttr = new ReservationAttributesText();
						$noteToWowTablesAttr->reservation_id = $reservationId;
						$noteToWowTablesAttr->reservation_attribute_id = ReservationController::$note_to_wow_attr_id;
						$noteToWowTablesAttr->attribute_value = $input['note_to_wowtables'];
						$noteToWowTablesAttr->save();
					}
					$statusAttr = ReservationAttributesInteger::where(['reservation_id' => $reservationId, 'reservation_attribute_id' => ReservationController::$status_attr_id])->first();
					$oldStatusId = $statusAttr->attribute_value;
					$statusAttr->attribute_value = (int)$input['new_status_id'];
					$statusAttr->save();
					break;
				}
				case ReservationController::$rejected_status_id: {
					//Reason for Rejection,Note to WowTables,Status Id
					$rejectionReasonAttr = new ReservationAttributesInteger();
					$rejectionReasonAttr->reservation_id = $reservationId;
					$rejectionReasonAttr->reservation_attribute_id = ReservationController::$rejection_reason_attr_id;
					$rejectionReasonAttr->attribute_value = (int)$input['rejection_reason_id'];
					$rejectionReasonAttr->save();
					if ($input['note_to_wowtables']) {
						$noteToWowTablesAttr = ReservationAttributesText::where(['reservation_id' => $reservationId, 'reservation_attribute_id' => ReservationController::$note_to_wow_attr_id])->first();
						if (!$noteToWowTablesAttr)
							$noteToWowTablesAttr = new ReservationAttributesText();
						$noteToWowTablesAttr->reservation_id = $reservationId;
						$noteToWowTablesAttr->reservation_attribute_id = ReservationController::$note_to_wow_attr_id;
						$noteToWowTablesAttr->attribute_value = $input['note_to_wowtables'];
						$noteToWowTablesAttr->save();
					}
					$statusAttr = ReservationAttributesInteger::where(['reservation_id' => $reservationId, 'reservation_attribute_id' => ReservationController::$status_attr_id])->first();
					$oldStatusId = $statusAttr->attribute_value;
					$statusAttr->attribute_value = (int)$input['new_status_id'];
					$statusAttr->save();
					$closeDateAttr = new ReservationAttributesDate();
					$closeDateAttr->reservation_id = $reservationId;
					$closeDateAttr->reservation_attribute_id = ReservationController::$closed_date_id;
					$closeDateAttr->attribute_value = Carbon::now();
					$responseData['closed_on'] = $closeDateAttr->attribute_value->format('Y-m-d H:i:s');
					$closeDateAttr->save();
					break;
				}
				case ReservationController::$cancelled_status_id: {
					$seatingStatusAttr = new ReservationAttributesInteger();
					$seatingStatusAttr->reservation_id = $reservationId;
					$seatingStatusAttr->reservation_attribute_id = ReservationController::$seating_status_attr_id;
					$seatingStatusAttr->attribute_value = ReservationController::$cancelled_seating_status;
					$seatingStatusAttr->save();
					$statusAttr = ReservationAttributesInteger::where(['reservation_id' => $reservationId, 'reservation_attribute_id' => ReservationController::$status_attr_id])->first();
					$oldStatusId = $statusAttr->attribute_value;
					$statusAttr->attribute_value = (int)$input['new_status_id'];
					$statusAttr->save();
					$closeDateAttr = new ReservationAttributesDate();
					$closeDateAttr->reservation_id = $reservationId;
					$closeDateAttr->reservation_attribute_id = ReservationController::$closed_date_id;
					$closeDateAttr->attribute_value = Carbon::now();
					$responseData['closed_on'] = $closeDateAttr->attribute_value->format('Y-m-d H:i:s');
					$closeDateAttr->save();
					break;
				}
				case ReservationController::$noshow_status_id: {
					$seatingStatusAttr = new ReservationAttributesInteger();
					$seatingStatusAttr->reservation_id = $reservationId;
					$seatingStatusAttr->reservation_attribute_id = ReservationController::$seating_status_attr_id;
					$seatingStatusAttr->attribute_value = ReservationController::$noshow_seating_status;
					$seatingStatusAttr->save();
					$statusAttr = ReservationAttributesInteger::where(['reservation_id' => $reservationId, 'reservation_attribute_id' => ReservationController::$status_attr_id])->first();
					$oldStatusId = $statusAttr->attribute_value;
					$statusAttr->attribute_value = (int)$input['new_status_id'];
					$statusAttr->save();
					$closeDateAttr = new ReservationAttributesDate();
					$closeDateAttr->reservation_id = $reservationId;
					$closeDateAttr->reservation_attribute_id = ReservationController::$closed_date_id;
					$closeDateAttr->attribute_value = Carbon::now();
					$responseData['closed_on'] = $closeDateAttr->attribute_value->format('Y-m-d H:i:s');
					$closeDateAttr->save();
					break;
				}
				case ReservationController::$closed_status_id: {
					//Seating Status,Server Name, Table Size,Exp Attendees,Variants,Addons,Alacarte Attendees,Total Bill,
					//Prepaid amount,Gift Card Id,Customer Preference ,Status Id
					$seatingStatusAttr = new ReservationAttributesInteger();
					$seatingStatusAttr->reservation_id = $reservationId;
					$seatingStatusAttr->reservation_attribute_id = ReservationController::$seating_status_attr_id;
					$seatingStatusAttr->attribute_value = (int)$input['seating_status_id'];
					$seatingStatusAttr->save();
					$serverNameAttr = new ReservationAttributesText();
					$serverNameAttr->reservation_id = $reservationId;
					$serverNameAttr->reservation_attribute_id = ReservationController::$server_attr_id;
					$serverNameAttr->attribute_value = $input['server_name'];
					$serverNameAttr->save();
					$tableSizeAttr = new ReservationAttributesInteger();
					$tableSizeAttr->reservation_id = $reservationId;
					$tableSizeAttr->reservation_attribute_id = ReservationController::$table_size_attr_id;
					$tableSizeAttr->attribute_value = (int)$input['table_size'];
					$tableSizeAttr->save();
					$totalBillAttr = new ReservationAttributesFloat();
					$totalBillAttr->reservation_id = $reservationId;
					$totalBillAttr->reservation_attribute_id = ReservationController::$total_bill_attr_id;
					$totalBillAttr->attribute_value = (float)$input['total_bill'];
					$totalBillAttr->save();
					$prepaidAmtAttr = new ReservationAttributesFloat();
					$prepaidAmtAttr->reservation_id = $reservationId;
					$prepaidAmtAttr->reservation_attribute_id = ReservationController::$prepaid_attr_id;
					$prepaidAmtAttr->attribute_value = (float)$input['prepaid_amount'];
					$prepaidAmtAttr->save();
					$giftCardAttr = new ReservationAttributesText();
					$giftCardAttr->reservation_id = $reservationId;
					$giftCardAttr->reservation_attribute_id = ReservationController::$gift_card_clo_attr_id;
					$giftCardAttr->attribute_value = $input['gift_card'];
					$giftCardAttr->save();
					if (array_key_exists('experience_attendees', $input)) {
						$expAttendeesAttr = new ReservationAttributesInteger();
						$expAttendeesAttr->reservation_id = $reservationId;
						$expAttendeesAttr->reservation_attribute_id = ReservationController::$exp_attendees_attr_id;
						$expAttendeesAttr->attribute_value = (int)$input['experience_attendees'];
						$expAttendeesAttr->save();
					}
					if (array_key_exists('addons', $input)) {
						$addonsArr = json_decode($input['addons'], true);
						foreach ($addonsArr as $addon) {
							$reservationAddon = new ReservationAddonsVariantsDetail();
							$reservationAddon->reservation_id = $reservationId;
							$reservationAddon->no_of_persons = (int)$addon['people'];
							$reservationAddon->options_id = (int)$addon['addon_id'];
							$reservationAddon->option_type = 'addon';
							$reservationAddon->reservation_type = 'experience';
							$reservationAddon->reservation_status_id = ReservationController::$closed_status_id;
							$reservationAddon->save();
						}
					}
					if (array_key_exists('alacarte_attendees', $input)) {
						$alacarteAttendeesAttr = new ReservationAttributesInteger();
						$alacarteAttendeesAttr->reservation_id = $reservationId;
						$alacarteAttendeesAttr->reservation_attribute_id = ReservationController::$alacarte_attendees_attr_id;
						$alacarteAttendeesAttr->attribute_value = (int)$input['alacarte_attendees'];
						$alacarteAttendeesAttr->save();
					}
					$reservationDetail = ReservationDetail::where('id', $reservationId)->first();
					$userRating = new UserRating();
					$userRating->user_id = $reservationDetail->user_id;
					$userRating->reservation_id = $reservationDetail->id;
					$userRating->rating = floatval($input['rating']);
					$userRating->save();
					if (array_key_exists('customer_preference', $input)) {
						$customerPreferences  = UserAttributesVarChar::where(['user_id' => $reservationDetail->user->id,
							'user_attribute_id' => ReservationController::$cust_pref_attr_id])->first();
						if($customerPreferences==null) {
							$customerPreferences = new UserAttributesVarChar();
							$customerPreferences->user_id = $reservationDetail->user_id;
							$customerPreferences->user_attribute_id = ReservationController::$cust_pref_attr_id;
						}
						$customerPreferences->attribute_value = $input['customer_preference'];
						$customerPreferences->save();
					}
					$giftCardAttr->reservation_attribute_id = ReservationController::$gift_card_clo_attr_id;
					$giftCardAttr->attribute_value = $input['gift_card'];
					$giftCardAttr->save();
					$statusAttr = ReservationAttributesInteger::where(['reservation_id' => $reservationId, 'reservation_attribute_id' => ReservationController::$status_attr_id])->first();
					$oldStatusId = $statusAttr->attribute_value;
					$statusAttr->attribute_value = (int)$input['new_status_id'];
					$statusAttr->save();
					$closeDateAttr = new ReservationAttributesDate();
					$closeDateAttr->reservation_id = $reservationId;
					$closeDateAttr->reservation_attribute_id = ReservationController::$closed_date_id;
					$closeDateAttr->attribute_value = Carbon::now();
					$responseData['closed_on'] = $closeDateAttr->attribute_value->format('Y-m-d H:i:s');
					$responseData['rating'] = UserRating::where('user_id',$reservationDetail->user->id)->avg('rating');
					if ($responseData['rating'] == null)
						$responseData['rating'] = 0.0;
					$closeDateAttr->save();
					break;
				}
			}
			ReservationStatusLog::create(['reservation_id' => $reservationId, 'user_id' => (int)$input['user_id']
				, 'old_reservation_status_id' => $oldStatusId, 'new_reservation_status_id' => $newStatusId
				]);
			DB::commit();
			return response()->json($responseData, 200);

		}catch(\Exception $e){
			return response()->json([
				'message' => 'An application error occured.'
			], 500);
		}
	}

	/**
	 * Push this reservation to specified devices
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function push($id,$tokenArr,$showNotification)
	{
		try{
			$reservation = array();
			$key = 'reservation_id';
			$value = (int)$id;
			$reservation[$key] = $value;

				//Int type
				$reservationIntAttrArr = ReservationAttributesInteger::where('reservation_id',(int)$id)->get();
				foreach ($reservationIntAttrArr as $reservationIntAttr) {
					$key = $reservationIntAttr->attribute->alias;
					$value = $reservationIntAttr->attribute_value;
					$reservation[$key] = $value;
					//Check log for same attribute
					$reservationStatusLog = ReservationStatusLog::where(['reservation_id'=>$reservationIntAttr->reservation_id,
						'new_reservation_status_id'=>ReservationController::$edited_status_id])->orderBy('created_at', 'DESC')->first();
					if($reservationStatusLog!=null) {
						$reservationIntAttrLogArr = ReservationAttributesIntegerLog::where(['reservation_attribute_id' => $reservationIntAttr->attribute->id,
							'reservation_status_log_id' => $reservationStatusLog->id])->get();
						foreach($reservationIntAttrLogArr as $reservationIntAttrLog){
							$key = "old_".$reservationIntAttrLog->attribute->alias;
							$value = $reservationIntAttrLog->old_attribute_value;
							$reservation[$key] = $value;
						}
					}
				}
				//Float type
				$reservationFloatAttrArr = ReservationAttributesFloat::where('reservation_id',(int)$id)->get();
				foreach ($reservationFloatAttrArr as $reservationFloatAttr) {
					$key = $reservationFloatAttr->attribute->alias;
					$value = $reservationFloatAttr->attribute_value;
					$reservation[$key] = $value;
				}
				//Date type
				$reservationDateAttrArr = ReservationAttributesDate::where('reservation_id',(int)$id)->get();
				foreach ($reservationDateAttrArr as $reservationDateAttr) {
					$key = $reservationDateAttr->attribute->alias;
					$value = $reservationDateAttr->attribute_value;
					$reservation[$key] = $value;
					//Check log for same attribute
					$reservationStatusLog = ReservationStatusLog::where(['reservation_id'=>$reservationDateAttr->reservation_id,
						'new_reservation_status_id'=>ReservationController::$edited_status_id])->orderBy('created_at', 'DESC')->first();
					if($reservationStatusLog!=null) {
						$reservationDateAttrLogArr = ReservationAttributesDateLog::where(['reservation_attribute_id' => $reservationDateAttr->attribute->id,
							'reservation_status_log_id' => $reservationStatusLog->id])->get();
						foreach($reservationDateAttrLogArr as $reservationDateAttrLog){
							$key = "old_".$reservationDateAttrLog->attribute->alias;
							$value = $reservationDateAttrLog->old_attribute_value;
							$reservation[$key] = $value;
						}
					}
				}

				//Text type
				$reservationTextAttrArr = ReservationAttributesText::where('reservation_id',(int)$id)->get();
				foreach ($reservationTextAttrArr as $reservationTextAttr) {
					$key = $reservationTextAttr->attribute->alias;
					$value = $reservationTextAttr->attribute_value;
					$reservation[$key] = $value;
					$reservationStatusLog = ReservationStatusLog::where(['reservation_id'=>$reservationTextAttr->reservation_id,
						'new_reservation_status_id'=>ReservationController::$edited_status_id])->orderBy('created_at', 'DESC')->first();
					if($reservationStatusLog!=null) {
						$reservationTextAttrLogArr = ReservationAttributesTextLog::where(['reservation_attribute_id' => $reservationTextAttr->attribute->id,
							'reservation_status_log_id' => $reservationStatusLog->id])->get();
						foreach($reservationTextAttrLogArr as $reservationTextAttrLog){
							$key = "old_".$reservationTextAttrLog->attribute->alias;
							$value = $reservationTextAttrLog->old_attribute_value;
							$reservation[$key] = $value;
						}
					}
				}

				//Boolean type
				$reservationBoolAttrArr = ReservationAttributesBoolean::where('reservation_id',(int)$id)->get();;
				foreach ($reservationBoolAttrArr as $reservationBoolAttr) {
					$key = $reservationBoolAttr->attribute->alias;
					$value = $reservationBoolAttr->attribute_value;
					if($value == 1)
						$reservation[$key] = true;
					else
						$reservation[$key] = false;
				}

				//VarChar type
				$reservationVarcharAttrArr = ReservationAttributesVarchar::where('reservation_id',(int)$id)->get();;
				foreach ($reservationVarcharAttrArr as $reservationVarcharAttr) {
					$key = $reservationVarcharAttr->attribute->alias;
					$value = $reservationVarcharAttr->attribute_value;
					$reservation[$key] = $value;
				}
				//Get Customer
					$reservationDetail = ReservationDetail::where('id', (int)$id)->first();
					if ($reservationDetail) {
						$reservation['reservation_type'] = $reservationDetail->reservation_type;
						$customer = array();
						$customer['id'] = $reservationDetail->user->id;
						$customer['full_name'] = $reservationDetail->user->full_name;
						$customer['email'] = $reservationDetail->user->email;
						$customer['phone_number'] = $reservationDetail->user->phone_number;
						$customer['points_earned'] = $reservationDetail->user->points_earned;
						$customer['rating'] = UserRating::where(['user_id'=>$reservationDetail->user,'id'=>$reservationDetail->id])->avg('rating');
						if($customer['rating']==null)
							$customer['rating'] = 0.0;
						$customerPreferences  = UserAttributesVarChar::where(['user_id' => $reservationDetail->user->id,
							'user_attribute_id' => ReservationController::$cust_pref_attr_id])->first();
						if($customerPreferences)
							$customer['customer_preferences'] = $customerPreferences->attribute_value;
						$reservation['customer'] = $customer;
					}
				//Get Location
					$reservationDetail = ReservationDetail::where('id', (int)$id)->first();
					if ($reservationDetail) {
						$location = array();
						$vendorLocation = VendorLocation::where('id',$reservationDetail->vendor_location_id)->first();
						$location['location_id'] = $vendorLocation->location_id ;
						$location['location'] = Location::where('id', $vendorLocation->location_id)->first()->name;
						$reservation['location'] = $location;
					}

				//Get Product
					$reservationDetail = ReservationDetail::where('id', (int)$id)->first();
					if ($reservationDetail && $reservationDetail->reservation_type=='experience') {
						$product = array();
						$product['product_id'] = $reservationDetail->product_id;
						$product['product'] = Product::where('id', $reservationDetail->product_id)->first()->name;
						$addonsArr = Product::where('product_parent_id', $reservationDetail->product_id)->get();
						if(sizeof($addonsArr)>0){
							$productAddonArr = array();
							foreach($addonsArr as $addon) {
								$addons = array();
								$addons['addon_id'] = $addon->id;
								$addons['addon_name'] = $addon->name;
								$reservationAddonDetail = ReservationAddonsVariantsDetail::where(['reservation_id'=>(int)$id,'reservation_status_id'=>$reservation['reservation_status_id'],'options_id'=>$addon->id])->first();
								if($reservationAddonDetail!=null){
									$addons['no_of_persons'] = $reservationAddonDetail->no_of_persons;
								}
								array_push($productAddonArr,$addons);
							}
							$product['addons']= $productAddonArr;
						}
						$reservation['product'] = $product;
					}

			if($showNotification)
				$reservation["show_notfn"]=1;
			else
				$reservation["show_notfn"]=0;
			//$tokenArr = json_decode($input['tokens'], true);
			foreach ($tokenArr as $token) {
				PushNotification::app('appNameAndroid')
					//->to("dM6qYZxj59A:APA91bHrPZ26AzKmUFTMG_nKrTZ0O_NU6gmrBErx-D3IRlHTHFXm33mkYUfhZ0mCwn_-lt6dC5-NgwsS-vNV_bcPNxyIB_eTDmEcDN8HsOKWW56v4M1JUtEdg_CJ2YrFjIgdIv_zQJG3")
					->to($token['token'])
					->send(json_encode($reservation));

			}
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			return response()->json($arrResponse, 200);
	}catch(\Exception $e){
			return response()->json([
			'message' => 'An application error occured.'
			], 500);
	}

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
