@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Create Experience Scheduling</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Experience Scheduling</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right">
                <i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

{!! Form::open(['route'=>'admin.experience.locations.store','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}

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
                        {!! Form::select('experience_id',$experiences_list,null,['id'=>'loc_exp','class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="location_id" class="col-sm-3 control-label">Select Restaurant Location <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('restaurant_location_id[]',$restaurant_locations_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="descriptive_title" class="col-sm-3 control-label">Descriptive Title<span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('descriptive_title',null,['class'=>'form-control', 'required'=>'required']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="show_status" class="col-sm-3 control-label">Show Status<span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('show_status',['show_in_all'=>'Show in all','hide_in_mobile'=>'Hide in mobile','hide_in_website'=>'Hide in website'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="schedule_tab" class="tab-pane mt-lg">
                @include('partials.forms.schedule_limits')
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">Timing</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="start_time" class="col-sm-4 control-label">Start Time <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        {!! Form::text('start_time','8:00',['class'=>'form-control','data-plugin-timepicker'=>'','id'=>'start_time']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="end_time" class="col-sm-4 control-label">End Time <span class="required">*</span></label>
                                    <div class="col-sm-8">
                                        {!! Form::text('end_time','22:00',['class'=>'form-control','data-plugin-timepicker'=>'','id'=>'end_time']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <a class="btn btn-primary" id="createSchedule" data-schedule-type="experience" >Create Schedule</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div id="schedules_table"></div>
            </div>
            <div id="block_dates" class="tab-pane mt-lg">
                <section class="panel">
                    <header class="panel-heading">
                        <a id="addNewBlockDateBtn" class="btn btn-primary">Add Another</a>
                        <h2 class="panel-title pull-right">Block Dates</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            @if( Input::old('block_dates') )
                                @foreach(Input::old('block_dates') as $key => $block_date)
                                    <div class="col-lg-4 mb-sm block-date-div">
                                        <div class="col-lg-10">
                                            <div class="form-group">
                                                <label for="publish_date" class="col-sm-4 control-label">Dates </label>
                                                <div class="col-sm-8">
                                                    {!! Form::text('block_dates['.$key.']',$block_date,['class'=>'form-control block-date-picker']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @if($key == 0)
                                        @else
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <a class="btn btn-danger delete-block-date-div"><i class="fa fa-times"></i></a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="col-lg-4 mb-sm block-date-div">
                                    <div class="col-lg-10">
                                        <div class="form-group">
                                            <label for="publish_date" class="col-sm-4 control-label">Dates </label>
                                            <div class="col-sm-8">
                                                {!! Form::text('block_dates[]',null,['class'=>'form-control block-date-picker']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
                <section class="panel">
                    <header class="panel-heading">
                        <a id="addNewBlockTimeRangeBtn" class="btn mb-xs btn-primary">Add Another</a>
                        <h2 class="panel-title pull-right">Time Range Limits</h2>
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
                                <th>Max Tables Limit</th>
                                <th>Action</th>
                            </tr>
                            <tbody>
                            </tbody>
                        </table>
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
            <div class="form-group col-md-3">
                <label for="publish_date" class="col-sm-4 control-label">Date <span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('publish_date',date('Y-m-d'),['class'=>'form-control','id'=>'restaurantLocationDatePicker']) !!}
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="publish_time" class="col-sm-4 control-label">Time <span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('publish_time',null,['class'=>'form-control','id'=>'restaurantLocationTimePicker']) !!}
                </div>
            </div>
            <div class="form-group col-md-5">
                <label for="status">&nbsp;&nbsp;&nbsp;Status <span class="required">*</span>&nbsp;&nbsp;&nbsp;</label>
                <div class="radio-custom radio-success radio-inline">
                    <input type="radio" id="Active" name="status" value="Active">
                    <label for="Active">Active</label>
                </div>
                <div class="radio-custom radio-danger radio-inline">
                    <input type="radio" id="Inactive" name="status" value="Inactive">
                    <label for="Inactive">Inactive</label>
                </div>
                <div class="radio-custom radio-danger radio-inline">
                    <input type="radio" id="Hidden" name="status" value="Hidden" checked="checked">
                    <label for="Hidden">Hidden</label>
                </div>
            </div>
            <div class="col-sm-2">
                {!! Form::submit('Save',['class'=>'btn btn-block btn-primary']) !!}
            </div>
        </div>
    </section>

{!! Form::close()  !!}
@stop