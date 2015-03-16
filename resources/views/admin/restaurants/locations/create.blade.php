@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Create Restaurant Location</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Restaurant Locations</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right">
                <i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

{!! Form::open(['route'=>'AdminRestaurantLocationsStore','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}

    <div class="tabs tabs-primary">
        <ul class="nav nav-tabs nav-justified">
            <li class="active">
                <a href="#basic_details" data-toggle="tab" class="text-center">Basic Details</a>
            </li>
            <li>
                <a href="#seo_details" data-toggle="tab" class="text-center">SEO Details</a>
            </li>
            <li>
                <a href="#media_tab" data-toggle="tab" class="text-center">Media</a>
            </li>
            <li>
                <a href="#alacarte_tab" data-toggle="tab" class="text-center">Alacarte General</a>
            </li>
            <li>
                <a href="#schedule_tab" data-toggle="tab" class="text-center">Alacarte Schedule</a>
            </li>
            <li>
                <a href="#block_dates" data-toggle="tab" class="text-center">Block Dates </a>
            </li>
            <li>
                <a href="#location_details" data-toggle="tab" class="text-center">Location Details</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    <label for="restaurant_id" class="col-sm-3 control-label ">Select Restaurant <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('restaurant_id',$restaurants_list,'0',['class'=>'form-control populate select-restaurant','data-plugin-selectTwo'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="location_id" class="col-sm-3 control-label ">Select Locality <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('location_id',$locations_list,'0',['class'=>'form-control populate select-location','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="slug" class="col-sm-3 control-label ">Slug <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('slug',null,['class'=>'form-control generate-slug','id'=>'slug','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[descriptive_title]" class="col-sm-3 control-label">Descriptive Title <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[descriptive_title]',null,['rows'=>'5','class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[restaurant_info]" class="col-sm-3 control-label">Location Info <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[restaurant_info]',null,['rows'=>'10','class'=>'form-control','id'=>'description','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <label for="attributes[seo_title]" class="col-sm-3 control-label">SEO Title <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_title]',null,['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_description]" class="col-sm-3 control-label">SEO Meta Description <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[seo_meta_description]',null,['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_keywords]" class="col-sm-3 control-label">SEO Keywords <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_meta_keywords]',null,['class'=>'form-control','data-role'=>'tagsinput','data-tag-class'=>'label label-primary']) !!}
                    </div>
                </div>
            </div>
            <div id="media_tab" class="tab-pane mt-lg">
                @include('partials.forms.add_media')
            </div>
            <div id="schedule_tab" class="tab-pane mt-lg">
                @include('partials.forms.schedule_limits')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="attributes[min_covers_per_table_off_peak]" class="col-sm-6 control-label">Min Covers Per Table (Off Peak) <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('attributes[min_covers_per_table_off_peak]',null,['class'=>'form-control','required'=>'']) !!}
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
                        <a id="addNewBlockDateBtn" class="btn btn-primary">Add Another</a>
                        <h2 class="panel-title pull-right">Block Dates</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4 mb-sm block-date-div">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label for="publish_date" class="col-sm-4 control-label">Dates </label>
                                            @if( Input::old('block_dates') )
                                                @foreach(Input::old('block_dates') as $key => $block_date)
                                                <div class="col-sm-8">
                                                    {!! Form::text('block_dates['.$key.']',$block_date,['class'=>'form-control block-date-picker']) !!}
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="col-sm-8">
                                                    {!! Form::text('block_dates[]',null,['class'=>'form-control block-date-picker']) !!}
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>
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
                                <th>Action</th>
                            </tr>
                            <tbody>
                                @if( Input::old('reset_time_range_limits') )
                                    {{--*/ /*$limits=[];$block_time_range=[];*/ /*--}}

                                    @foreach(Input::old('reset_time_range_limits') as $key => $time_range)
                                        {{--*/
                                                foreach( Input::old('reset_time_range_limits.'.$key) as $subkey => $value){
                                                        $limits[$subkey][$key] = $value;
                                                }
                                        /*--}}
                                    @endforeach
                                    {{--*/ dd($limits); /*--}}
                                    @foreach($block_time_range as $key => $block_time)
                                    <tr>
                                        <td>{!! Form::select('reset_time_range_limits[limit_by]['.$key.']',['Day'=>'Day','Date'=>'Date'],null,['class'=>'form-control time-range-limit-by']) !!}</td>
                                        <td>
                                            {!! Form::select('reset_time_range_limits[day]['.$key.']',['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'],null,['class'=>'form-control block-time-range-day-picker']) !!}
                                            {!! Form::text('reset_time_range_limits[date]['.$key.']',null,['class'=>'form-control block-date-picker block-time-range-date-picker']) !!}
                                        </td>
                                        <td>
                                            {!! Form::checkbox('',null,false,['class'=>'form-control full-time-range-picker']) !!}
                                        </td>
                                        <td>{!! Form::text('reset_time_range_limits[from_time]['.$key.']',null,['size'=>'2','class'=>'form-control block-from-time-picker']) !!}</td>
                                        <td>{!! Form::text('reset_time_range_limits[to_time]['.$key.']',null,['size'=>'2','class'=>'form-control block-to-time-picker']) !!}</td>
                                        <td>{!! Form::text('reset_time_range_limits[max_covers_limit]['.$key.']',null,['size'=>'2','class'=>'form-control']) !!}</td>
                                        <td>
                                            <a class="btn btn-danger delete-block-time-range">Remove</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>{!! Form::select('reset_time_range_limits[limit_by][]',['Day'=>'Day','Date'=>'Date'],'Day',['class'=>'form-control time-range-limit-by']) !!}</td>
                                        <td>
                                            {!! Form::select('reset_time_range_limits[day][]',['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'],'Day',['class'=>'form-control block-time-range-day-picker']) !!}
                                            {!! Form::text('reset_time_range_limits[date][]',null,['class'=>'form-control block-date-picker block-time-range-date-picker']) !!}
                                        </td>
                                        <td>
                                            {!! Form::checkbox('',null,false,['class'=>'form-control full-time-range-picker']) !!}
                                        </td>
                                        <td>{!! Form::text('reset_time_range_limits[from_time][]',null,['size'=>'2','class'=>'form-control block-from-time-picker']) !!}</td>
                                        <td>{!! Form::text('reset_time_range_limits[to_time][]',null,['size'=>'2','class'=>'form-control block-to-time-picker']) !!}</td>
                                        <td>{!! Form::text('reset_time_range_limits[max_covers_limit][]',null,['size'=>'2','class'=>'form-control']) !!}</td>
                                        <td>
                                            <a class="btn btn-danger delete-block-time-range">Remove</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div id="alacarte_tab" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="a_la_carte" id="a_la_carte" value="1" checked="">
                            <label for="a_la_carte">Allow Alacarte Reservations <span class="required">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[commission_per_cover]" class="col-sm-3 control-label">Commission per Cover <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[commission_per_cover]',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[reward_points_per_reservation]" class="col-sm-3 control-label">Reward Points per Reservation <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[reward_points_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="cuisines" class="col-sm-3 control-label">Cuisines <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[cuisines][]',$cuisines,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="collections" class="col-sm-3 control-label">Collections <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[collections][]',['0'=>'None','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="flags" class="col-sm-3 control-label">Flags <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[flags]',[''=>'','1'=>'New','2'=>'Valentines Special'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="flags" class="col-sm-3 control-label">Price Indicator <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[price_indicator]',['Select'=>'Select','0'=>'Low','1'=>'Medium','2'=>'High'],null,['class'=>'form-control']) !!}
                    </div>
                </div>
                <!--
                <div class="form-group">
                    <label for="attributes[menu_picks]" class="col-sm-3 control-label">Menu Picks <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[menu_picks]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[expert_tips]" class="col-sm-3 control-label">Expert Tips <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[expert_tips]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[terms_and_conditions]" class="col-sm-3 control-label">Terms & Conditions <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[terms_and_conditions]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                ------->
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-1">
                        <a id="menuPicksBtn" data-target="#menuPicksModal" data-toggle="modal" class="btn btn-primary">Create Menu Picks</a>
                    </div>
                </div>
                <div id="menuPicksHolder">
                    <div class="form-group">
                        <label for="attributes[menu_picks]" class="col-sm-3 control-label">Menu Picks </label>
                        <div class="col-sm-6">
                            {!! Form::textarea('attributes[menu_picks]',null,['rows'=>'5','class'=>'form-control','required'=>'','id'=>'menuPicks']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-1">
                        <a id="expertTipsBtn" data-target="#expertTipsModal" data-toggle="modal" class="btn btn-primary">Create Expert Tips</a>
                    </div>
                </div>
                <div id="expertTipsHolder">
                    <div class="form-group">
                        <label for="attributes[expert_tips]" class="col-sm-3 control-label">Expert Tips </label>
                        <div class="col-sm-6">
                            {!! Form::textarea('attributes[expert_tips]',null,['rows'=>'5','class'=>'form-control','required'=>'','id'=>'expertTips']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-1">
                        <a id="termsConditionsBtn" data-target="#termsConditionsModal" data-toggle="modal" class="btn btn-primary">Create Terms & Conditions</a>
                    </div>
                </div>
                <div id="termsConditionsHolder">
                    <div class="form-group">
                        <label for="attributes[terms_and_conditions]" class="col-sm-3 control-label">Terms & Conditions </label>
                        <div class="col-sm-6">
                            {!! Form::textarea('attributes[terms_and_conditions]',null,['rows'=>'5','class'=>'form-control','required'=>'','id'=>'termsConditions']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div id="location_details" class="tab-pane mt-lg">
                @include('partials.forms.locations')
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