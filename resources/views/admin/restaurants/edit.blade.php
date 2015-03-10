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

    {!! Form::model($restaurant['restaurant'],['route'=>['AdminRestaurantUpdate',$restaurant['restaurant']['id']],'method'=>'PUT','id'=>'editRestaurantForm','novalidate'=>'novalidate']) !!}

    <section  class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Restaurant Basic Details</h2>
        </header>
        <div class="panel-body">
            <div class="form-group">
                {!! Form::label('name','Restaurant Name',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('name',null,['class'=>'form-control','id'=>'title','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('slug','Slug',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('slug',null,['class'=>'form-control','id'=>'slug','required'=>'']) !!}
                </div>
            </div>
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
                    {!! Form::select('attributes[seo_meta_keywords]',[''=>''],null,['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('attributes[restaurant_info]','Restaurant Info',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('attributes[restaurant_info]',null,['rows'=>'7','class'=>'form-control','id'=>'description','required'=>'']) !!}
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
                    {!! Form::select('status',['Draft'=>'Draft','Publish'=>'Publish'],'Draft',['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
        </div>
    </section>

    <!--<section  class="panel">
        <header class="panel-heading">
            <a class="btn btn-primary" id="addNewRestaurantAttributeBtn">Add New Attribute</a>
            <h2 class="panel-title pull-right">Restaurant Attributes</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div id="addRestaurantAttributesHolder"></div>
            </div>
        </div>
    </section>-->

    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Publish Actions</h2>
        </header>
        <div class="panel-body">
            <div class="col-sm-2">
                <a class="btn btn-block btn-primary">Save Draft</a>
            </div>
            @include('partials.forms.publish_actions')
        </div>
    </section>

    {!! Form::close() !!}

    @include('modals.add_restaurant_attribute')

@stop