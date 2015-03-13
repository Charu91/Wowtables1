@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Restaurants</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Add/Update Restaurant</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right">
                <i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    {!! Form::model($restaurant['restaurant'],['route'=>['AdminRestaurantUpdate',$restaurant['restaurant']['id']],'method'=>'PUT','novalidate'=>'novalidate']) !!}


    <div class="tabs tabs-primary">
        <ul class="nav nav-tabs nav-justified">
            <li class="active">
                <a href="#basic_details" data-toggle="tab" class="text-center">Restaurant Basic Details</a>
            </li>
            <li>
                <a href="#seo_details" data-toggle="tab" class="text-center">Search Engine Optimization</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    <label for="name" class="col-sm-3 col-sm-offset-1 control-label">Restaurant Name <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('name',null,['class'=>'form-control','id'=>'title','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="slug" class="col-sm-3 col-sm-offset-1 control-label">Slug <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('slug',null,['class'=>'form-control','id'=>'slug','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[restaurant_info]" class="col-sm-3 col-sm-offset-1 control-label">Restaurant Info <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[restaurant_info]',$restaurant['attributes']['restaurant_info'],['rows'=>'7','class'=>'form-control','id'=>'description','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <label for="attributes[seo_title]" class="col-sm-3 col-sm-offset-1 control-label">SEO Title </label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_title]',$restaurant['attributes']['seo_title'],['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_description]" class="col-sm-3 col-sm-offset-1 control-label">SEO Meta Description </label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[seo_meta_description]',$restaurant['attributes']['seo_meta_description'],['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[seo_meta_keywords]" class="col-sm-3 col-sm-offset-1 control-label">SEO Keywords </label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_meta_keywords]',$restaurant['attributes']['seo_meta_keywords'],['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'']) !!}
                    </div>
                </div>
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
                    {!! Form::text('publish_date',$publish_date,['class'=>'form-control','id'=>'restaurantDatePicker']) !!}
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="publish_time" class="col-sm-4 control-label">Time <span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('publish_time',$publish_time,['class'=>'form-control','id'=>'restaurantTimePicker']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="status">&nbsp;&nbsp;&nbsp;Status <span class="required">*</span>&nbsp;&nbsp;&nbsp;</label>
                <div class="radio-custom radio-success radio-inline">
                    <input type="radio" id="Publish" name="status" value="Publish" @if($restaurant['restaurant']['status'] == 'Publish') checked="checked" @else @endif>
                    <label for="Publish">Publish</label>
                </div>
                <div class="radio-custom radio-danger radio-inline">
                    <input type="radio" id="Draft" name="status" value="Draft" @if($restaurant['restaurant']['status'] == 'Draft') checked="checked" @else @endif >
                    <label for="Draft">Draft</label>
                </div>
            </div>
            <div class="col-sm-2">
                {!! Form::submit('Save',['class'=>'btn btn-block btn-primary']) !!}
            </div>
        </div>
    </section>


    {!! Form::close() !!}

    @include('modals.add_restaurant_attribute')

@stop