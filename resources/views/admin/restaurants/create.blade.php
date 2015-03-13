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

    {!! Form::open(['route'=>'AdminRestaurantStore','novalidate'=>'novalidate']) !!}

    <section  class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Restaurant Details</h2>
        </header>
        <div class="panel-body">
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
                <label for="attributes[seo_title]" class="col-sm-3 col-sm-offset-1 control-label">SEO Title <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('attributes[seo_title]',null,['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="attributes[seo_meta_description]" class="col-sm-3 col-sm-offset-1 control-label">SEO Meta Description <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::textarea('attributes[seo_meta_description]',null,['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="attributes[seo_meta_keywords]" class="col-sm-3 col-sm-offset-1 control-label">SEO Keywords <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::text('attributes[seo_meta_keywords]',null,['class'=>'form-control','data-role'=>'tagsinput','data-tag-class'=>'label label-primary']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="attributes[short_description]" class="col-sm-3 col-sm-offset-1 control-label">Short Description <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::textarea('attributes[short_description]',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="attributes[restaurant_info]" class="col-sm-3 col-sm-offset-1 control-label">Restaurant Info <span class="required">*</span></label>
                <div class="col-sm-6">
                    {!! Form::textarea('attributes[restaurant_info]',null,['rows'=>'7','class'=>'form-control','id'=>'description','required'=>'']) !!}
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
            <div id="addRestaurantAttributesHolder"></div>
        </div>
    </section>-->

    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Publish Actions</h2>
        </header>
        <div class="panel-body">
            <div class="form-group col-md-3">
                <label for="publish_date" class="col-sm-4 control-label">Date <span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('publish_date',date('Y-m-d'),['class'=>'form-control','id'=>'restaurantDatePicker']) !!}
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="publish_time" class="col-sm-4 control-label">Time <span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('publish_time',null,['class'=>'form-control','id'=>'restaurantTimePicker']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="status">&nbsp;&nbsp;&nbsp;Status <span class="required">*</span>&nbsp;&nbsp;&nbsp;</label>
                <div class="radio-custom radio-success radio-inline">
                    <input type="radio" id="Publish" name="status" value="Publish">
                    <label for="Publish">Publish</label>
                </div>
                <div class="radio-custom radio-danger radio-inline">
                    <input type="radio" id="Draft" name="status" value="Draft" checked="checked">
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