@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Edit Experience Location</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Experience Locations</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right">
                <i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    {!! Form::model($productLocationDetails,['route'=>['AdminExperienceLocationsUpdate',$productLocationDetails['experience_location_id']],'method'=>'PUT','novalidate'=>'novalidate']) !!}

    <div class="tabs tabs-primary">
        <ul class="nav nav-tabs nav-justified">
            <li class="active">
                <a href="#basic_details" data-toggle="tab" class="text-center">Basic Details</a>
            </li>
            <li>
                <a href="#schedule_tab" data-toggle="tab" class="text-center">Scheduling</a>
            </li>
            <li>
                <a href="#block_dates" data-toggle="tab" class="text-center">Block Dates</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    <label for="experience_id" class="col-sm-3 control-label">Select Experience <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('experience_id',$experiences_list,$productLocationDetails['product_id'],['id'=>'loc_exp','class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="location_id" class="col-sm-3 control-label">Select Restaurant Location <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('restaurant_location_id[]',$restaurant_locations_list,$productLocations,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="descriptive_title" class="col-sm-3 control-label">Descriptive Title<span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('descriptive_title',$productLocationDetails['experience_location_descriptive_title'],['class'=>'form-control', 'required'=>'required']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="show_status" class="col-sm-3 control-label">Show Status<span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('show_status',['show_in_all'=>'Show in all','hide_in_mobile'=>'Hide in mobile','hide_in_website'=>'Hide in website'],$productLocationDetails['show_status'],['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="schedule_tab" class="tab-pane mt-lg">
                {{--@include('partials.forms.schedule_limits')--}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="limits[min_people_per_reservation]" class="col-sm-6 control-label">Minimum People Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('limits[min_people_per_reservation]',$productLocationsLimits->min_people_per_reservation,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="limits[max_people_per_reservation]" class="col-sm-6 control-label">Maximum People Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('limits[max_people_per_reservation]',$productLocationsLimits->max_people_per_reservation,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="limits[max_reservations_per_time_slot]" class="col-sm-6 control-label">Default Max Tables Per Time Slot </label>
                                <div class="col-sm-6">
                                    {!! Form::text('limits[max_reservations_per_time_slot]',$productLocationsLimits->max_reservations_per_time_slot,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="limits[min_people_increments_per_reservation]" class="col-sm-6 control-label">Min People Increments Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('limits[min_people_increments_per_reservation]',$productLocationsLimits->min_people_increments,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="limits[max_people_per_day]" class="col-sm-6 control-label">Maximum Tables Per Day </label>
                                <div class="col-sm-6">
                                    {!! Form::text('limits[max_people_per_day]',$productLocationsLimits->max_reservations_per_day,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="limits[minimum_reservation_time_buffer]" class="col-sm-6 control-label">Min Advance Reservation Time (hrs) </label>
                                <div class="col-sm-6">
                                    {!! Form::text('limits[minimum_reservation_time_buffer]',$productLocationsLimits->minimum_reservation_time_buffer,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="limits[maximum_reservation_time_buffer]" class="col-sm-6 control-label">Max Advance Reservation Time (hrs) </label>
                                <div class="col-sm-6">
                                    {!! Form::text('limits[maximum_reservation_time_buffer]',$productLocationsLimits->maximum_reservation_time_buffer,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="limits[max_reservations_per_day]" class="col-sm-6 control-label">Max Reservations per day <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('limits[max_reservations_per_day]',$productLocationsLimits->max_people_per_day,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if( ! empty($availableSchedules) )
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">All Schedules</h2>
                        </header>
                        <div  class="panel-body">
                            <div class="table-responsive">
                                <table  class="table table-bordered mb-none">
                                    <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Time</th>
                                        <th>Monday</th>
                                        <th>Tuesday</th>
                                        <th>Wednesday</th>
                                        <th>Thursday</th>
                                        <th>Friday</th>
                                        <th>Saturday</th>
                                        <th>Sunday</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($availableSchedules as $key => $slot)
                                        <tr>
                                            <td>
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                    <tr>
                                                        <td><a id="selectrow" class="btn btn-xs btn-success select-all">Select All</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><a class="btn btn-xs btn-danger select-none">Deselect</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                    <tr>
                                                        <td>{{ $slot['time'] }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="padding:0;">
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sch</td>
                                                            @if( in_array($slot['mon']['schedule_id'],$schedules) )
                                                                <td>{!! Form::checkbox('schedules['.$slot['mon']['schedule_id'].'][id]',$slot['mon']['schedule_id'],true)!!}</td>
                                                                {!! Form::hidden('schedules['.$slot['mon']['schedule_id'].'][time]',$slot['time']) !!}
                                                                {!! Form::hidden('schedules['.$slot['mon']['schedule_id'].'][day]','mon') !!}
                                                            @else
                                                                <td>{!! Form::checkbox('schedules['.$slot['mon']['schedule_id'].'][id]',$slot['mon']['schedule_id'],false)!!}</td>
                                                                {{--<td>{!! Form::checkbox('schedules['.$slot['mon']['schedule_id'].'][id]',$slot['mon']['schedule_id'],false) !!}</td>--}}
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="padding:0;">
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sch</td>
                                                            @if( in_array($slot['tue']['schedule_id'],$schedules) )
                                                                <td>{!! Form::checkbox('schedules['.$slot['tue']['schedule_id'].'][id]',$slot['tue']['schedule_id'],true)!!}</td>
                                                                {!! Form::hidden('schedules['.$slot['tue']['schedule_id'].'][time]',$slot['time']) !!}
                                                                {!! Form::hidden('schedules['.$slot['tue']['schedule_id'].'][day]','tue') !!}
                                                            @else
                                                                <td>{!! Form::checkbox('schedules['.$slot['tue']['schedule_id'].'][id]',$slot['tue']['schedule_id'],false)!!}</td>
                                                                {{--<td>{!! Form::checkbox('schedules['.$slot['tue']['schedule_id'].'][id]',$slot['tue']['schedule_id'],false) !!}</td>--}}
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="padding:0;">
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sch</td>
                                                            @if( in_array($slot['wed']['schedule_id'],$schedules) )
                                                                <td>{!! Form::checkbox('schedules['.$slot['wed']['schedule_id'].'][id]',$slot['wed']['schedule_id'],true)!!}</td>
                                                                {!! Form::hidden('schedules['.$slot['wed']['schedule_id'].'][time]',$slot['time']) !!}
                                                                {!! Form::hidden('schedules['.$slot['wed']['schedule_id'].'][day]','wed') !!}
                                                            @else
                                                                <td>{!! Form::checkbox('schedules['.$slot['wed']['schedule_id'].'][id]',$slot['wed']['schedule_id'],false)!!}</td>
                                                                {{--<td>{!! Form::checkbox('schedules['.$slot['wed']['schedule_id'].'][id]',$slot['wed']['schedule_id'],false) !!}</td>--}}
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="padding:0;">
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sch</td>
                                                            @if( in_array($slot['thu']['schedule_id'],$schedules) )
                                                                <td>{!! Form::checkbox('schedules['.$slot['thu']['schedule_id'].'][id]',$slot['thu']['schedule_id'],true)!!}</td>
                                                                {!! Form::hidden('schedules['.$slot['thu']['schedule_id'].'][time]',$slot['time']) !!}
                                                                {!! Form::hidden('schedules['.$slot['thu']['schedule_id'].'][day]','thu') !!}
                                                            @else
                                                                <td>{!! Form::checkbox('schedules['.$slot['thu']['schedule_id'].'][id]',$slot['thu']['schedule_id'],false)!!}</td>
                                                                {{--<td>{!! Form::checkbox('schedules['.$slot['thu']['schedule_id'].'][id]',$slot['thu']['schedule_id'],false) !!}</td>--}}
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="padding:0;">
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sch</td>
                                                            @if( in_array($slot['fri']['schedule_id'],$schedules) )
                                                                <td>{!! Form::checkbox('schedules['.$slot['fri']['schedule_id'].'][id]',$slot['fri']['schedule_id'],true)!!}</td>
                                                                {!! Form::hidden('schedules['.$slot['fri']['schedule_id'].'][time]',$slot['time']) !!}
                                                                {!! Form::hidden('schedules['.$slot['fri']['schedule_id'].'][day]','fri') !!}
                                                            @else
                                                                <td>{!! Form::checkbox('schedules['.$slot['fri']['schedule_id'].'][id]',$slot['fri']['schedule_id'],false)!!}</td>
                                                                {{--<td>{!! Form::checkbox('schedules['.$slot['fri']['schedule_id'].'][id]',$slot['fri']['schedule_id'],false) !!}</td>--}}
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="padding:0;">
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sch</td>
                                                            @if( in_array($slot['sat']['schedule_id'],$schedules) )
                                                                <td>{!! Form::checkbox('schedules['.$slot['sat']['schedule_id'].'][id]',$slot['sat']['schedule_id'],true)!!}</td>
                                                                {!! Form::hidden('schedules['.$slot['sat']['schedule_id'].'][time]',$slot['time']) !!}
                                                                {!! Form::hidden('schedules['.$slot['sat']['schedule_id'].'][day]','sat') !!}
                                                            @else
                                                                <td>{!! Form::checkbox('schedules['.$slot['sat']['schedule_id'].'][id]',$slot['sat']['schedule_id'],false)!!}</td>
                                                                {{--<td>{!! Form::checkbox('schedules['.$slot['sat']['schedule_id'].'][id]',$slot['sat']['schedule_id'],false) !!}</td>--}}
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="padding:0;">
                                                <table  class="table table-bordered mb-none">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sch</td>
                                                            @if( in_array($slot['sun']['schedule_id'],$schedules) )
                                                                <td>{!! Form::checkbox('schedules['.$slot['sun']['schedule_id'].'][id]',$slot['sun']['schedule_id'],true)!!}</td>
                                                                {!! Form::hidden('schedules['.$slot['sun']['schedule_id'].'][time]',$slot['time']) !!}
                                                                {!! Form::hidden('schedules['.$slot['sun']['schedule_id'].'][day]','sun') !!}
                                                            @else
                                                                <td>{!! Form::checkbox('schedules['.$slot['sun']['schedule_id'].'][id]',$slot['sun']['schedule_id'],false)!!}</td>
                                                                {{--<td>{!! Form::checkbox('schedules['.$slot['sun']['schedule_id'].'][id]',$slot['sun']['schedule_id'],false) !!}</td>--}}
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
            <div id="block_dates" class="tab-pane mt-lg">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">Block Dates</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            @if(!empty($productLocationBlockDates))
                                @foreach($productLocationBlockDates as $productLocationBlockDate)
                                    <div class="col-lg-4 mb-sm block-date-div">
                                        <div class="col-lg-10"><div class="form-group">
                                                <label class="col-sm-4 control-label" for="block_dates[]">Dates </label>
                                                <div class="col-sm-8"><input type="text" class="form-control block-date-picker" name="block_dates[]" value="{{$productLocationBlockDate->block_date}}"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <a class="btn btn-danger delete-block-date-div">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="block-date-div"></div>
                            @endif
                        </div>
                        <div class="panel-footer">
                            <a id="addNewBlockDateBtn" class="btn btn-primary">Add Block Date</a>
                        </div>
                    </div>
                </section>
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">Time Range Limits</h2>
                    </header>
                    <div class="panel-body">
                        <table id="blockTimeRangeTable"  class="table table-bordered mb-none">
                            <tr>
                                <th>Limit By</th>
                                <th>Select Day/Date</th>
                                <th>Full Day</th>
                                <th>From Time</th>
                                <th>To Time</th>
                                <th>Max Covers Limit</th>
                                <th>Max Table Limit</th>
                            </tr>
                            <tbody>

                            @if(!empty($productLocationBlockTimeLimits))
                                {{!$block_time_increment = 1}}
                                @foreach($productLocationBlockTimeLimits as $productLocationBlockTimeLimit)
                                    <tr>
                                        <td>
                                            <select name="reset_time_range_limits[{{$block_time_increment}}][limit_by]" class="form-control time-range-limit-by">
                                                <option {{$productLocationBlockTimeLimit->limit_by == "Day" ? "selected = selected" : ""}} value="Day">Day</option>
                                                <option {{$productLocationBlockTimeLimit->limit_by == "Date" ? "selected = selected" : ""}} value="Date">Date</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if($productLocationBlockTimeLimit->limit_by == "Day")
                                                <select name="reset_time_range_limits[{{$block_time_increment}}][day]" class="form-control block-time-range-day-picker">
                                                    <option {{$productLocationBlockTimeLimit->day == "mon" ? "selected = selected" : ""}} value="mon">Monday</option>
                                                    <option {{$productLocationBlockTimeLimit->day == "tue" ? "selected = selected" : ""}} value="tue">Tuesday</option>
                                                    <option {{$productLocationBlockTimeLimit->day == "wed" ? "selected = selected" : ""}} value="wed">Wednesday</option>
                                                    <option {{$productLocationBlockTimeLimit->day == "thu" ? "selected = selected" : ""}} value="thu">Thursday</option>
                                                    <option {{$productLocationBlockTimeLimit->day == "fri" ? "selected = selected" : ""}} value="fri">Friday</option>
                                                    <option {{$productLocationBlockTimeLimit->day == "sat" ? "selected = selected" : ""}} value="sat">Saturday</option>
                                                    <option {{$productLocationBlockTimeLimit->day == "sun" ? "selected = selected" : ""}} value="sun">Sunday</option>
                                                </select>
                                            @else
                                                <input type="text" name="reset_time_range_limits[{{$block_time_increment}}][date]" class="form-control block-time-range-date-picker" value="{{$productLocationBlockTimeLimit->date}}">
                                            @endif
                                        </td>
                                        <td>
                                            <input type="checkbox" {{(($productLocationBlockTimeLimit->start_time == "00:00:00" && $productLocationBlockTimeLimit->start_time == "00:00:00") ? "checked = checked" : "")}} class="form-control full-time-range-picker" name="" >
                                        </td>
                                        <td><input size="2" type="text" name="reset_time_range_limits[{{$block_time_increment}}][from_time]" class="form-control block-from-time-picker" value="{{$productLocationBlockTimeLimit->start_time}}"></td>
                                        <td><input size="2" type="text" name="reset_time_range_limits[{{$block_time_increment}}][to_time]" class="form-control block-to-time-picker" value="{{$productLocationBlockTimeLimit->end_time}}"></td>
                                        <td><input size="2" type="text" name="reset_time_range_limits[{{$block_time_increment}}][max_covers_limit]" class="form-control" value="{{$productLocationBlockTimeLimit->max_covers_limit}}"></td>
                                        <td><input size="2" type="text" name="reset_time_range_limits[{{$block_time_increment}}][max_tables_limit]" class="form-control" value="{{$productLocationBlockTimeLimit->max_tables_limit}}"></td>
                                        <td><a class="btn btn-danger delete-block-time-range">Remove</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <div class="panel-footer">
                            <a id="addNewBlockTimeRangeBtn" class="btn mb-xs btn-primary">Add Time Range Limits</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Publish Actions</h2>
        </header>
        <div class="panel-body">
            <div class="form-group col-sm-4">
                <label for="status">&nbsp;&nbsp;&nbsp;Status <span class="required">*</span>&nbsp;&nbsp;&nbsp;</label>
                <div class="radio-custom radio-success radio-inline">
                    <input type="radio" id="Active" name="status" value="Active" @if($productLocationDetails['status']=='Active') checked="checked" @else @endif >
                    <label for="Active">Active</label>
                </div>
                <div class="radio-custom radio-danger radio-inline">
                    <input type="radio" id="Inactive" name="status" value="Inactive"  @if($productLocationDetails['status']=='Inactive') checked="checked" @else @endif>
                    <label for="Inactive">Inactive</label>
                </div>
            </div>
            <div class="col-sm-2">
                {!! Form::submit('Save',['class'=>'btn btn-block btn-primary']) !!}
            </div>
        </div>
    </section>

    {!! Form::close()  !!}
@stop