@extends('templates.admin_layout')

@section('content')
    <style>
        .small-ajax-loader {
            background: url("../assets/img/ajax-loader.gif") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
            display: none;
            height: 25px;
            position: absolute;
            right: 23px;
            top: 55px;
            width: 15px;
        }
        .small-ajax-loader-search {
            background: url("../assets/img/ajax-loader.gif") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
            display: none;
            height: 25px;
            position: absolute;
            right: 40px;
            top: 80px;
            width: 15px;
        }
        .error {
            color: #b94a39;
            font-size: 12px;
        }
        .show_in_input_yes{
            margin-left: 208px;
            margin-top: -30px;
            position: absolute;
            display: none;
        }
        .show_in_input_no{
            margin-left: 208px;
            margin-top: -30px;
            position: absolute;
            display: none;
        }
        .btn.btn-inverse {
            background: none repeat scroll 0 0 #000;
            border-radius: 0;
            color: #eab803;
            font-size: 13px;
            font-weight: 700;
            line-height: 14px;
            outline: medium none;
            padding: 5px 20px;
        }
        .modal-dialog{
            max-width: 400px;
        }

        .Highlighted a{
            background-color : #f0c140 !important;
            background-image :none !important;
            color: White !important;
            font-weight:bold !important;
            font-size: 12pt;
        }

        .time-highlight{
            background: none repeat scroll 0 0 #f0c140 !important;
            border-radius: 2px;
            color: #fff;
            height: 25px;
            margin-bottom: 6px;
            margin-left: 6px;
            margin-right: 6px;
            padding: 2px 0;
            text-align: center;
            text-decoration: none;
            white-space: nowrap;
        }
    </style>
    <h1 class="cms-title text-center">Add / Gift Cards</h1>
    <header class="page-header">
        <h2>Admin Gift Cards</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Admin Gift Cards</span></li>
            </ol>
        </div>
    </header>

    <section class="panel col-lg-12 col-lg-offset-0">

        <header class="panel-heading">
            <h2 class="panel-title">Admin Gift Cards</h2>
        </header>
        <div class="panel-body col-md-3 member-info-form">
            <p class="text-center"><strong>Add Gift Card information:</strong></p>
            <form role="form">
                <div class="form-group">
                    <input type="text" class="form-control" id="GiftCardId" name="GiftCardId" placeholder="Gift Card Id">
                    <span class="show_in_input_yes"><img src="/assets/img/yes.png" alt="exist"/></span>
                    <span class="show_in_input_no"><img src="/assets/img/no.png" alt="Does not exist"/></span>
                    <span class="small-ajax-loader"></span>
                    <span id="email_error" class="error"></span>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="Buyer_name" placeholder="Gift Card Buyer Name">
                    <span id="full_name_error" class="error"></span>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="Buyer_contact" placeholder="Phone Number">
                    <span id="phone_error" class="error"></span>
                </div>
               <div class="form-group">
                    <button type="button" id="edit_member" class="btn btn-inverse btn-block hidden">Edit Gift Card</button>
                </div>
                 <input type="hidden" value="" id="gift_id">
            </form>
        </div>
		 <div class="panel-body col-md-2">
		 </div>
       <div class="panel-body col-md-6 member-info-form hidden" id='add_gift_card_detail'>
	               <form role="form">
			<div class="form-group">
				<label for="Gift-card-id" class="control-label">Gift Card Id</label>
				<input class="form-control" name="Gift-card-id" type="text" id="gift-card-id" disabled>
				<span id="giftId_error" class="error"></span>
			</div>
			<div class="form-group">
				<label for="buyer_name" class="control-label">Buyer</label>
				<input class="form-control" name="buyer_name" type="text" id="buyer_name">
				<span id="buyer_error" class="error"></span>
			</div>
			<div class="form-group">
				<label for="buyer_contact" class="control-label">Buyer Contact Number</label>
				<input class="form-control" name="buyer_contact" type="text" id="buyer_contact">
				<span id="buyer_contact_error" class="error"></span>
			</div>
			 <div class="form-group email-field">
				<label for="buyer_contact" class="control-label">Buyer Contact Email</label>
				<input class="form-control" name="buyer_contact_email" type="text" id="buyer_contact_email">
				<span id="buyer_contact_email_error" class="error"></span>
			</div>
			<div class="form-group">
				<label for="gift_details" class="control-label">Exp/Rest/Package Details</label>
				<input class="form-control" name="gift_details" type="text" id="gift_details">
				<span id="gift_detail_error" class="error"></span>
			</div>
			<div class="form-group">
				<label  class="control-label">Number Of Guest</label>
				<input class="form-control" name="number_of_guest" type="text" id="number_of_guest">
			    <span id="guest_no_error" class="error"></span>
			</div>
			<div class="form-group">
				<label  class="control-label">Cash Value</label>
				<input class="form-control" name="cash_value" type="text" id="cash_value">
				  <span id="cash_value_error" class="error"></span>
			</div>
			<div class="form-group">
				<label  class="control-label">Name Of Giftee</label>
				<input class="form-control" name="name_of_giftee" type="text" id="name_of_giftee">
				  <span id="gift_no_error" class="error"></span>
			</div>
			<div class="form-group">
				<label  class="control-label">Contact Detail of Giftee</label>
				<input class="form-control" name="giftee_detail" type="text" id="giftee_detail">
				 <span id="giftee_detail_error" class="error"></span>
			</div>
			 <div class="form-group email-field">
				<label for="buyer_contact" class="control-label">Giftee Contact Email</label>
				<input class="form-control" name="giftee_contact_email" type="text" id="giftee_contact_email">
				<span id="giftee_contact_email_error" class="error"></span>
			</div>
			<div class="form-group">
				<label  class="control-label">Expire</label>
				<input class="form-control" name="expire_date" type="text" id="expire_date">
				<span id="gift_expire_error" class="error"></span>
			</div>
			
			<div class="form-group">
			<label  class="control-label">Redeemed ?</label>
                    <select name="redeemed" class="form-control populate" id="redeemed">
                        <option value="0">--Select--</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                                <option value="cancel">Cancel</option>
                                                
                                            </select>
							<span id="gift_redeemed_error" class="error"></span>				
                </div>
			<div class="form-group">
				<label  class="control-label">Credit Remaining ?</label>
				<input class="form-control" name="credit_remaining" type="text" id="credit_remaining">
				<span id="gift_credit_remaining_error" class="error"></span>	
			</div>
			<div class="form-group">
				<label  class="control-label">Notes</label>
				<input class="form-control" name="gift_note" type="text" id="gift_note">
			</div>
			  <input type="hidden" value="" id="card_id">
			
			<div class="form-group">
                    <button type="button" id="add_gift_card" class="btn btn-inverse btn-block">Add Gift Card</button>
                </div>
				
			</form>
		</div>
    </section>

    <!--edit Modal -->

    <div style="z-index: 9999;" class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background: #EAB703 !important;">
                <div id="load_layer" class="change_loader" >
                    <img src="/images/loading.gif">
                </div>
                <div class="modal-header" style="margin-top:-7px !important;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id='close_changes'>&times;</button>
                    <h4 class="modal-title text-center" id="myModalLabel">Change This Reservation</h4>
                </div>
                <div class="modal-body">
                    <div id="reserv_table">

                        <div id="my_locality">
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading active">
                                <h4 class="panel-title">
                                    <a href="javascript:" style="text-decoration: none;">
                                        Select Party Size </a><a  href="javascript:" data-original-title="Select the total number of guests at the table. If a larger table size is needed, please contact the WowTables Concierge." data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="http://wowtables.app/images/question_icon_small_display.png"></a>
                                    <select name="qty" id="party_size1"  class="pull-right space hidden">
                                        <option value="0">SELECT</option>

                                        <!-- <option value="2">2 People</option>
                                        <option value="7">7 People</option> -->

                                    </select>
                                    <strong><a id="party_edit1" href="javascript:"  style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;" id="myselect_person"></span> EDIT</a></strong>
                                </h4>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" >
                                <h4 class="panel-title">
                                    <a href="javascript:" style="text-decoration: none;">
                                        Select Date <input type="hidden" value="" id="vendor_id">
                                    </a>
                                    <strong><a id="date_edit12"  data-toggle="collapse" data-parent="#accordion" href="#collapseTwo1" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;" id="myselect_date"></span> EDIT</a></strong>
                                </h4>
                            </div>
                            <div id="collapseTwo1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="input-append date" id='dp1' data-date-format="dd-mm-yyyy">
                                        <input type="hidden" value="" name="booking_date" id="booking_date">
                                        <div class="options" style="margin: -10px;">
                                            <div id="choose_date"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="javascript:" style="text-decoration: none;">
                                        Select Time
                                    </a>
                                    <strong><a id="time_edit5"  data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;" id="myselect_time"></span> EDIT</a></strong>
                                </h4>
                            </div>
                            <div id="collapseThree5" class="panel-collapse collapse in" style="display:none;">
                                <div class="panel-body" id="timeajax">

                                </div>
                            </div>
                        </div>

                        <div id="my_addons">
                        </div>
                        <div id="my_giftcard">
                        </div>

                        <a id="save_changes" class="btn btn-warning" href="javascript:" style="margin-left: 32%;display:none;">Confirm Changes</a>

                        <div class="text-center" >

                            <input type="hidden" name="res_id" id="res_id">
                            <input type="hidden" value="" id="last_reserv_date" name="last_reserv_date">
                            <input type="hidden" value="" id="last_reserv_time" name="last_reserv_time">
                            <input type="hidden" value="" id="last_reserv_outlet" name="last_reserv_outlet">
                            <input type="hidden" value="" id="last_reserv_party_size" name="last_reserv_party_size">
                            <input type="hidden" value="" id="last_reservation_date" name="last_reservation_date">
                            <input type="hidden" value="" id="last_reservation_time" name="last_reservation_time">
                            <input type="hidden" value="" id="last_reservation_party_size" name="last_reservation_party_size">
                            <input type="hidden" value="" id="change_user_id" name="change_user_id">
                            <input type="hidden" value="admin" id="added_by" name="added_by">
                            <p id="cant_change_table" class="hidden">To make changes to your reservation for this evening please call our concierge or the restaurant directly.</p>
                            <p id="cant_change_table" class="hidden cant_change">Please make a change to confirm. If no change is required, please click on cancel.</p>
                            <a aria-hidden="true" data-dismiss="modal" id="cancel" class="btn btn-warning hidden cant_change" href="javascript:">Cancel</a>
                        </div>
                    </div>
                    <div class="change_reserv_confirmation hide" >
                        <h4 class="panel-title" style="margin-bottom: 20px;" id="my_update_confirm" style="display:none;">
                            We have received your table change request. You will receive a confirmation mail & SMS from our concierge soon.
                        </h4>
                        <h4 class="panel-title" style="margin-bottom: 20px;" id="my_update_immediate" style="display:none;">
                            To check for immediate availability, please call our concierge.
                        </h4>
                        <div class="text-center">
                            <a  class="btn btn-warning close_modal" href="javascript:" data-dismiss="modal">Close This</a>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!--edit Modal -->

    <!--add Experience Modal-->
    <div style="z-index: 9999;" class="modal fade" id="addExperienceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background:#EAB703; !important">
                <div id="load_layer" class="add_loader">
                    <img src="/images/loading.gif">
                </div>
                <div class="modal-header" style="margin-top:-8px !important;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center" id="myModalLabel">Add Reservation</h4>
                    <h4 class="modal-title text-center" id="myModalsmallLabel"></h4>
                </div>
                <div class="modal-body">

                    <form>
                        <div class="panel-group reservation-accordian" id="accordion">
                            <div class="panel panel-default hidden" id="location">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;color:#756554 !important;">
                                            Location
                                        </a>
                                        <select name="address" id="address" class="pull-right space"></select>
                                    </h4>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;color:#756554 !important;">
                                            Select Party Size

                                            <strong><a id='party_change' class="hidden" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;"></span> EDIT</a></strong>
                                        </a>
                                        <select id='party_size' name='party_size' class="pull-right space">
                                        </select>
                                    </h4>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;color:#756554 !important;">
                                            Select Date
                                        </a>
                                        <strong><a id="select_date" class="hidden" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;"></span> EDIT</a></strong>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div id='party_date'></div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;color:#756554 !important;">
                                            Select Time
                                        </a>
                                        <strong><a id="select_time" class="hidden" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;"></span> EDIT</a></strong>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div id="time"></div>
                                        <div id="hours"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default hidden" id="meal_options">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;color:#756554 !important;">
                                            Meal options<strong><a id="select_meal" class="hidden" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" style="text-decoration: none;float: right;font-size: 13px;color:#756554 !important;"><span style="color: #fff;"></span> EDIT</a></strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse">
                                    <div class="panel-body addon_meals" id="add_addon">

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default" id="add_options">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;color:#756554 !important;">
                                            Additional Options
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" id="additional_options">
                                            </a>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFive" class="panel-collapse collapse optional-fields">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="specialRequests" placeholder="Enter special requests">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="giftID" placeholder="Gift Card ID">
                                        </div>
                                        <div class="form-group">
                                            <label style="color:#756554 !important;">Award points for offline reservation  <input type="checkbox"  id="avard_point" value="1"></label>
                                        </div>
                                        <div class="form-group">
                                            <label style="color:#756554 !important;">Send Reservation Email  <input type="checkbox"  id="reservation_email" value="1"></label>
                                        </div>
                                        <input type="hidden" value="" id="reserv_date">
                                        <input type="hidden" value="" id="exp_id">
                                        <input type="hidden" value="" id="ac_restaurant_id">
                                        <input type="hidden" value="" id="price">
                                        <input type="hidden" value="" id="price_non_veg">
                                        <input type="hidden" value="" id="price_alcohol">
                                        <input type="hidden" value="" id="address_keyword">
                                        <input type="hidden" value="" id="addr">
                                        <input type="hidden" value="" id="city">
                                        <input type="hidden" value="" id="vendor_name">
                                        <input type="hidden" value="" id="experience_title">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="hidden" id="cant_select_table">To check for immediate availability, please call our concierge.</span>
                            <p class="hidden" id="cant_do_reserv1">You have an existing reservation that conflicts with this one. To modify or cancel your existing reservation please click</p>
                            <a class="btn btn-warning hidden" id="brs_my_reserv" href="">View My Existing Reservations</a>
                            <p class="hidden" id="cant_do_reserv2">If you have any queries please call our concierge desk.</p>
                            <a href="javascript:void(0)" class="btn btn-warning hidden" id='select_table'>SELECT TABLE</a>
                            <p class="hidden cant_change" id="cant_change_table">Please make a change to confirm. If no change is required, please click on cancel.</p>
                            <a href="javascript:void(0)" class="btn btn-warning hidden cant_change" id="cancel"  data-dismiss="modal" aria-hidden="true">Cancel</a>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!--add Experience Modal-->

    <!--add Alacarte Modal-->
    <div style="z-index: 9999;" class="modal fade" id="addAlacarteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="ac_load_layer" class="add_loader">
                    <img src="/images/loading.gif">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center" id="ac_myModalLabel">Add Reservation</h4>
                    <h4 class="modal-title text-center" id="ac_myModalsmallLabel"></h4>
                </div>
                <div class="modal-body">

                    <form>
                        <div class="panel-group reservation-accordian" id="ac_accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;">
                                            Select Party Size

                                            <strong><a id='ac_party_change' class="hidden" data-toggle="collapse" data-parent="#ac_accordion" href="#ac_collapseOne" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #000;"></span> EDIT</a></strong>
                                        </a>
                                        <select id='ac_party_size' name='party_size' class="pull-right space">
                                        </select>
                                    </h4>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;">
                                            Select Date
                                        </a>
                                        <strong><a id="ac_select_date" class="hidden" data-toggle="collapse" data-parent="#ac_accordion" href="#ac_collapseTwo" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #000;"></span> EDIT</a></strong>
                                    </h4>
                                </div>
                                <div id="ac_collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div id='ac_party_date'></div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;">
                                            Select Time
                                        </a>
                                        <strong><a id="ac_select_time" class="hidden" data-toggle="collapse" data-parent="#ac_accordion" href="#ac_collapseThree" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #000;"></span> EDIT</a></strong>
                                    </h4>
                                </div>
                                <div id="ac_collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div id="ac_time"></div>
                                        <div id="ac_hours"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default hidden" id="ac_meal_options">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;">
                                            Meal options<strong><a id="ac_select_meal" class="hidden" data-toggle="collapse" data-parent="#ac_accordion" href="#ac_collapseFour" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color: #fff;"></span> EDIT</a></strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="ac_collapseFour" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group" id="ac_nonveg">
                                            <label>Non-vegetarian</label>
                                            <select class="nonveg"></select>
                                        </div>
                                        <div class="form-group" id="ac_alcohol">
                                            <label>Alcohol pairings</label>
                                            <select class="alcohol"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default" id="ac_add_options">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="javascript:" style="text-decoration: none;">
                                            Additional Options
                                            <a data-toggle="collapse" data-parent="#ac_accordion" href="#ac_collapseFive" id="ac_additional_options">
                                            </a>
                                        </a>
                                    </h4>
                                </div>
                                <div id="ac_collapseFive" class="panel-collapse collapse optional-fields">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="ac_specialRequests" placeholder="Enter special requests">
                                        </div>
                                        <div class="form-group">
                                            <label>Award points for offline reservation  <input type="checkbox"  id="ac_avard_point" value="1"></label>
                                        </div>
                                        <div class="form-group">
                                            <label style="color:#756554 !important;">Send Reservation Email  <input type="checkbox"  id="ac_reservation_email" value="1"></label>
                                        </div>
                                        <input type="hidden" value="" id="ac_reserv_date">
                                        <input type="hidden" value="" name="ac_booking_date" id="ac_booking_date">
                                        <input type="hidden" value="" id="ac_exp_id">
                                        <input type="hidden" value="" id="ac_price">
                                        <input type="hidden" value="" id="ac_price_non_veg">
                                        <input type="hidden" value="" id="ac_price_alcohol">
                                        <input type="hidden" value="" id="ac_address_keyword">
                                        <input type="hidden" value="" id="ac_addr">
                                        <input type="hidden" value="" id="ac_city">
                                        <input type="hidden" value="" id="vendor_name">

                                        <input type="hidden" value="" id="ac_last_reserv_date">
                                        <input type="hidden" value="" id="ac_last_reserv_time">
                                        <input type="hidden" value="" id="ac_last_reserv_outlet">
                                        <input type="hidden" value="" id="ac_last_reserv_party_size">
                                        <input type="hidden" value="" id="ac_res_id">

                                        <input type="hidden" value="" id="alacarte_reward_points">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="hidden" id="ac_cant_select_table">To check for immediate availability, please call our concierge.</span>
                            <p class="hidden" id="ac_cant_do_reserv1">You have an existing reservation that conflicts with this one. To modify or cancel your existing reservation please click</p>
                            <a class="btn btn-warning hidden" id="ac_brs_my_reserv" href="">View My Existing Reservations</a>
                            <p class="hidden" id="ac_cant_do_reserv2">If you have any queries please call our concierge desk.</p>
                            <a href="javascript:void(0)" class="btn btn-warning hidden" id='ac_select_table'>SELECT TABLE</a>
                            <p class="hidden cant_change" id="ac_cant_change_table">Please make a change to confirm. If no change is required, please click on cancel.</p>
                            <a href="javascript:void(0)" class="btn btn-warning hidden cant_change" id="ac_cancel"  data-dismiss="modal" aria-hidden="true">Cancel</a>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!--add Alacarte Modal-->


    <!--Cancel Modal -->
    <div style="z-index: 9999;" class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background: #EAB703 !important;">
                <div id="load_layer" class="cancel_loader">
                    <img src="/images/loading.gif">
                </div>
                <div class="modal-header" style="margin-top: -5px !important;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center" id="myModalLabel">Cancel Reservation</h4>
                </div>
                <div class="modal-body">
                    <div class="cancel_reserv_form">
                        <h4 class="panel-title" style="margin-bottom:20px;color:#756554!important;">
                            Are you sure you want to cancel this reservation? Once cancelled, it cannot be undone.
                        </h4>
                        <div class="text-center">
                            <a type="button" class="btn btn-warning" href="javascript:" id='cancel_current'>Yes, Cancel It</a>
                            <a type="button" class="btn btn-warning close" data-dismiss="modal" style="color:#000;text-shadow:0 0 0 #000">Nevermind</a>
                            <input type="hidden" name='reserv_id'>
                            <input type="hidden" value="" id="cancel_user_id" name="cancel_user_id">
                            <input type="hidden" value="user" id="added_by" name="added_by">
                        </div>
                    </div>
                </div>
                <div class="cancel_reserv_confirmation hide">
                    <h4 class="panel-title text-center" style="margin-bottom: 20px;color:#fff;">
                        We have received your cancel request.
                    </h4>
                    <div class="text-center">
                        <a  class="btn btn-warning close_modal" href="javascript:" data-dismiss="modal">Close This</a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!--Cancel Modal -->
@stop