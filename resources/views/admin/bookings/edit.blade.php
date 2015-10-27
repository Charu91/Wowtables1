@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Edit Reservation</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Edit Reservation</span></li>
            </ol>
        </div>
    </header>
    <section class="panel col-lg-12">
        {!! Form::open(['route'=>['ReservationAttributesUpdate',$reservation_id],'method'=>'PUT','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}
        <header class="panel-heading clearfix">
            <!--<h2 class="panel-title">Edit Reservation</h2>-->
            <!--<div>
                <div class="form-group">
                    <label for="sel1">Select list:</label>
                    <select class="form-control" id="sel1">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select>
                </div>
            </div>-->

            <h2 class="panel-title pull-left">Edit Reservation</h2>
            <div class="pull-right">
                {!! Form::submit('Update',['class'=>'btn btn-primary']) !!}
            </div>
        </header>
        <?php //print_r($reservationDetailsAttr); die; ?>

        <div class="panel-body">
            <div class="form-group">
                {!! Form::label("alternate_id","AlternateId",['class'=>'col-md-1 control-label']) !!}
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="attributes[alternate_id]" name="attributes[alternate_id]" value="{{ $reservationDetailsAttr['attributes']['alternate_id'] or '' }}"/>
                </div>
                {!! Form::label("gift_card_id_from_reserv","Gift Card Id (From Reservation)",['class'=>'col-md-3 control-label']) !!}
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="attributes[gift_card_id_reserv]" name="attributes[gift_card_id_reserv]" value="{{ $reservationDetailsAttr['attributes']['gift_card_id_reserv'] or '' }}"/>
                </div>

                {!! Form::label("prepaid_amount","Prepaid Amount",['class'=>'col-md-1 control-label']) !!}
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="attributes[prepaid_amount]" name="attributes[prepaid_amount]" value="{{ $reservationDetailsAttr['attributes']['prepaid_amount'] or '' }}"/>
                </div>


            </div>
            <div class="form-group">
                {!! Form::label("attributes[cust_name]","Cust Name",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[cust_name]" name="attributes[cust_name]" value="{{ $reservationDetailsAttr['attributes']['cust_name'] or '' }}"/>
                </div>
                {!! Form::label("attributes[contact_no]","Contact No",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[contact_no]" name="attributes[contact_no]" value="{{ $reservationDetailsAttr['attributes']['contact_no'] or '' }}"/>
                </div>
                {!! Form::label("attributes[email]","Email",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[email]" name="attributes[email]" value="{{ $reservationDetailsAttr['attributes']['email'] or '' }}"/>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label("attributes[reserv_type]","Booking Type",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    {!!  Form::select('attributes[reserv_type]', $bookingTypeArr, $reservationDetailsAttr['attributes']['reserv_type'], array('id' => 'attributes[reserv_type]','class'=>'form-control')) !!}
                    <!--<input type="text" class="form-control" id="attributes[reserv_type]" name="attributes[reserv_type]" value="{{ $reservationDetailsAttr['attributes']['reserv_type'] or '' }}"/>-->
                </div>
                {!! Form::label("attributes[date]","Date Of Visit",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="reserv_date" name="attributes[date]" value="{{ $reservationDetailsAttr['attributes']['date'] or '' }}"/>
                </div>
                {!! Form::label("attributes[time]","Time",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="reserv_time" name="attributes[time]" value="{{ $reservationDetailsAttr['attributes']['time'] or '' }}"/>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label("attributes[experience]","Experience",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[experience]" name="attributes[experience]" value="{{ $reservationDetailsAttr['attributes']['experience'] or '' }}"/>
                </div>
                {!! Form::label("attributes[outlet]","Outlet",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="attributes[outlet]" name="attributes[outlet]" value="{{ $reservationDetailsAttr['attributes']['outlet'] or '' }}"/>
                </div>
                {!! Form::label("attributes[giu_membership_id]","GIU Membership Id",['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[giu_membership_id]" name="attributes[giu_membership_id]" value="{{ $reservationDetailsAttr['attributes']['giu_membership_id'] or '' }}"/>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("attributes[special_request]","Special Request",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <textarea class="form-control" name="attributes[special_request]" id="attributes[special_request]">{{ $reservationDetailsAttr['attributes']['special_request'] or '' }}</textarea>
                </div>
                {!! Form::label("attributes[admin_comments]","Admin Comments",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <textarea class="form-control" name="attributes[admin_comments]" id="attributes[admin_comments]">{{ $reservationDetailsAttr['attributes']['admin_comments'] or '' }}</textarea>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label("attributes[referral]","Referral",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[referral]" name="attributes[referral]" value="{{ $reservationDetailsAttr['attributes']['referral'] or '' }}"/>
                </div>
                {!! Form::label("attributes[gift_card_id]","Gift Card Id",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[gift_card_id]" name="attributes[gift_card_id]" value="{{ $reservationDetailsAttr['attributes']['gift_card_id'] or '' }}"/>
                </div>
                {!! Form::label("attributes[gift_card_value]","Gift Card Value",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[gift_card_value]" name="attributes[gift_card_value]" value="{{ $reservationDetailsAttr['attributes']['gift_card_value'] or '' }}"/>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label("attributes[api_added]","Api Added",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="attributes[api_added]" name="attributes[api_added]" value="{{ $reservationDetailsAttr['attributes']['api_added'] or '' }}"/>
                </div>
                {!! Form::label("attributes[loyalty_points_awarded]","Loyalty Points Awarded",['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-1">
                    <input type="text" class="form-control" id="attributes[loyalty_points_awarded]" name="attributes[loyalty_points_awarded]" value="{{ $reservationDetailsAttr['attributes']['loyalty_points_awarded'] or '' }}"/>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("attributes[no_of_people_booked]","No Of People Booked",['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-1">
                    <input type="text" class="form-control" id="attributes[no_of_people_booked]" name="attributes[no_of_people_booked]" value="{{ $reservationDetailsAttr['attributes']['no_of_people_booked'] or '' }}"/>
                </div>
                {!! Form::label("attributes[total_seated]","Total Seated",['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-1">
                    <input type="text" class="form-control" id="total_seated" name="attributes[total_seated]" value="{{ $reservationDetailsAttr['attributes']['total_seated'] or '' }}"/>
                </div>

            </div>
            <hr/>
            <h4>Actual Seaters</h4>
            <div class="form-group">
                {!! Form::label("attributes[actual_experience_takers]","Actual Experience Takers",['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="text" class="form-control" id="actual_experience_takers" name="attributes[actual_experience_takers]" value="{{ $reservationDetailsAttr['attributes']['actual_experience_takers'] or '' }}"/>
                        <!--<span class="input-group-addon" id="update-prices" style="cursor: pointer;">Update</span>-->
                    </div>
                </div>
                {!! Form::label("attributes[actual_alacarte_takers]","Actual Alacarte Takers",['class'=>'col-sm-1 control-label']) !!}
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="text" class="form-control" id="actual_alacarte_takers" name="attributes[actual_alacarte_takers]" value="{{ $reservationDetailsAttr['attributes']['actual_alacarte_takers'] or '' }}"/>
                        <!--<span class="input-group-addon" id="update-prices" style="cursor: pointer;">Update</span>-->
                    </div>
                </div>
                <?php //print_r($pricingDetails->addon_names);die; ?>
                @if(isset($pricingDetails->addon_names))
                    <div class="col-sm-5">
                    @foreach($pricingDetails->addon_names as $addon_id => $value)
                        <div class="row">
                        {!! Form::label("attributes[addon_$addon_id]",$value[0],['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="text" class="form-control" id="addon_{!! $addon_id !!}" name="attributes[actual_addon_takers][{!! $addon_id !!}]" value="{!! $value[1] !!}" />
                            </div>
                        </div>
                        </div>
                    @endforeach
                    </div>
                @endif
                <div class="col-sm-2">
                    <input type="button" class="btn btn-primary" id="update-prices" data-reserv-id="{!! $reservation_id !!}" value="Update Prices" />
                </div>
            </div>
            <hr/>
            <h4>Billing Details</h4>
            <div class="form-group">
                {!! Form::label("attributes[total_billings]","Total Billing",['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="total_billing" name="attributes[total_billings]" readonly="readonly" value="{{ $reservationDetailsAttr['attributes']['total_billings'] or '' }}" />
                </div>
                {!! Form::label("attributes[total_commission]","Total Commission",['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="total_commission" name="attributes[total_commission]" readonly="readonly" value="{{ $reservationDetailsAttr['attributes']['total_commission'] or '' }}" />
                </div>
            </div>
            <br/>
            <hr/>
            <section class="panel col-lg-6" style="min-height: 110px;">
                <h5><strong>Alacarte</strong></h5>
            @if($reservationDetailsAttr['attributes']['reserv_type'] == "Alacarte")
                 @include('admin.bookings.alacarte')
            @else
                <p>No information</p>
            @endif
            </section>
            <section class="panel col-lg-6" style="min-height: 110px;">
                <h5><strong>Experience</strong></h5>
            @if($reservationDetailsAttr['attributes']['reserv_type'] == "Experience")
                @include('admin.bookings.experience')
            @else
                <p>No information</p>
            @endif
            </section>
        </div>
        <footer class="panel-footer">
            @if(isset($pricingDetails->addon_names))
            <input type="hidden" id="addons_details" name="addons_details" value='<?php echo json_encode($pricingDetails->addon_names); ?>'>
            @endif
            {!! Form::submit('Update',['class'=>'btn btn-primary']) !!}
        </footer>
        {!! Form::close() !!}
    </section>
@stop