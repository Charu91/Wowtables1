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
            <li>
                <a href="#contact_details" data-toggle="tab" class="text-center">Contact Details</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    <label for="restaurant_id" class="col-sm-3 control-label ">Select Restaurant <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('restaurant_id',null,['class'=>'form-control populate restaurants-select-box select-restaurant']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="location_id" class="col-sm-3 control-label ">Select Locality <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('location_id',null,['class'=>'form-control populate localities-select-box select-location']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="slug" class="col-sm-3 control-label ">Slug <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('slug',null,['class'=>'form-control generate-slug','id'=>'slug','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[short_description]" class="col-sm-3 control-label">Descriptive Title <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[short_description]',null,['rows'=>'5','class'=>'form-control','required'=>'']) !!}
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
                    <label for="attributes[seo_title]" class="col-sm-3 control-label">SEO Title </label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_title]',null,['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_description]" class="col-sm-3 control-label">SEO Meta Description </label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[seo_meta_description]',null,['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_keywords]" class="col-sm-3 control-label">SEO Keywords </label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_meta_keywords]',null,['class'=>'form-control','data-role'=>'tagsinput','data-tag-class'=>'label label-primary']) !!}
                    </div>
                </div>
            </div>
            <div id="media_tab" class="tab-pane mt-lg">
                @include('partials.forms.add_media')
            </div>
            <div id="schedule_tab" class="tab-pane mt-lg">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="location_attributes[min_people_per_reservation]" class="col-sm-6 control-label">Minimum People Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[min_people_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[max_people_per_reservation]" class="col-sm-6 control-label">Maximum People Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[max_people_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[max_reservations_per_time_slot]" class="col-sm-6 control-label">Default Max Tables Per Time Slot </label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[max_reservations_per_time_slot]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[max_reservations_per_day]" class="col-sm-6 control-label">Max Reservations per day <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[max_reservations_per_day]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[off_peak_hour_discount_min_covers]" class="col-sm-6 control-label">Min Covers Per Table (Off Peak) <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[off_peak_hour_discount_min_covers]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="location_attributes[max_people_per_day]" class="col-sm-6 control-label">Maximum Tables Per Day </label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[max_people_per_day]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[minimum_reservation_time_buffer]" class="col-sm-6 control-label">Min Advance Reservation Time (hrs) </label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[minimum_reservation_time_buffer]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[maximum_reservation_time_buffer]" class="col-sm-6 control-label">Max Advance Reservation Time (hrs) </label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[maximum_reservation_time_buffer]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[min_people_increments_per_reservation]" class="col-sm-6 control-label">Min People Increments Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('location_attributes[min_people_increments_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
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
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <a class="btn btn-danger delete-block-date-div"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                    <div class="block-date-div"></div>
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
                                @if( Input::old('reset_time_range_limits') )
                                    @foreach(Input::old('reset_time_range_limits') as $key => $time_range)
                                        <tr>
                                            <td>{!! Form::select('reset_time_range_limits['.$key.'][limit_by]',['Day'=>'Day','Date'=>'Date'],null,['class'=>'form-control time-range-limit-by']) !!}</td>
                                            <td>
                                                @if( Input::old('reset_time_range_limits')[$key]['limit_by'] == 'Day' )
                                                    {!! Form::select('reset_time_range_limits['.$key.'][day]',['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'],'Day',['class'=>'form-control block-time-range-day-picker']) !!}
                                                    {!! Form::text('reset_time_range_limits['.$key.'][date]',null,['style'=>'display:none;','class'=>'form-control block-time-range-date-picker']) !!}
                                                @else
                                                    {!! Form::select('reset_time_range_limits['.$key.'][day]',['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'],'Day',['style'=>'display:none;','class'=>'form-control block-time-range-day-picker']) !!}
                                                    {!! Form::text('reset_time_range_limits['.$key.'][date]',null,['class'=>'form-control block-time-range-date-picker']) !!}
                                                @endif
                                            </td>
                                            <td>{!! Form::checkbox('',null,true,['class'=>'form-control full-time-range-picker']) !!}</td>
                                            <td>
                                                @if( (Input::old('reset_time_range_limits')[$key]['from_time'] == '00:00:00' || Input::old('reset_time_range_limits')[$key]['from_time'] == '0:00:00' )  && ( Input::old('reset_time_range_limits')[$key]['to_time'] == '00:00:00' || Input::old('reset_time_range_limits')[$key]['to_time'] == '0:00:00') )
                                                    {!! Form::text('reset_time_range_limits['.$key.'][from_time]',null,['size'=>'2','class'=>'form-control block-from-time-picker','readonly'=>'']) !!}</td>
                                                @else
                                                    {!! Form::text('reset_time_range_limits['.$key.'][from_time]',null,['size'=>'2','class'=>'form-control block-from-time-picker']) !!}</td>
                                                @endif
                                            <td>
                                                @if( (Input::old('reset_time_range_limits')[$key]['from_time'] == '00:00:00' || Input::old('reset_time_range_limits')[$key]['from_time'] == '0:00:00' )  && ( Input::old('reset_time_range_limits')[$key]['to_time'] == '00:00:00' || Input::old('reset_time_range_limits')[$key]['to_time'] == '0:00:00') )
                                                    {!! Form::text('reset_time_range_limits['.$key.'][to_time]',null,['size'=>'2','class'=>'form-control block-to-time-picker','readonly'=>'']) !!}</td>
                                                @else
                                                    {!! Form::text('reset_time_range_limits['.$key.'][to_time]',null,['size'=>'2','class'=>'form-control block-to-time-picker']) !!}</td>
                                                @endif
                                            <td>{!! Form::text('reset_time_range_limits['.$key.'][max_covers_limit]',null,['size'=>'2','class'=>'form-control']) !!}</td>
                                            <td>
                                                <a class="btn btn-danger delete-block-time-range">Remove</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <!--<tr>
                                        <td>{!! Form::select('reset_time_range_limits[0][limit_by]',['Day'=>'Day','Date'=>'Date'],'Day',['class'=>'form-control time-range-limit-by']) !!}</td>
                                        <td>
                                            {!! Form::select('reset_time_range_limits[0][day]',['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'],'Day',['class'=>'form-control block-time-range-day-picker']) !!}
                                            {!! Form::text('reset_time_range_limits[0][date]',null,['style'=>'display:none;','class'=>'form-control block-time-range-date-picker']) !!}
                                        </td>
                                        <td>
                                            {!! Form::checkbox('',null,false,['class'=>'form-control full-time-range-picker']) !!}
                                        </td>
                                        <td>{!! Form::text('reset_time_range_limits[0][from_time]',null,['size'=>'2','class'=>'form-control block-from-time-picker']) !!}</td>
                                        <td>{!! Form::text('reset_time_range_limits[0][to_time]',null,['size'=>'2','class'=>'form-control block-to-time-picker']) !!}</td>
                                        <td>{!! Form::text('reset_time_range_limits[0][max_covers_limit]',null,['size'=>'2','class'=>'form-control']) !!}</td>
                                        <td>
                                            <a class="btn btn-danger delete-block-time-range">Remove</a>
                                        </td>
                                    </tr>-->
                                @endif
                            </tbody>
                        </table>
                        <div class="panel-footer">
                            <a id="addNewBlockTimeRangeBtn" class="btn mb-xs btn-primary">Add Time Range Limits</a>
                        </div>
                    </div>
                </section>
            </div>
            <div id="alacarte_tab" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="a_la_carte" id="a_la_carte" value="1" checked="">
                            <label for="a_la_carte">Allow Alacarte Reservations </label>
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
                    <label for="flags" class="col-sm-3 control-label">Price Indicator <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('pricing_level',['Low'=>'Low','Medium'=>'Medium','High'=>'High'],null,['class'=>'form-control','data-plugin-selectTwo'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="cuisines" class="col-sm-3 control-label">Cuisines </label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[cuisines][]',$cuisines,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="collections" class="col-sm-3 control-label">Collections </label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[collections][]',$tags_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="flags" class="col-sm-3 control-label">Flags </label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[flags]',$flags_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="curators" class="col-sm-3 control-label">Guest Curator </label>
                    <div class="col-sm-6">
                        {!! Form::select('curators',$curator_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="curator_tips" class="col-sm-3 control-label">Guest Curator Recommendations </label>
                    <div class="col-sm-6">
                        {!! Form::textarea('curator_tips',null,['class'=>'form-control redactor-text','required'=>'']) !!}
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
                    <label for="attributes[menu_picks]" class="col-sm-3 control-label">Menu Picks </label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[menu_picks]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[expert_tips]" class="col-sm-3 control-label">Expert Tips </label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[expert_tips]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[terms_and_conditions]" class="col-sm-3 control-label">Terms & Conditions </label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[terms_and_conditions]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="location_details" class="tab-pane mt-lg">
                @include('partials.forms.locations')
            </div>
            <div id="contact_details" class="tab-pane mt-lg">
                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Restaurant Contacts</h2>
                    </header>
                    <div class="panel-body">
                        <table id="restaurantContactsTable"  class="table table-bordered mb-none">
                            <tr>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            <tbody>
                            @if( Input::old('contacts') )
                                @foreach(Input::old('contacts') as $key => $contact)
                                    <tr>
                                        <td>{!! Form::text('contacts['.$key.'][name]',null,['class'=>'form-control restaurant-contact-name']) !!}</td>
                                        <td>{!! Form::text('contacts['.$key.'][designation]',null,['class'=>'form-control restaurant-contact-designation']) !!}</td>
                                        <td>{!! Form::text('contacts['.$key.'][phone_number]',null,['class'=>'form-control restaurant-contact-phone']) !!}</td>
                                        <td>{!! Form::text('contacts['.$key.'][email]',null,['class'=>'form-control restaurant-contact-email']) !!}</td>
                                        <td>
                                            <a class="btn btn-danger delete-restaurant-contact">Remove</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <!--<tr>
                                    <td>{!! Form::text('contacts[0][name]',null,['class'=>'form-control restaurant-contact-name']) !!}</td>
                                    <td>{!! Form::text('contacts[0][designation]',null,['class'=>'form-control restaurant-contact-designation']) !!}</td>
                                    <td>{!! Form::text('contacts[0][phone_number]',null,['class'=>'form-control restaurant-contact-name']) !!}</td>
                                    <td>
                                        <a class="btn btn-danger delete-restaurant-contact">Remove</a>
                                    </td>
                                </tr>-->
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <a id="addNewContactsBtn" class="btn mb-xs btn-primary">Add Another Contact</a>
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