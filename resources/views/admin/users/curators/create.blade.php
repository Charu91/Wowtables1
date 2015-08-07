@extends('templates.admin_layout')

@section('content')
<header class="page-header">
    <h2>Curators</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/admin/dashboard">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li>
                <a href="/admin/users/">
                    Users
                </a>
            </li>
            <li><span>Curators</span></li>
        </ol>
    </div>
</header>

<section class="panel col-lg-8 col-lg-offset-2">
    <header class="panel-heading">
        <h2 class="panel-title">Add New</h2>
    </header>
    {!! Form::open(['route'=>'admin.user.curators.store','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('name','Name',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('name',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('designation','Designation',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('designation',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="location_id" class="col-sm-4 control-label ">City <span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::select('location_id',$cities_list,0,['class'=>'form-control populate select-location','data-plugin-selectTwo'=>'','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('link','Link',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('link',null,['class'=>'form-control','required'=>'','placeholder'=>'http://www.example.com']) !!}
            </div>
        </div>
        <!--<div class="mt-lg mb-lg form-group">
            <div class="col-sm-4">
                <button data-media-select="1" data-gallery-position="1"  data-media-type="single-media-image" type="button" class="btn btn-primary media-modal-btn">Select Image</button>
            </div>
            <div data-gallery-position="1" class="popup-gallery col-sm-8">
                <input name="media_id" type="hidden" required>
            </div>
        </div>-->
        <div class="form-group">
            {!! Form::label('bio','Bio',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::textarea('bio',null,['class'=>'form-control redactor-text','required'=>'']) !!}
            </div>
        </div>
    </div>
   <footer class="panel-footer">
       {!! Form::submit('Add Curator',['class'=>'btn btn-primary']) !!}
   </footer>
   {!! Form::close() !!}
</section>
@stop
