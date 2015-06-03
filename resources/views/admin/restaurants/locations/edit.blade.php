@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Edit Restaurant Location</h2>
        <style type="text/css">
            .multiselect-container{
                z-index: 9999;
            }

        </style>
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
    {!! Form::model($restaurant['RestaurantLocation'],['route'=>['AdminRestaurantLocationsUpdate',$restaurant['RestaurantLocation']->id],'method'=>'PUT','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}


    <div class="tabs tabs-primary">
        <ul class="nav nav-tabs nav-justified">
            <li class="active">
                <a href="#basic_details" data-toggle="tab" class="text-center">Basic Details</a>
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
            <!--<li>
                <a href="#alacarte_peak_tab" data-toggle="tab" class="text-center">Alacarte(Peak)</a>
            </li>-->
            <li>
                <a href="#block_schedules_tab" data-toggle="tab" class="text-center">Block Dates</a>
            </li>
            {{--<li>--}}
                {{--<a href="#location_details" data-toggle="tab" class="text-center">Location Details</a>--}}
            {{--</li>--}}

            <li>
                <a href="#contact_details" data-toggle="tab" class="text-center">Contact Details</a>
            </li>
            <li>
                <a href="#seo_details" data-toggle="tab" class="text-center">SEO Details</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    <label for="restaurant_id" class="col-sm-3 control-label">Select Restaurant <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('restaurant_id',$restaurants_list,$restaurant['RestaurantLocation']->vendor->id,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="location_id" class="col-sm-3 control-label">Select Locality <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('location_id',$locations_list,$restaurant['RestaurantLocation']->location->id,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="slug" class="col-sm-3 control-label">Slug <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('slug',$restaurant['RestaurantLocation']->slug,['class'=>'form-control','id'=>'slug','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="a_la_carte" id="a_la_carte" {{(isset($restaurant['RestaurantLocation']->a_la_carte) && $restaurant['RestaurantLocation']->a_la_carte == 1  ? "checked = checked" : "") }} value="1">
                            <label  for="a_la_carte">Allow Alacarte Reservations</label>
                        </div>
                    </div>
                </div>
                @include('partials.forms.locations_edit')
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <label for="attributes[seo_title]" class="col-sm-3 control-label">SEO Title <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_seo_title =  (isset($restaurant['attributes']['seo_title']) && $restaurant['attributes']['seo_title'] != "" ? $restaurant['attributes']['seo_title'] : ''); ?>
                        {!! Form::text('attributes[seo_title]',$restaurant_seo_title,['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_description]" class="col-sm-3 control-label">SEO Meta Description <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_seo_meta_description =  (isset($restaurant['attributes']['seo_meta_description']) && $restaurant['attributes']['seo_meta_description'] != "" ? $restaurant['attributes']['seo_meta_description'] : ''); ?>
                        {!! Form::textarea('attributes[seo_meta_description]',$restaurant_seo_meta_description,['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_keywords]" class="col-sm-3 control-label">SEO Keywords <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_seo_meta_keywords =  (isset($restaurant['attributes']['seo_meta_keywords']) && $restaurant['attributes']['seo_meta_keywords'] != "" ? $restaurant['attributes']['seo_meta_keywords'] : ''); ?>
                        {!! Form::text('attributes[seo_meta_keywords]',$restaurant_seo_meta_keywords,['class'=>'form-control','data-role'=>'tagsinput','data-tag-class'=>'label label-primary']) !!}
                    </div>
                </div>
            </div>
            <div id="media_tab" class="tab-pane mt-lg">
                @include('partials.forms.add_media')
                <hr/>
                @foreach($restaurantLocationMedias as $restaurantLocationMediaKey => $restaurantLocationMediaValue)
                    <h2>{{$restaurantLocationMediaKey}}</h2>
                    @foreach($restaurantLocationMediaValue as $key => $restaurantLocationMedia)

                        @if($restaurantLocationMediaKey == "listing")
                            {{! $setInputName = "old_media[listing_image]"}}
                            {{! $setImageUrl = "listing"}}
                            @elseif($restaurantLocationMediaKey == "gallery")
                            {{! $setInputName = "old_media[gallery_images][]"}}
                            {{! $setImageUrl = "gallery"}}
                            @elseif($restaurantLocationMediaKey == "mobile")
                            {{! $setInputName = "old_media[mobile]"}}
                            {{! $setImageUrl = "mobile"}}
                        @endif
                            <img width="100" src="https://s3-eu-west-1.amazonaws.com/wowtables/uploads/{{$setImageUrl}}/{{$restaurantLocationMedia}}" class="mt-xs mb-xs mr-xs img-thumbnail img-responsive">
                        <input type="hidden" name="{{$setInputName}}" value="{{$key}}"/>
                    @endforeach
                @endforeach
            </div>
            <div id="schedule_tab" class="tab-pane mt-lg">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="location_attributes[min_people_per_reservation]" class="col-sm-6 control-label">Minimum People Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    <?php $restaurant_min_people_reservation =  (isset($restaurantLocationLimits->min_people_per_reservation) && $restaurantLocationLimits->min_people_per_reservation != "" ? $restaurantLocationLimits->min_people_per_reservation : ''); ?>
                                    {!! Form::text('location_attributes[min_people_per_reservation]',$restaurant_min_people_reservation,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[max_people_per_reservation]" class="col-sm-6 control-label">Maximum People Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    <?php $restaurant_max_people_per_reservation =  (isset($restaurantLocationLimits->max_people_per_reservation) && $restaurantLocationLimits->max_people_per_reservation != "" ? $restaurantLocationLimits->max_people_per_reservation : ''); ?>
                                    {!! Form::text('location_attributes[max_people_per_reservation]',$restaurant_max_people_per_reservation,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[max_reservations_per_time_slot]" class="col-sm-6 control-label">Default Max Tables Per Time Slot </label>
                                <div class="col-sm-6">
                                    <?php $restaurant_max_reservations_per_time_slot =  (isset($restaurantLocationLimits->max_reservations_per_time_slot) && $restaurantLocationLimits->max_reservations_per_time_slot != "" ? $restaurantLocationLimits->max_reservations_per_time_slot : ''); ?>
                                    {!! Form::text('location_attributes[max_reservations_per_time_slot]',$restaurant_max_reservations_per_time_slot,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[max_reservations_per_day]" class="col-sm-6 control-label">Max covers <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    <?php $restaurant_max_reservations_per_day =  (isset($restaurantLocationLimits->max_reservations_per_day) && $restaurantLocationLimits->max_reservations_per_day != "" ? $restaurantLocationLimits->max_reservations_per_day : ''); ?>
                                    {!! Form::text('location_attributes[max_reservations_per_day]',$restaurant_max_reservations_per_day,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[off_peak_hour_discount_min_covers]" class="col-sm-6 control-label">Min Covers Per Table (Off Peak) <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    <?php $restaurant_off_peak_hour_discount_min_covers =  (isset($restaurantLocationLimits->off_peak_hour_discount_min_covers) && $restaurantLocationLimits->off_peak_hour_discount_min_covers != "" ? $restaurantLocationLimits->off_peak_hour_discount_min_covers : ''); ?>
                                    {!! Form::text('location_attributes[off_peak_hour_discount_min_covers]',$restaurant_off_peak_hour_discount_min_covers,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="location_attributes[max_people_per_day]" class="col-sm-6 control-label">Maximum Tables Per Day </label>
                                <div class="col-sm-6">
                                    <?php $restaurant_max_people_per_day =  (isset($restaurantLocationLimits->max_people_per_day) && $restaurantLocationLimits->max_people_per_day != "" ? $restaurantLocationLimits->max_people_per_day : ''); ?>
                                    {!! Form::text('location_attributes[max_people_per_day]',$restaurant_max_people_per_day,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[minimum_reservation_time_buffer]" class="col-sm-6 control-label">Min Advance Reservation Time (hrs) </label>
                                <div class="col-sm-6">
                                    <?php $restaurant_minimum_reservation_time_buffer =  (isset($restaurantLocationLimits->minimum_reservation_time_buffer) && $restaurantLocationLimits->minimum_reservation_time_buffer != "" ? $restaurantLocationLimits->minimum_reservation_time_buffer : ''); ?>
                                    {!! Form::text('location_attributes[minimum_reservation_time_buffer]',$restaurant_minimum_reservation_time_buffer,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[maximum_reservation_time_buffer]" class="col-sm-6 control-label">Max Advance Reservation Time (hrs) </label>
                                <div class="col-sm-6">
                                    <?php $restaurant_maximum_reservation_time_buffer =  (isset($restaurantLocationLimits->maximum_reservation_time_buffer) && $restaurantLocationLimits->maximum_reservation_time_buffer != "" ? $restaurantLocationLimits->maximum_reservation_time_buffer : ''); ?>
                                    {!! Form::text('location_attributes[maximum_reservation_time_buffer]',$restaurant_maximum_reservation_time_buffer,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="location_attributes[min_people_increments_per_reservation]" class="col-sm-6 control-label">Min People Increments Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    <?php $restaurant_min_people_increments =  (isset($restaurantLocationLimits->min_people_increments) && $restaurantLocationLimits->min_people_increments != "" ? $restaurantLocationLimits->min_people_increments : ''); ?>
                                    {!! Form::text('location_attributes[min_people_increments_per_reservation]',$restaurant_min_people_increments,['class'=>'form-control','required'=>'']) !!}
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
                                                    {{--<tr>--}}
                                                        {{--<td>Max T</td>--}}
                                                        {{--<td>{!! Form::text('schedules['.$slot['tue']['schedule_id'].'][max_reservations]','5',['size'=>'2']) !!}</td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Off Peak</td>
                                                        @if( array_get($restaurantSchedules,$slot['mon']['schedule_id']) == 1 )
                                                            <td>{!! Form::checkbox('schedules['.$slot['mon']['schedule_id'].'][off_peak]',$slot['mon']['schedule_id'],true)!!}</td>

                                                        @else
                                                            {{--<td>{!! Form::checkbox('schedules['.$slot['tue']['schedule_id'].'][off_peak]',$slot['mon']['schedule_id'],false)!!}</td>--}}
                                                            <td>{!! Form::checkbox('schedules['.$slot['mon']['schedule_id'].'][off_peak]','1',false) !!}</td>
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
                                                    {{--<tr>--}}
                                                        {{--<td>Max T</td>--}}
                                                        {{--<td>{!! Form::text('schedules['.$slot['tue']['schedule_id'].'][max_reservations]','5',['size'=>'2']) !!}</td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Off Peak</td>
                                                        @if( array_get($restaurantSchedules,$slot['tue']['schedule_id']) == 1 )
                                                            <td>{!! Form::checkbox('schedules['.$slot['tue']['schedule_id'].'][off_peak]',$slot['tue']['schedule_id'],true)!!}</td>
                                                        @else
                                                            {{--<td>{!! Form::checkbox('schedules['.$slot['tue']['schedule_id'].'][off_peak]',$slot['tue']['schedule_id'],false)!!}</td>--}}
                                                            <td>{!! Form::checkbox('schedules['.$slot['tue']['schedule_id'].'][off_peak]','1',false) !!}</td>
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
                                                    {{--<tr>--}}
                                                        {{--<td>Max T</td>--}}
                                                        {{--<td>{!! Form::text('schedules['.$slot['wed']['schedule_id'].'][max_reservations]','5',['size'=>'2']) !!}</td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Off Peak</td>
                                                        @if( array_get($restaurantSchedules,$slot['wed']['schedule_id']) == 1 )
                                                            <td>{!! Form::checkbox('schedules['.$slot['wed']['schedule_id'].'][off_peak]',$slot['wed']['schedule_id'],true)!!}</td>
                                                        @else
                                                            {{--<td>{!! Form::checkbox('schedules['.$slot['wed']['schedule_id'].'][off_peak]',$slot['wed']['schedule_id'],false)!!}</td>--}}
                                                            <td>{!! Form::checkbox('schedules['.$slot['wed']['schedule_id'].'][off_peak]','1',false) !!}</td>
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
                                                    {{--<tr>--}}
                                                        {{--<td>Max T</td>--}}
                                                        {{--<td>{!! Form::text('schedules['.$slot['thu']['schedule_id'].'][max_reservations]','5',['size'=>'2']) !!}</td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Off Peak</td>
                                                        @if( array_get($restaurantSchedules,$slot['thu']['schedule_id']) == 1 )
                                                            <td>{!! Form::checkbox('schedules['.$slot['thu']['schedule_id'].'][off_peak]',$slot['thu']['schedule_id'],true)!!}</td>
                                                        @else
                                                            {{--<td>{!! Form::checkbox('schedules['.$slot['thu']['schedule_id'].'][off_peak]',$slot['thu']['schedule_id'],false)!!}</td>--}}
                                                            <td>{!! Form::checkbox('schedules['.$slot['thu']['schedule_id'].'][off_peak]','1',false) !!}</td>
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
                                                    {{--<tr>--}}
                                                        {{--<td>Max T</td>--}}
                                                        {{--<td>{!! Form::text('schedules['.$slot['fri']['schedule_id'].'][max_reservations]','5',['size'=>'2']) !!}</td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Off Peak</td>
                                                        @if( array_get($restaurantSchedules,$slot['fri']['schedule_id']) == 1 )
                                                            <td>{!! Form::checkbox('schedules['.$slot['fri']['schedule_id'].'][off_peak]',$slot['fri']['schedule_id'],true)!!}</td>
                                                        @else
                                                            {{--<td>{!! Form::checkbox('schedules['.$slot['fri']['schedule_id'].'][off_peak]',$slot['fri']['schedule_id'],false)!!}</td>--}}
                                                            <td>{!! Form::checkbox('schedules['.$slot['fri']['schedule_id'].'][off_peak]','1',false) !!}</td>
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
                                                    {{--<tr>--}}
                                                        {{--<td>Max T</td>--}}
                                                        {{--<td>{!! Form::text('schedules['.$slot['sat']['schedule_id'].'][max_reservations]','5',['size'=>'2']) !!}</td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Off Peak</td>
                                                        @if( array_get($restaurantSchedules,$slot['sat']['schedule_id']) == 1 )
                                                            <td>{!! Form::checkbox('schedules['.$slot['sat']['schedule_id'].'][off_peak]',$slot['sat']['schedule_id'],true)!!}</td>

                                                        @else
                                                            {{--<td>{!! Form::checkbox('schedules['.$slot['sat']['schedule_id'].'][off_peak]',$slot['sat']['schedule_id'],false)!!}</td>--}}
                                                            <td>{!! Form::checkbox('schedules['.$slot['sat']['schedule_id'].'][off_peak]','1',false) !!}</td>
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
                                                    {{--<tr>--}}
                                                        {{--<td>Max T</td>--}}
                                                        {{--<td>{!! Form::text('schedules['.$slot['sun']['schedule_id'].'][max_reservations]','5',['size'=>'2']) !!}</td>--}}
                                                    {{--</tr>--}}
                                                    <tr>
                                                        <td>Off Peak</td>
                                                        @if( array_get($restaurantSchedules,$slot['sun']['schedule_id']) == 1 )
                                                            <td>{!! Form::checkbox('schedules['.$slot['sun']['schedule_id'].'][off_peak]',$slot['sun']['schedule_id'],true)!!}</td>
                                                        @else
                                                            {{--<td>{!! Form::checkbox('schedules['.$slot['sun']['schedule_id'].'][off_peak]',$slot['sun']['schedule_id'],false)!!}</td>--}}
                                                            <td>{!! Form::checkbox('schedules['.$slot['sun']['schedule_id'].'][off_peak]','1',false) !!}</td>
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
            <div id="alacarte_tab" class="tab-pane mt-lg">
                <div class="form-group">
                    <label for="attributes[commission_per_cover]" class="col-sm-3 control-label">Commission per Cover <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_commission_per_cover =  (isset($restaurant['attributes']['commission_per_cover']) && $restaurant['attributes']['commission_per_cover'] != "" ? $restaurant['attributes']['commission_per_cover'] : ''); ?>
                        {!! Form::text('attributes[commission_per_cover]',$restaurant_commission_per_cover,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[reward_points_per_reservation]" class="col-sm-3 control-label">Reward Points per Reservation <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_reward_points_per_reservation =  (isset($restaurant['attributes']['reward_points_per_reservation']) && $restaurant['attributes']['reward_points_per_reservation'] != "" ? $restaurant['attributes']['reward_points_per_reservation'] : ''); ?>
                        {!! Form::text('attributes[reward_points_per_reservation]',$restaurant_reward_points_per_reservation,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="flags" class="col-sm-3 control-label">Price Indicator <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_pricing_level =  (isset($restaurant['RestaurantLocation']->pricing_level) && $restaurant['RestaurantLocation']->pricing_level != "" ? $restaurant['RestaurantLocation']->pricing_level : ''); ?>
                        {!! Form::select('pricing_level',['0'=>'Select','Low'=>'Low','Medium'=>'Medium','High'=>'High'],$restaurant_pricing_level,['class'=>'form-control','id'=>'restaurantPriceIndicator']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="cuisine" class="col-sm-3 control-label">Cuisines <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {{--{!! Form::select('attributes[cuisines][]',$cuisines,$restaurant['attributes']['cuisines'],['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'multiple','required'=>'']) !!}--}}
                        <?php $set_cuisines = (isset($restaurant['attributes']['cuisines']) && $restaurant['attributes']['cuisines'] !="" ? $restaurant['attributes']['cuisines'] : ' ') ?>
                        {!! Form::select('attributes[cuisines][]',$cuisines,$set_cuisines,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="collections" class="col-sm-3 control-label">Collections <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $set_tags = (isset($restaurantLocationTags) && $restaurantLocationTags !="" ? $restaurantLocationTags : ' ') ?>
                        {!! Form::select('attributes[collections][]',$tags_list,$set_tags,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
                        {{--{!! Form::select('attributes[collections][]',$tags_list,$restaurantLocationTags,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'','required'=>'']) !!}--}}
                    </div>
                </div>
                <div class="form-group">
                    <label for="flags" class="col-sm-3 control-label">Flags </label>
                    <div class="col-sm-6">
                        <?php $set_flags = (isset($restaurantLocationFlags->flag_id) && $restaurantLocationFlags->flag_id !="" ? $restaurantLocationFlags->flag_id : ' ');
                              $a = array_unshift($flags_list,'Select');
                        ?>
                            {!! Form::select('attributes[flags]',$flags_list,$set_flags,['class'=>'form-control populate','id'=>'restaurantsFlags']) !!}
                        {{--{!! Form::text('attributes[flags]',$set_flags,['class'=>'form-control populate flags-select-box flagsList']) !!}--}}
                        {{--{!! Form::text('attributes[flags]',$restaurantLocationFlags->flag_id,['class'=>'form-control populate flags-select-box flagsList']) !!}--}}
                    </div>
                </div>
                <div class="form-group">
                    <label for="curators" class="col-sm-3 control-label">Guest Curator </label>
                    <div class="col-sm-6">
                        <?php $set_curators = (isset($restaurantLocationCurators->curator_id) && $restaurantLocationCurators->curator_id !="" ? $restaurantLocationCurators->curator_id : ' ');
                              $a = array_unshift($curator_list,'Select');
                        ?>
                            {!! Form::select('curators',$curator_list,$set_curators,['class'=>'form-control populate','id'=>'restaurantsGuestCurator']) !!}
                        {{--{!! Form::text('curators',$set_curators,['class'=>'form-control populate curators-select-box curatorsList']) !!}--}}
                        {{--{!! Form::text('curators',$restaurantLocationCurators->curator_id,['class'=>'form-control populate curators-select-box curatorsList']) !!}--}}
                    </div>
                </div>
                <div class="form-group">
                    <label for="curator_tips" class="col-sm-3 control-label">Guest Curator Recommendations </label>
                    <div class="col-sm-6">
                        <?php $set_curators_tips = (isset($restaurantLocationCurators->curator_tips) && $restaurantLocationCurators->curator_tips !="" ? $restaurantLocationCurators->curator_tips : ' ') ?>
                        {!! Form::textarea('curator_tips',$set_curators_tips,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[descriptive_title]" class="col-sm-3 control-label">Descriptive Title <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_short_description = (isset($restaurant['attributes']['short_description']) && $restaurant['attributes']['short_description'] !="" ? $restaurant['attributes']['short_description'] : '') ?>
                        {!! Form::textarea('attributes[short_description]',$restaurant_short_description,['rows'=>'5','class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[restaurant_info]" class="col-sm-3 control-label">Location Info <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_info = (isset($restaurant['attributes']['restaurant_info']) && $restaurant['attributes']['restaurant_info'] !="" ? $restaurant['attributes']['restaurant_info'] : '') ?>
                        {!! Form::textarea('attributes[restaurant_info]',$restaurant_info,['rows'=>'10','class'=>'form-control','id'=>'description','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[menu_picks]" class="col-sm-3 control-label">Menu Picks <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_menu_picks = (isset($restaurant['attributes']['menu_picks']) && $restaurant['attributes']['menu_picks'] !="" ? $restaurant['attributes']['menu_picks'] : '') ?>
                        {!! Form::textarea('attributes[menu_picks]',$restaurant_menu_picks,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[expert_tips]" class="col-sm-3 control-label">Expert Tips <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_expert_tips = (isset($restaurant['attributes']['expert_tips']) && $restaurant['attributes']['expert_tips'] !="" ? $restaurant['attributes']['expert_tips'] : '') ?>
                        {!! Form::textarea('attributes[expert_tips]',$restaurant_expert_tips,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[terms_and_conditions]" class="col-sm-3 control-label">Terms & Conditions <span class="required">*</span></label>
                    <div class="col-sm-6">
                        <?php $restaurant_terms_and_conditions = (isset($restaurant['attributes']['terms_and_conditions']) && $restaurant['attributes']['terms_and_conditions'] !="" ? $restaurant['attributes']['terms_and_conditions'] : '') ?>
                        {!! Form::textarea('attributes[terms_and_conditions]',$restaurant_terms_and_conditions,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="alacarte_peak_tab" class="tab-pane mt-lg">
                Coming Soon.
            </div>
            {{--<div id="location_details" class="tab-pane mt-lg">--}}
                {{--@include('partials.forms.locations_edit')--}}
            {{--</div>--}}
            <div id="block_schedules_tab" class="tab-pane mt-lg">
                <section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">Block Dates</h2>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            @if(!empty($restaurantLocationBlockDates))
                                @foreach($restaurantLocationBlockDates as $restaurantLocationBlockDate)
                                    <div class="col-lg-4 mb-sm block-date-div">
                                        <div class="col-lg-10"><div class="form-group">
                                                <label class="col-sm-4 control-label" for="block_dates[]">Dates </label>
                                                <div class="col-sm-8"><input type="text" class="form-control block-date-picker" name="block_dates[]" value="{{$restaurantLocationBlockDate->block_date}}"></div>
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
                            @if(!empty($restaurantLocationBlockTimeLimits))
                                {{!$block_time_increment = 1}}
                                @foreach($restaurantLocationBlockTimeLimits as $restaurantLocationBlockTimeLimit)
                                    <tr>
                                        <td>
                                            <select name="reset_time_range_limits[{{$block_time_increment}}][limit_by]" class="form-control time-range-limit-by">
                                                <option {{$restaurantLocationBlockTimeLimit->limit_by == "Day" ? "selected = selected" : ""}} value="Day">Day</option>
                                                <option {{$restaurantLocationBlockTimeLimit->limit_by == "Date" ? "selected = selected" : ""}} value="Date">Date</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if($restaurantLocationBlockTimeLimit->limit_by == "Day")
                                                <select name="reset_time_range_limits[{{$block_time_increment}}][day]" class="form-control block-time-range-day-picker">
                                                    <option {{$restaurantLocationBlockTimeLimit->day == "mon" ? "selected = selected" : ""}} value="mon">Monday</option>
                                                    <option {{$restaurantLocationBlockTimeLimit->day == "tue" ? "selected = selected" : ""}} value="tue">Tuesday</option>
                                                    <option {{$restaurantLocationBlockTimeLimit->day == "wed" ? "selected = selected" : ""}} value="wed">Wednesday</option>
                                                    <option {{$restaurantLocationBlockTimeLimit->day == "thu" ? "selected = selected" : ""}} value="thu">Thursday</option>
                                                    <option {{$restaurantLocationBlockTimeLimit->day == "fri" ? "selected = selected" : ""}} value="fri">Friday</option>
                                                    <option {{$restaurantLocationBlockTimeLimit->day == "sat" ? "selected = selected" : ""}} value="sat">Saturday</option>
                                                    <option {{$restaurantLocationBlockTimeLimit->day == "sun" ? "selected = selected" : ""}} value="sun">Sunday</option>
                                                </select>
                                            @else
                                                <input type="text" name="reset_time_range_limits[{{$block_time_increment}}][date]" class="form-control block-time-range-date-picker" value="{{$restaurantLocationBlockTimeLimit->date}}">
                                            @endif
                                        </td>
                                        <td>
                                            <input type="checkbox" {{(($restaurantLocationBlockTimeLimit->start_time == "00:00:00" && $restaurantLocationBlockTimeLimit->start_time == "00:00:00") ? "checked = checked" : "")}} class="form-control full-time-range-picker" name="" >
                                        </td>
                                        <td><input size="2" type="text" name="reset_time_range_limits[{{$block_time_increment}}][from_time]" class="form-control block-from-time-picker" value="{{$restaurantLocationBlockTimeLimit->start_time}}"></td>
                                        <td><input size="2" type="text" name="reset_time_range_limits[{{$block_time_increment}}][to_time]" class="form-control block-to-time-picker" value="{{$restaurantLocationBlockTimeLimit->end_time}}"></td>
                                        <td><input size="2" type="text" name="reset_time_range_limits[{{$block_time_increment}}][max_covers_limit]" class="form-control" value="{{$restaurantLocationBlockTimeLimit->max_covers_limit}}"></td>
                                        <td><input size="2" type="text" name="reset_time_range_limits[{{$block_time_increment}}][max_tables_limit]" class="form-control" value="{{$restaurantLocationBlockTimeLimit->max_tables_limit}}"></td>
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
                            @if(!empty($restaurantLocationContacts))
                                {{!$contact_increment = 1}}
                                @foreach($restaurantLocationContacts as $restaurantLocationContact)
                                    <tr>
                                        <td>{!! Form::text('contacts[{{$contact_increment}}][name]',$restaurantLocationContact->name,['class'=>'form-control restaurant-contact-name']) !!}</td>
                                        <td>{!! Form::text('contacts[{{$contact_increment}}][designation]',$restaurantLocationContact->designation,['class'=>'form-control restaurant-contact-designation']) !!}</td>
                                        <td>{!! Form::text('contacts[{{$contact_increment}}][phone_number]',$restaurantLocationContact->phone_number,['class'=>'form-control restaurant-contact-email']) !!}</td>
                                        <td>{!! Form::text('contacts[{{$contact_increment}}][email]',$restaurantLocationContact->email,['class'=>'form-control restaurant-contact-phone']) !!}</td>
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
            <div class="form-group col-sm-4">
                <label for="status">&nbsp;&nbsp;&nbsp;Status <span class="required">*</span>&nbsp;&nbsp;&nbsp;</label>
                <div class="radio-custom radio-success radio-inline">
                    <input type="radio" id="Active" name="status" value="Active" @if($restaurant['RestaurantLocation']->status=='Active') checked="checked" @else @endif >
                    <label for="Active">Active</label>
                </div>
                <div class="radio-custom radio-danger radio-inline">
                    <input type="radio" id="Inactive" name="status" value="Inactive"  @if($restaurant['RestaurantLocation']->status=='Inactive') checked="checked" @else @endif>
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