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
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <div class="btn-group btn-group-md" role="group" aria-label="...">
                    {!! link_to_route('Unconfirmed','Unconfirmed Bookings',array(),['class'=>'btn btn-primary']) !!}
                    {!! link_to_route('Missing','Missing Attendees',array(),['class'=>'btn btn-primary']) !!}
                    {!! link_to_route('All','All Bookings',array(),['class'=>'btn btn-primary']) !!}
                    {!! link_to_route('Today','Today Bookings',array(),['class'=>'btn btn-primary active']) !!}
                </div>
            </div>
        </div>
    </div>


        <div id="todaysbookings" class="mt-lg">
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
                        <th>Contact</th>
                        <th>No of People</th>
                        <th>Outlet</th>
                        <th>Special Request</th>
                        <th>Gift Card Id</th>
                        <th>Status</th>
                        <th>Email Ids</th>
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
                            <td><a href="mailto:{!! $todaysbooking->email !!}">{!! $todaysbooking->email !!}</a></td>


                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <h4>No Records</h4>
                @endif
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
