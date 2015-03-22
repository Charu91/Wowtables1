@extends('.........templates.admin_layout')

@section('content')
<header class="page-header">
    <h2>Collections</h2>
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
            <li><span>Collections</span></li>
        </ol>
    </div>
</header>

<section class="panel col-lg-8 col-lg-offset-2">
    <header class="panel-heading">
        <h2 class="panel-title">Add New</h2>
    </header>
    {!! Form::open(['route'=>'admin.promotions.collections.store','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('name','Name',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('name',null,['class'=>'form-control','required'=>'','id'=>'title']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('slug','Slug',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('slug',null,['class'=>'form-control','required'=>'','id'=>'slug']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('description','Description',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::textarea('description',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('seo_title','Seo Title',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('seo_title',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('seo_meta_description','Seo Meta Description',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('seo_meta_description',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('seo_meta_keywords','Seo Meta Keywords',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('seo_meta_keywords',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="mt-lg mb-lg form-group">
            <div class="col-sm-4">
                <button data-media-select="1" data-gallery-position="1"  data-media-type="single-media-image" type="button" class="btn btn-primary media-modal-btn">Select Banner Image</button>
            </div>
            <div data-gallery-position="1" class="popup-gallery col-sm-8">
                <input name="media_id" type="hidden" required>
            </div>
        </div>
        <div class="form-group">
            <label for="status">&nbsp;&nbsp;&nbsp;Status &nbsp;&nbsp;&nbsp;</label>
            <div class="radio-custom radio-success radio-inline">
                <input type="radio" name="status" value="available" checked="checked">
                <label for="available">Available</label>
            </div>
            <div class="radio-custom radio-danger radio-inline mt-none">
                <input type="radio" name="status" value="Discontinued" >
                <label for="discontinued">Discontinued</label>
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
       {!! Form::submit('Add Collection',['class'=>'btn btn-primary']) !!}
   </footer>
   {!! Form::close() !!}
</section>
@stop
