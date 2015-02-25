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

    {!! Form::open(['route'=>'AdminRestaurantStore','id'=>'addNewRestaurantForm','novalidate'=>'novalidate']) !!}

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
                {!! Form::label('status','Status',['class'=>'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('status',['Draft'=>'Draft','Published'=>'Published'],'Draft',['class'=>'form-control','required'=>'']) !!}
                </div>
            </div>
        </div>
    </section>

    <section  class="panel">
        <header class="panel-heading">
            <a class="btn btn-primary" id="addNewRestaurantAttributeBtn">Add New Attribute</a>
            <h2 class="panel-title pull-right">Restaurant Attributes</h2>
        </header>
        <div class="panel-body">
            <div id="addRestaurantAttributesHolder"></div>
        </div>
    </section>

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