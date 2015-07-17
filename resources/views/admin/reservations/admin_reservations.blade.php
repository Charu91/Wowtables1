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
    </style>
    <h1 class="cms-title text-center">Add / Modify Reservations</h1>
    <header class="page-header">
        <h2>Admin Reservations</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Admin Reservations</span></li>
            </ol>
        </div>
    </header>

    <section class="panel col-lg-12 col-lg-offset-0">

        <header class="panel-heading">
            <h2 class="panel-title">Admin Reservations</h2>
        </header>
        <div class="panel-body col-md-3 member-info-form">
            <p class="text-center"><strong>Add customer information:</strong></p>
            <form role="form">
                <div class="form-group email-field">
                    <input type="email" class="form-control" id="customerEmail" name="CustomerEmail" placeholder="Customer email">
                    <span class="show_in_input_yes"><img src="/assets/img/yes.png" alt="exist"/></span>
                    <span class="show_in_input_no"><img src="/assets/img/no.png" alt="Does not exist"/></span>
                    <span class="small-ajax-loader"></span>
                    <span id="email_error" class="error"></span>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="customerName" placeholder="Customer Name">
                    <span id="full_name_error" class="error"></span>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="customerNumber" placeholder="Phone Number">
                    <span id="phone_error" class="error"></span>
                </div>
                <div class="form-group">
                    <select name="cities" class='form-control populate' id="customerCity">
                        <option value="0">--Select--</option>
                        <?php foreach($cities as $key => $val){ ?>
                        <option value="<?php echo $key ;?>"><?php echo $val ;?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" id="add_member" class="btn btn-inverse btn-block hidden ">Add a New Member</button>
                </div>

                <input type="hidden" value="" id="user_id">
            </form>
        </div>
        <div class="panel-body col-lg-9">
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="#add-reser" data-toggle="tab">Add New Reservations</a></li>
                <li><a href="#modify-reser" data-toggle="tab" id="modif_reserv">Modify Reservations</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="add-reser">

                    <form role="form">
                        <div class="form-group">
                            <input id="admin_restaurant_search" name="admin_restaurant_search" type="text" class="form-control" placeholder="Search for a restaurant or an experience">
                            <span class="error" id="search_error"></span>
                            <span class="small-ajax-loader-search"></span>
                        </div>

                    </form>

                    <br/><br/>

                    <table class="table table-striped table-bordered add-booking-table" id="experiences">
                        <thead>
                        <tr>
                            <th>Experiences</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>

                <div class="tab-pane fade " id="modify-reser">
                    <div class="reservations-wrap">
                        <p class="lead wrap-title">Upcoming Reservations:</p>
                        <div id="upcomings_reservs"></div>
                        <hr/>
                        <p class="lead wrap-title" >Previous Reservations:</p>
                        <div id="pref_reservs"></div>
                    </div>
                    <button type="button" class="btn btn-default btn-lg btn-block" id="last_reserv" style="display: none;">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <span class="prev_res">PREVIOUS RESERVATIONS</span>
                        <img class="ajax_loader" style="display: none;" src="/images/loading.gif">
                    </button>
                </div>
            </div>
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
                                    <strong><a id="date_edit12"  data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;" id="myselect_date"></span> EDIT</a></strong>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
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