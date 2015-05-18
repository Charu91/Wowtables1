@extends('.........templates.admin_layout')

@section('content')
<header class="page-header">
    <h2>Listpage Sidebar</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/admin/dashboard">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li>
                <span>
                    Promotions
                </span>
            </li>
            <li><span>Listpage Sidebar</span></li>
        </ol>
    </div>
</header>

<section class="panel col-lg-8 col-lg-offset-2">
    <header class="panel-heading">
        <h2 class="panel-title">Add New</h2>
    </header>
    {!! Form::open(['route'=>'admin.promotions.listpage_sidebar.store','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="mt-lg mb-lg form-group">
            <div class="col-sm-4">
                <button data-media-select="1" data-gallery-position="1"  data-media-type="sidebar-image" type="button" class="btn btn-primary sidebar-media-modal-btn">Select Image</button>
            </div>
            <div data-gallery-position="1" class="popup-gallery col-sm-8">
                <input name="sidebar_media_id" type="hidden" required>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('link','Link',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('link',null,['class'=>'form-control','placeholder'=>'http://www.example.com','required'=>'','id'=>'title']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('title','Title',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('title',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('description','Description',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::textarea('description',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('promotion_title','Promotion Title',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('promotion_title',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="location_id" class="col-sm-4 control-label ">City</label>
            <div class="col-sm-8">
                {!! Form::select('location_id',$cities_list,0,['class'=>'form-control populate select-location','data-plugin-selectTwo'=>'','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" value="1" id="attributes[show_in_experience]" name="show_in_experience">
                    <label for="attributes[show_in_experience]">Show in Experiences</label>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" value="1" id="attributes[show_in_alacarte]" name="show_in_alacarte">
                    <label for="attributes[show_in_alacarte]">Show in Alacarte</label>
                </div>
            </div>
        </div>
    </div>
   <footer class="panel-footer">
       {!! Form::submit('Add Sidebar',['class'=>'btn btn-primary']) !!}
       <a href="/admin/promotions/listpage_sidebar" class="btn btn-primary">Cancel</a>
   </footer>
   {!! Form::close() !!}
</section>
@stop
