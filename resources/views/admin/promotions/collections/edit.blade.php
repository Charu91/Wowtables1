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
        <h2 class="panel-title">Edit</h2>
    </header>
    {!! Form::model($collection,['route'=>['admin.promotions.collections.update',$collection->id],'method'=>'PUT','novalidate'=>'novalidate']) !!}
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
                {{--<button data-media-select="1" data-gallery-position="1"  data-media-type="single-media-image" type="button" class="btn btn-primary media-modal-btn">Select Banner Image</button>--}}
            {{--</div>--}}
            {{--<div data-gallery-position="1" class="popup-gallery col-sm-8">--}}
                {{--<input name="media_id" type="hidden" required value="{{$collection->media_id}}">--}}
                {{--<img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="https://s3-eu-west-1.amazonaws.com/wowtables/uploads/listing/{!! $collection->media->file !!}" width="100">--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="mt-lg mb-lg ml-lg form-group">
            <div class="col-md-5">
                <span class="required">*</span>
                <button data-media-select="1" data-gallery-position="4" data-media-type="mobile_listing_image" type="button" class="btn btn-success media-modal-btn media-modal-btn-mobile-listing-experience" ><span class="fa fa-plus"></span>&nbsp;Mobile Images (1)</button>
            </div>
            <div data-gallery-position="4" class="popup-gallery">
                <input name="media[mobile]" type="hidden" required value="{{$collection->media_id}}">
                <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="https://s3-eu-west-1.amazonaws.com/wowtables/uploads/mobile/{!! $collection->media->file !!}" width="100">
            </div>
        </div>
        <div class="mt-lg mb-lg ml-lg form-group">
            <div class="col-md-5">
                <span class="required">*</span>
                <button data-media-select="1" data-gallery-position="5" data-media-type="web_images" type="button" class="btn btn-success media-modal-btn-web-collection-images" ><span class="fa fa-plus"></span>&nbsp;Web Images (1)</button>
            </div>
            <div data-gallery-position="5" class="popup-gallery">
                <input name="media[web_collection]" type="hidden" required value="{{$collection->web_media_id}}">
                <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="https://s3-eu-west-1.amazonaws.com/wowtables/uploads/collections/{!! $collection->collection_media->file !!}" width="100">
            </div>
        </div>
        <div class="form-group">
            <label for="status">&nbsp;&nbsp;&nbsp;Status &nbsp;&nbsp;&nbsp;</label>
            <div class="radio-custom radio-success radio-inline">
                <input type="radio" name="status" value="available" @if($collection->status == 'available') checked="checked" @else @endif>
                <label for="available">Available</label>
            </div>
            <div class="radio-custom radio-danger radio-inline mt-none">
                <input type="radio" name="status" value="discontinued" @if($collection->status == 'discontinued') checked="checked" @else @endif>
                <label for="discontinued">Discontinued</label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" value="1" id="attributes[show_in_experience]" name="show_in_experience" @if($collection->show_in_experience == 1) checked="checked" @else @endif>
                    <label for="attributes[show_in_experience]">Show in Experiences</label>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" value="1" id="attributes[show_in_alacarte]" name="show_in_alacarte" @if($collection->show_in_alacarte == 1) checked="checked" @else @endif>
                    <label for="attributes[show_in_alacarte]">Show in Alacarte</label>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" value="1" id="hide_in_mobile" name="hide_in_mobile" @if($collection->hide_in_mobile == 1) checked="checked" @else @endif>
                    <label for="hide_in_mobile">Hide in Mobile</label>
                </div>
            </div>
        </div>
    </div>
   <footer class="panel-footer">
       {!! Form::submit('Update',['class'=>'btn btn-primary']) !!}
        <a href="/admin/promotions/collections" class="btn btn-primary">Cancel</a>
   </footer>
   {!! Form::close() !!}
</section>
@stop
