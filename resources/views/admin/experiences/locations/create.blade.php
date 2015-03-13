@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Create Experience Location</h2>
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
            <li>
                <a href="#time_range" data-toggle="tab" class="text-center">Time Range Limits</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    <label for="experience_id" class="col-sm-3 control-label">Select Experience <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('experience_id',$restaurants_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="location_id" class="col-sm-3 control-label">Select Restaurant Location <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('location_id',$locations_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="schedule_tab" class="tab-pane mt-lg">
                @include('partials.forms.schedule_limits')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="attributes[minimum_people_increments_per_reservation]" class="col-sm-6 control-label">
                                    Min People Increments / Reservation
                                    <span class="required">*</span>
                                </label>
                                <div class="col-sm-6">
                                    {!! Form::text('attributes[minimum_people_increments_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partials.forms.schedules')
            </div>
            <div id="block_dates" class="tab-pane mt-lg">

            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Block Dates</h2>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="publish_date" class="col-sm-4 control-label">Date <span class="required">*</span></label>
                                <div class="col-sm-8">
                                    {!! Form::text('publish_date',date('Y-m-d'),['class'=>'form-control','id'=>'restaurantLocationDatePicker']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="start_time" class="col-sm-4 control-label">From <span class="required">*</span></label>
                                <div class="col-sm-8">
                                    {!! Form::text('start_time','8:00',['class'=>'form-control','data-plugin-timepicker'=>'','id'=>'start_time']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="end_time" class="col-sm-4 control-label">To <span class="required">*</span></label>
                                <div class="col-sm-8">
                                    {!! Form::text('end_time','22:00',['class'=>'form-control','data-plugin-timepicker'=>'','id'=>'end_time']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <a id="" class="btn btn-primary">Add Another</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </div>
            <div id="time_range" class="tab-pane mt-lg">
                Coming Soon.
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
            <div class="form-group col-md-4">
                <label for="status">&nbsp;&nbsp;&nbsp;Status <span class="required">*</span>&nbsp;&nbsp;&nbsp;</label>
                <div class="radio-custom radio-success radio-inline">
                    <input type="radio" id="Active" name="status" value="Active">
                    <label for="Active">Active</label>
                </div>
                <div class="radio-custom radio-danger radio-inline">
                    <input type="radio" id="Inactive" name="status" value="Inactive" checked="checked">
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