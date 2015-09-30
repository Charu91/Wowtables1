@extends('templates.admin_layout')

@section('content')
    <style type="text/css">
        .scrollable-menu {
            height: auto;
            max-height: 270px;
            overflow-x: hidden;
        }
    </style>
<header class="page-header">
    <h2>Bookings List</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/admin/dashboard">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Bookings List</span></li>
        </ol>
    </div>
</header>

<div class="tabs tabs-primary">
    <ul class="nav nav-tabs nav-justified">
        <li class="active">
            <a href="#unconfirmed_bookings" data-toggle="tab" class="text-center">Unconfirmed Bookings</a>
        </li>
        <li>
            <a href="#post_bookings" data-toggle="tab" class="text-center">Post Process Bookings</a>
        </li>
        <li><a href="#allbookings" data-toggle="tab" class="text-center">All Bookings</a></li>
        <li><a href="#todaysbookings" data-toggle="tab" class="text-center">Today's Bookings</a></li>
    </ul>
    <div class="tab-content">
        <div id="unconfirmed_bookings" class="tab-pane active mt-lg">
            <div class="panel-body">
                <table class="table table-striped table-responsive mb-none" id="unbookings">
                    <thead>
                    <tr>
                        <th class="no-sort"></th>
                        <th>Cust Name</th>
                        <th>Date to Visit</th>
                        <th>Time</th>
                        <th>Experience</th>
                        <th>Venue Name</th>
                        <th>City</th>
                        <th>Email Ids</th>
                        <th>Contact</th>
                        <th>No of People</th>
                        <th>Outlet</th>
                        <th>Special Request</th>
                        <th>Gift Card Id</th>
                        <th>Status</th>
                        <th>Zoho Booking Cancelled</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php //print_r($bookings);die; ?>
                    @foreach($un_bookings as $un_booking)
                        <tr>
                            <td>
                                <!--<div class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                      <li><a href="#">Edit</a></li>
                                      <li><a href="#">Delete</a></li>
                                      <li><a href="#">Duplicate</a></li>
                                      <li><a href="#">Print</a></li>
                                      <li><a href="#">View Record</a></li>
                                      <li class="divider"></li>
                                      <li><a href="#">Send Customer Confirmation</a></li>
                                      <li><a href="#">Send Reservation Email</a></li>
                                      <li><a href="#">Send Cancellation/Change Email</a></li>
                                      <li><a href="#">Custom Customer Email</a></li>
                                      <li><a href="#">Send Restaurant Email</a></li>
                                    </ul>
                                </div>-->

                                <div class="btn-group">
                                  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu scrollable-menu">
                                    <li>{!! link_to_route('BookingEdit','Edit',$un_booking->id,['target'=>'_blank']) !!}</li>
                                    <!--<li><a href="#">Delete</a></li>
                                    <li><a href="#">Duplicate</a></li>
                                    <li><a href="#">Print</a></li>
                                    <li><a href="#">View Record</a></li>-->
                                    <li class="divider"></li>
                                    <li>
                                        {!! link_to_route('SendConfirmation','Send Customer Confirmation',$un_booking->id,null) !!}
                                    </li>
                                    <li class="divider"></li>
                                    <!--<li><a href="#">Send Reservation Email</a></li>-->
                                    <li>
                                        <!--<a href="#">Send Cancellation/Change Email</a>-->
                                        {!! link_to_route('ReservationCancel','Send Cancel Info to Restaurant',$un_booking->id,null) !!}
                                    </li>
                                    <li>{!! link_to_route('ReservationChange','Send Change Info to Restaurant',$un_booking->id,null) !!}</li>
                                    <li>{!! link_to_route('ReservationSend','Send Reservation Info To Restaurant',$un_booking->id,null) !!}</li>
                                    <!--<li><a href="#">Custom Customer Email</a></li>
                                    <li><a href="#">Send Restaurant Email</a></li>-->

                                  </ul>
                                </div>


                            </td>
                            <td>{!! $un_booking->cust_name !!}</td>
                            <td>{!! $un_booking->bdate !!}</td>
                            <td>{!! $un_booking->btime !!}</td>
                            <td>{!! $un_booking->name !!}</td>
                            <td>{!! $un_booking->restaurant_name !!}</td>
                            <td>{!! $un_booking->city !!}</td>
                            <td><a href="mailto:{!! $un_booking->email !!}">{!! $un_booking->email !!}</a></td>
                            <td>{!! $un_booking->phone_no !!}</td>
                            <td>{!! $un_booking->no_of_persons !!}</td>
                            <td>{!! $un_booking->outlet !!}</td>

                            <td>{!! $un_booking->special_request !!}</td>
                            <td>{!! $un_booking->gift_card_id !!}</td>
                            <td>
                                <span class="label label-warning">{!! $un_booking->reserv_status !!} by {!! $un_booking->lastmodified !!}</span>
                                <div class="btn-group pull-right">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            {!! $un_booking->reserv_status !!}
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            @foreach($un_booking->statusArr as $key=>$val)
                                                <li>
                                                    {!! link_to_route('ChangeStatus',$val,null,['class'=>'change-status','data-reserv-id'=>$un_booking->id,'data-reserv-status'=>$key,'data-reserv-type'=>$un_booking->reserv_type]) !!}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($un_booking->zoho_cancelled == 1)
                                    <input type="checkbox" class="checkbox" id="zoho_booking_cancelled" name="attributes[zoho_booking_cancelled]" data-reserv-id="{!! $un_booking->id !!}" data-reserv-type="{!! $un_booking->reserv_type !!}" />
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="post_bookings" class="tab-pane mt-lg">
            <div class="panel-body">
                <table class="table table-striped table-responsive mb-none" id="postbookings">
                    <thead>
                    <tr>
                        <th class="no-sort"></th>
                        <th>Cust Name</th>
                        <th>Date to Visit</th>
                        <th>Time</th>
                        <th>Experience</th>
                        <th>Venue Name</th>
                        <th>City</th>
                        <th>Email Ids</th>
                        <th>Contact</th>
                        <th>No of People</th>
                        <th>Outlet</th>
                        <th>Special Request</th>
                        <th>Gift Card Id</th>
                        <th>Status</th>
                        <th>Zoho Booking Cancelled</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php //print_r($bookings);die; ?>
                    @foreach($post_bookings as $post_booking)
                        <tr>
                            <td>
                                <!--<div class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                      <li><a href="#">Edit</a></li>
                                      <li><a href="#">Delete</a></li>
                                      <li><a href="#">Duplicate</a></li>
                                      <li><a href="#">Print</a></li>
                                      <li><a href="#">View Record</a></li>
                                      <li class="divider"></li>
                                      <li><a href="#">Send Customer Confirmation</a></li>
                                      <li><a href="#">Send Reservation Email</a></li>
                                      <li><a href="#">Send Cancellation/Change Email</a></li>
                                      <li><a href="#">Custom Customer Email</a></li>
                                      <li><a href="#">Send Restaurant Email</a></li>
                                    </ul>
                                </div>-->

                                <div class="btn-group">
                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu scrollable-menu">
                                        <li>{!! link_to_route('BookingEdit','Edit',$post_booking->id,['target'=>'_blank']) !!}</li>
                                        <!--<li><a href="#">Delete</a></li>
                                        <li><a href="#">Duplicate</a></li>
                                        <li><a href="#">Print</a></li>
                                        <li><a href="#">View Record</a></li>-->
                                        <li class="divider"></li>
                                        <li>
                                            {!! link_to_route('SendConfirmation','Send Customer Confirmation',$post_booking->id,null) !!}
                                        </li>
                                        <li class="divider"></li>
                                        <!--<li><a href="#">Send Reservation Email</a></li>-->
                                        <li>
                                            <!--<a href="#">Send Cancellation/Change Email</a>-->
                                            {!! link_to_route('ReservationCancel','Send Cancel Info to Restaurant',$post_booking->id,null) !!}
                                        </li>
                                        <li>{!! link_to_route('ReservationChange','Send Change Info to Restaurant',$post_booking->id,null) !!}</li>
                                        <li>{!! link_to_route('ReservationSend','Send Reservation Info To Restaurant',$post_booking->id,null) !!}</li>
                                        <!--<li><a href="#">Custom Customer Email</a></li>
                                        <li><a href="#">Send Restaurant Email</a></li>-->

                                    </ul>
                                </div>


                            </td>
                            <td>{!! $post_booking->cust_name !!}</td>
                            <td>{!! $post_booking->bdate !!}</td>
                            <td>{!! $post_booking->btime !!}</td>
                            <td>{!! $post_booking->name !!}</td>
                            <td>{!! $post_booking->restaurant_name !!}</td>
                            <td>{!! $post_booking->city !!}</td>
                            <td><a href="mailto:{!! $post_booking->email !!}">{!! $post_booking->email !!}</a></td>
                            <td>{!! $post_booking->phone_no !!}</td>
                            <td>{!! $post_booking->no_of_persons !!}</td>
                            <td>{!! $post_booking->outlet !!}</td>

                            <td>{!! $post_booking->special_request !!}</td>
                            <td>{!! $post_booking->gift_card_id !!}</td>
                            <td>
                                <span class="label label-warning">{!! $post_booking->reserv_status !!} by {!! $post_booking->lastmodified !!}</span>
                                <div class="btn-group pull-right">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            {!! $post_booking->reserv_status !!}
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            @foreach($post_booking->statusArr as $key=>$val)
                                                <li>
                                                    {!! link_to_route('ChangeStatus',$val,null,['class'=>'change-status','data-reserv-id'=>$post_booking->id,'data-reserv-status'=>$key,'data-reserv-type'=>$post_booking->reserv_type]) !!}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($post_booking->zoho_cancelled == 1)
                                    <input type="checkbox" class="checkbox" id="zoho_booking_cancelled" name="attributes[zoho_booking_cancelled]" data-reserv-id="{!! $post_booking->id !!}" data-reserv-type="{!! $post_booking->reserv_type !!}" />
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="allbookings" class="tab-pane mt-lg">
            <div class="panel-body">
                <table class="table table-striped table-responsive mb-none" id="all_bookings">
                    <thead>
                    <tr>
                        <th class="no-sort"></th>
                        <th>Cust Name</th>
                        <th>Date to Visit</th>
                        <th>Time</th>
                        <th>Experience</th>
                        <th>Venue Name</th>
                        <th>City</th>
                        <th>Email Ids</th>
                        <th>Contact</th>
                        <th>No of People</th>
                        <th>Outlet</th>
                        <th>Special Request</th>
                        <th>Gift Card Id</th>
                        <th>Status</th>
                        <!--<th>Order Completed</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php //print_r($bookings);die; ?>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <!--<div class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                      <li><a href="#">Edit</a></li>
                                      <li><a href="#">Delete</a></li>
                                      <li><a href="#">Duplicate</a></li>
                                      <li><a href="#">Print</a></li>
                                      <li><a href="#">View Record</a></li>
                                      <li class="divider"></li>
                                      <li><a href="#">Send Customer Confirmation</a></li>
                                      <li><a href="#">Send Reservation Email</a></li>
                                      <li><a href="#">Send Cancellation/Change Email</a></li>
                                      <li><a href="#">Custom Customer Email</a></li>
                                      <li><a href="#">Send Restaurant Email</a></li>
                                    </ul>
                                </div>-->

                                <div class="btn-group">
                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu scrollable-menu">
                                        <li>{!! link_to_route('BookingEdit','Edit',$booking->id,['target'=>'_blank']) !!}</li>
                                        <!--<li><a href="#">Delete</a></li>
                                        <li><a href="#">Duplicate</a></li>
                                        <li><a href="#">Print</a></li>
                                        <li><a href="#">View Record</a></li>-->
                                        <li class="divider"></li>
                                        <li>
                                            {!! link_to_route('SendConfirmation','Send Customer Confirmation',$booking->id,null) !!}
                                        </li>
                                        <li class="divider"></li>
                                        <!--<li><a href="#">Send Reservation Email</a></li>-->
                                        <li>
                                            <!--<a href="#">Send Cancellation/Change Email</a>-->
                                            {!! link_to_route('ReservationCancel','Send Cancel Info to Restaurant',$booking->id,null) !!}
                                        </li>
                                        <li>{!! link_to_route('ReservationChange','Send Change Info to Restaurant',$booking->id,null) !!}</li>
                                        <li>{!! link_to_route('ReservationSend','Send Reservation Info To Restaurant',$booking->id,null) !!}</li>
                                        <!--<li><a href="#">Custom Customer Email</a></li>
                                        <li><a href="#">Send Restaurant Email</a></li>-->

                                    </ul>
                                </div>


                            </td>
                            <td>{!! $booking->cust_name !!}</td>
                            <td>{!! $booking->bdate !!}</td>
                            <td>{!! $booking->btime !!}</td>
                            <td>{!! $booking->name !!}</td>
                            <td>{!! $booking->restaurant_name !!}</td>
                            <td>{!! $booking->city !!}</td>
                            <td><a href="mailto:{!! $booking->email !!}">{!! $booking->email !!}</a></td>
                            <td>{!! $booking->phone_no !!}</td>
                            <td>{!! $booking->no_of_persons !!}</td>
                            <td>{!! $booking->outlet !!}</td>
                            <td>{!! $booking->special_request !!}</td>
                            <td>{!! $booking->gift_card_id !!}</td>
                            <td>
                                <span class="label label-warning">{!! $booking->reserv_status !!} by {!! $booking->lastmodified !!}</span>
                                <div class="btn-group pull-right">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            {!! $booking->reserv_status !!}
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            @foreach($booking->statusArr as $key=>$val)
                                                <li>
                                                    {!! link_to_route('ChangeStatus',$val,null,['class'=>'change-status','data-reserv-id'=>$booking->id,'data-reserv-status'=>$key,'data-reserv-type'=>$booking->reserv_type]) !!}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <!--<td>
                                @if($booking->order_completed == 1)
                                    <input type="checkbox" class="checkbox" id="order_completed" name="attributes[order_completed]" data-reserv-id="{!! $booking->id !!}" checked />
                                @else
                                    <input type="checkbox" class="checkbox" id="order_completed" name="attributes[order_completed]" data-reserv-id="{!! $booking->id !!}" />
                                @endif
                            </td>-->

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="todaysbookings" class="tab-pane mt-lg">
            <div class="panel-body">
                @if(!empty($todaysbookings))
                <table class="table table-striped table-responsive mb-none" id="todaybookings">
                    <thead>
                    <tr>
                        <th class="no-sort"></th>
                        <th>Cust Name</th>
                        <th>Date to Visit</th>
                        <th>Time</th>
                        <th>Experience</th>
                        <th>Venue Name</th>
                        <th>City</th>
                        <th>Email Ids</th>
                        <th>Contact</th>
                        <th>No of People</th>
                        <th>Outlet</th>
                        <th>Special Request</th>
                        <th>Gift Card Id</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php //print_r($bookings);die; ?>
                    @foreach($todaysbookings as $todaysbooking)
                        <tr>
                            <td>
                                <!--<div class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                      <li><a href="#">Edit</a></li>
                                      <li><a href="#">Delete</a></li>
                                      <li><a href="#">Duplicate</a></li>
                                      <li><a href="#">Print</a></li>
                                      <li><a href="#">View Record</a></li>
                                      <li class="divider"></li>
                                      <li><a href="#">Send Customer Confirmation</a></li>
                                      <li><a href="#">Send Reservation Email</a></li>
                                      <li><a href="#">Send Cancellation/Change Email</a></li>
                                      <li><a href="#">Custom Customer Email</a></li>
                                      <li><a href="#">Send Restaurant Email</a></li>
                                    </ul>
                                </div>-->

                                <div class="btn-group">
                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu scrollable-menu">
                                        <li>{!! link_to_route('BookingEdit','Edit',$todaysbooking->id,['target'=>'_blank']) !!}</li>
                                        <!--<li><a href="#">Delete</a></li>
                                        <li><a href="#">Duplicate</a></li>
                                        <li><a href="#">Print</a></li>
                                        <li><a href="#">View Record</a></li>-->
                                        <li class="divider"></li>
                                        <li>
                                            {!! link_to_route('SendConfirmation','Send Customer Confirmation',$todaysbooking->id,null) !!}
                                        </li>
                                        <li class="divider"></li>
                                        <!--<li><a href="#">Send Reservation Email</a></li>-->
                                        <li>
                                            <!--<a href="#">Send Cancellation/Change Email</a>-->
                                            {!! link_to_route('ReservationCancel','Send Cancel Info to Restaurant',$todaysbooking->id,null) !!}
                                        </li>
                                        <li>{!! link_to_route('ReservationChange','Send Change Info to Restaurant',$todaysbooking->id,null) !!}</li>
                                        <li>{!! link_to_route('ReservationSend','Send Reservation Info To Restaurant',$todaysbooking->id,null) !!}</li>
                                        <!--<li><a href="#">Custom Customer Email</a></li>
                                        <li><a href="#">Send Restaurant Email</a></li>-->

                                    </ul>
                                </div>


                            </td>
                            <td>{!! $todaysbooking->cust_name !!}</td>
                            <td>{!! $todaysbooking->bdate !!}</td>
                            <td>{!! $todaysbooking->btime !!}</td>
                            <td>{!! $todaysbooking->name !!}</td>
                            <td>{!! $todaysbooking->restaurant_name !!}</td>
                            <td>{!! $todaysbooking->city !!}</td>
                            <td><a href="mailto:{!! $todaysbooking->email !!}">{!! $todaysbooking->email !!}</a></td>
                            <td>{!! $todaysbooking->phone_no !!}</td>
                            <td>{!! $todaysbooking->no_of_persons !!}</td>
                            <td>{!! $todaysbooking->outlet !!}</td>
                            <td>{!! $todaysbooking->special_request !!}</td>
                            <td>{!! $todaysbooking->gift_card_id !!}</td>
                            <td>
                                <span class="label label-warning">{!! $todaysbooking->reserv_status !!} by {!! $todaysbooking->lastmodified !!}</span>
                                <div class="btn-group pull-right">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            {!! $todaysbooking->reserv_status !!}
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            @foreach($todaysbooking->statusArr as $key=>$val)
                                                <li>
                                                    {!! link_to_route('ChangeStatus',$val,null,['class'=>'change-status','data-reserv-id'=>$todaysbooking->id,'data-reserv-status'=>$key,'data-reserv-type'=>$todaysbooking->reserv_type]) !!}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </td>


                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <h4>No Records</h4>
                @endif
            </div>
        </div>
    </div>
</div>
    <!-- Modal -->
    <div id="adminComments" class="modal fade" role="dialog" style="top: 20%;left: 20%;outline: none;">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Admin Comments</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route'=>['ChangeStatus'],'method'=>'POST','class'=>'form-horizontal admin-comments','novalidate'=>'novalidate','id'=>'admin_comments']) !!}
                    <div class="form-group">
                    {!! Form::label("attributes[admin_comments]","Admin Comments",['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-8">
                        <textarea class="form-control" name="attributes[admin_comments]" id="admin_comments"></textarea>
                    </div>
                    </div>
                    <input type="hidden" name="reserv_id" id="reserv_id">
                    <input type="hidden" name="reserv_status" id="reserv_status">
                    <input type="hidden" name="reserv_type" id="reserv_type">
                    {!! Form::submit('Save',['class'=>'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
                <!--<div class="modal-footer">
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button
                    <button type="button" class="btn btn-default admin-save" data-dismiss="modal" id="adminSave" data-reserv-id="">Save</button>
                </div>-->
            </div>

        </div>
    </div>
@stop
