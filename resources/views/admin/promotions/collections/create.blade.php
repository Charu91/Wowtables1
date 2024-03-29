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
        {{--<div class="mt-lg mb-lg form-group">--}}
            {{--<div class="col-sm-4">--}}
                {{--<button data-media-select="1" data-gallery-position="1"  data-media-type="single-media-image" type="button" class="btn btn-primary collection-media-modal-btn">Select Banner Image</button>--}}
            {{--</div>--}}
            {{--<div data-gallery-position="1" class="popup-gallery col-sm-8">--}}
                {{--<input name="media_id" type="hidden" required>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="mt-lg mb-lg ml-lg form-group">
            <div class="col-md-5">
                <span class="required">*</span>
                <button data-media-select="1" data-gallery-position="4" data-media-type="mobile_listing_image" type="button" class="btn btn-success media-modal-btn-mobile-listing-experience" ><span class="fa fa-plus"></span>&nbsp;Mobile Images (1)</button>
            </div>
            <div data-gallery-position="4" class="popup-gallery">
                @if( Input::old('media.mobile_listing_image') )
                    {!! Form::hidden('media[mobile]',null) !!}
                @else
                    <input name="media[mobile]" type="hidden" required>
                @endif
            </div>
        </div>
        <div class="mt-lg mb-lg ml-lg form-group">
            <div class="col-md-5">
                <span class="required">*</span>
                <button data-media-select="1" data-gallery-position="5" data-media-type="web_images" type="button" class="btn btn-success media-modal-btn-web-collection-images" ><span class="fa fa-plus"></span>&nbsp;Web Images (1)</button>
            </div>
            <div data-gallery-position="5" class="popup-gallery">
                @if( Input::old('media.web_collection') )
                    {!! Form::hidden('media[web_collection]',null) !!}
                @else
                    <input name="media[web_collection]" type="hidden" required>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="status">&nbsp;&nbsp;&nbsp;Status &nbsp;&nbsp;&nbsp;</label>
            <div class="radio-custom radio-success radio-inline">
                <input type="radio" name="status" value="available" checked="checked">
                <label for="available">Available</label>
            </div>
            <div class="radio-custom radio-danger radio-inline mt-none">
                <input type="radio" name="status" value="discontinued" >
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

            <div class="col-sm-4">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" value="1" id="hide_in_mobile" name="hide_in_mobile">
                    <label for="hide_in_mobile">Hide in Mobile</label>
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
