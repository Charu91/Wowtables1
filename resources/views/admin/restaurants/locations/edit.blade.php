@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Edit Restaurant Location</h2>
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

    {!! Form::open(['route'=>['AdminRestaurantLocationsUpdate',$restaurant['RestaurantLocation']->id],'class'=>'form-horizontal','novalidate'=>'novalidate']) !!}

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
                <a href="#schedule_tab" data-toggle="tab" class="text-center">Scheduling</a>
            </li>
            <li>
                <a href="#alacarte_tab" data-toggle="tab" class="text-center">Alacarte(Off Peak)</a>
            </li>
            <li>
                <a href="#alacarte_peak_tab" data-toggle="tab" class="text-center">Alacarte(Peak)</a>
            </li>
            <li>
                <a href="#location_details" data-toggle="tab" class="text-center">Location Details</a>
            </li>
            <li>
                <a href="#block_schedules_tab" data-toggle="tab" class="text-center">Block Schedules</a>
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
                    <label for="attributes[restaurant_info]" class="col-sm-3 control-label">Restaurant Info <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[restaurant_info]',$restaurant['attributes']['restaurant_info'],['rows'=>'10','class'=>'form-control','id'=>'description','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <label for="attributes[seo_title]" class="col-sm-3 control-label">SEO Title <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_title]',$restaurant['attributes']['seo_title'],['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_description]" class="col-sm-3 control-label">SEO Meta Description <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[seo_meta_description]',$restaurant['attributes']['seo_meta_description'],['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_keywords]" class="col-sm-3 control-label">SEO Keywords <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_meta_keywords]',$restaurant['attributes']['seo_meta_keywords'],['class'=>'form-control','data-role'=>'tagsinput','data-tag-class'=>'label label-primary']) !!}
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
                                <label for="attributes[min_people_per_reservation]" class="col-sm-6 control-label">Minimum People Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('attributes[min_people_per_reservation]',$restaurant['attributes']['min_people_per_reservation'],['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="attributes[max_people_per_reservation]" class="col-sm-6 control-label">Maximum People Per Reservation <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('attributes[max_people_per_reservation]',$restaurant['attributes']['max_people_per_reservation'],['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="attributes[max_reservations_per_time_slot]" class="col-sm-6 control-label">Maximum Reservation Per Time Slot <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('attributes[max_reservations_per_time_slot]',$restaurant['attributes']['max_reservations_per_time_slot'],['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel">
                            <div class="form-group">
                                <label for="attributes[max_reservations_per_day]" class="col-sm-6 control-label">Maximum Reservation Per Day <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('attributes[max_reservations_per_day]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="attributes[minimum_reservation_time_buffer]" class="col-sm-6 control-label">Minimum Reservation Time Buffer <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('attributes[minimum_reservation_time_buffer]',$restaurant['attributes']['minimum_reservation_time_buffer'],['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="attributes[maximum_reservation_time_buffer]" class="col-sm-6 control-label">Maximum Reservation Time Buffer <span class="required">*</span></label>
                                <div class="col-sm-6">
                                    {!! Form::text('attributes[maximum_reservation_time_buffer]',null,['class'=>'form-control','required'=>'']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if( ! empty($breakfast) )
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Breakfast</h2>
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
                                    @foreach($breakfast as $slot)
                                        <tr>
                                            <td> <a id="selectrow" class="btn btn-xs btn-success select-all">Select All</a> | <a class="btn btn-xs btn-danger select-none">None</a> </td>
                                            <td>{{ $slot['time'] }}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                @endif

                @if( ! empty($lunch) )
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Lunch</h2>
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
                                    @foreach($lunch as $slot)
                                        <tr>
                                            <td> <a class="btn btn-xs btn-success select-all">Select All</a> | <a class="btn btn-xs btn-danger select-none">None</a> </td>
                                            <td>{{ $slot['time'] }}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                @endif

                @if( ! empty($dinner) )
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Dinner</h2>
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
                                    @foreach($dinner as $slot)
                                        <tr>
                                            <td> <a class="btn btn-xs btn-success select-all">Select All</a> | <a class="btn btn-xs btn-danger select-none">None</a> </td>
                                            <td>{{ $slot['time'] }}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
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
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="a_la_carte" id="a_la_carte" value="1">
                            <label  for="a_la_carte">Allow Alacarte Reservations <span class="required">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="attributes[allow_gift_card_redemptions]" id="attributes[allow_gift_card_redemptions]" value="1" @if($restaurant['attributes']['allow_gift_card_redemptions'] == true) checked="checked" @else @endif>
                            <label  for="attributes[allow_gift_card_redemptions]">Allow Gift Card Redemptions <span class="required">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[commission_per_cover]" class="col-sm-3 control-label">Commission per Cover <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[commission_per_cover]',$restaurant['attributes']['commission_per_cover'],['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[reward_points_per_reservation]" class="col-sm-3 control-label">Reward Points per Reservation <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[reward_points_per_reservation]',$restaurant['attributes']['reward_points_per_reservation'],['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="cuisine" class="col-sm-3 control-label">Cuisines <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[cuisines]',$cuisines,$restaurant['attributes']['cuisines'],['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'multiple','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="collections" class="col-sm-3 control-label">Collections <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('collections[]',['0'=>'None','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[menu_picks]" class="col-sm-3 control-label">Menu Picks <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[menu_picks]',$restaurant['attributes']['menu_picks'],['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[expert_tips]" class="col-sm-3 control-label">Expert Tips <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[expert_tips]',$restaurant['attributes']['expert_tips'],['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[terms_and_conditions]" class="col-sm-3 control-label">Terms & Conditions <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[terms_and_conditions]',$restaurant['attributes']['terms_and_conditions'],['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="alacarte_peak_tab" class="tab-pane mt-lg">
                Coming Soon.
            </div>
            <div id="location_details" class="tab-pane mt-lg">
                @include('partials.forms.locations')
            </div>
            <div id="block_schedules_tab" class="tab-pane mt-lg">
                Coming Soon.
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