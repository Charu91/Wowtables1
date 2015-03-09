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
                <a href="#alacarte_tab" data-toggle="tab" class="text-center">Alacarte</a>
            </li>
            <li>
                <a href="#location_details" data-toggle="tab" class="text-center">Location</a>
            </li>
            <li>
                <a href="#miscellaneous_tab" data-toggle="tab" class="text-center">Miscellaneous</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    {!! Form::label('restaurant_id','Select Restaurant',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('restaurant_id',$restaurants_list,$restaurant['RestaurantLocation']->vendor->id,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('location_id','Locality',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('location_id',$locations_list,$restaurant['RestaurantLocation']->location->id,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('slug','Slug',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('slug',$restaurant['RestaurantLocation']->slug,['class'=>'form-control','id'=>'slug','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('attributes[restaurant_info]','Restaurant Info',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[restaurant_info]',null,['rows'=>'10','class'=>'form-control','id'=>'description','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('attributes[short_description]','Short Description',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[short_description]',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('status','Status',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('status',['Active'=>'Active','Inactive'=>'Inactive'],'Inactive',['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                <div class="form-group">
                    {!! Form::label('attributes[seo_title]','SEO Title',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_title]',null,['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('attributes[seo_meta_description]','SEO Meta Description',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[seo_meta_description]',null,['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('attributes[seo_meta_keywords]','SEO Keywords',['class'=>'col-sm-3 control-label']) !!}
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
                @include('partials.forms.schedules')
            </div>
            <div id="alacarte_tab" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="a_la_carte" id="a_la_carte" value="1" checked="">
                            <label  for="a_la_carte">Allow Alacarte Reservations</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('attributes[terms_and_conditions]','Terms & Conditions',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[terms_and_conditions]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="location_details" class="tab-pane mt-lg">
                @include('partials.forms.locations')
            </div>
            <div id="miscellaneous_tab" class="tab-pane mt-lg">
                @include('partials.forms.cousines_collections')
                @include('partials.forms.payment_details')
                <div class="form-group">
                    {!! Form::label('attributes[menu_picks]','Menu Picks',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[menu_picks]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('attributes[expert_tips]','Expert Tips',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[expert_tips]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
                    </div>
                </div>
                <div>

                </div>
            </div>
        </div>
    </div>

    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Publish Actions</h2>
        </header>
        <div class="panel-body">
            <div class="form-group col-md-5">
                {!! Form::label('publish_date','Date',['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('publish_date',date('Y-m-d'),['class'=>'form-control','id'=>'restaurantLocationDatePicker']) !!}
                </div>
            </div>
            <div class="form-group col-md-5">
                {!! Form::label('publish_time','Time',['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('publish_time',null,['class'=>'form-control','id'=>'restaurantLocationTimePicker']) !!}
                </div>
            </div>
            <div class="col-sm-2">
                {!! Form::submit('Save',['class'=>'btn btn-block btn-success']) !!}
            </div>
        </div>
    </section>

    {!! Form::close()  !!}
@stop