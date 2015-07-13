@extends('templates.admin_layout')

@section('content')
<header class="page-header">
    <h2>Email Footer Promotions</h2>
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
            <li><span>Email Footer Promotions</span></li>
        </ol>
    </div>
</header>

<section class="panel col-lg-8 col-lg-offset-2">
    <?php //echo "<pre>"; print_r($email_footer_promotions); die;?>
    <header class="panel-heading">
        <h2 class="panel-title">Edit</h2>
    </header>
    {!! Form::model($email_footer_promotions,['route'=>['admin.promotions.email_footer_promotions.update',$email_footer_promotions->id],'method'=>'PUT','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('link','Link',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('link',null,['class'=>'form-control','placeholder'=>'http://www.example.com','required'=>'','id'=>'title']) !!}
            </div>
        </div>
        <div class="mt-lg mb-lg form-group">
            <div class="col-sm-4">
                <button data-media-select="1" data-gallery-position="1"  data-media-type="single-media-image" type="button" class="btn btn-primary media-modal-btn">Select Image</button>
            </div>
            <div data-gallery-position="1" class="popup-gallery col-sm-8">
                <input name="media_id" type="hidden" required value="{{$email_footer_promotions->media_id}}">
                <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="https://s3-eu-west-1.amazonaws.com/wowtables/uploads/email_footer_promotions/{!! $email_footer_promotions->media->file !!}" width="100">
            </div>
        </div>
        <div class="form-group">
            <label for="location_id" class="col-sm-4 control-label ">City</label>
            <div class="col-sm-8">
                {!! Form::select('location_id',$cities_list,$email_footer_promotions->city_id,['class'=>'form-control populate select-location','data-plugin-selectTwo'=>'','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" value="1" id="attributes[show_in_experience]" name="show_in_experience" @if($email_footer_promotions->show_in_experience == 1) checked="checked" @else @endif>
                    <label for="attributes[show_in_experience]">Show in Experiences</label>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" value="1" id="attributes[show_in_alacarte]" name="show_in_alacarte" @if($email_footer_promotions->show_in_alacarte == 1) checked="checked" @else @endif>
                    <label for="attributes[show_in_alacarte]">Show in Alacarte</label>
                </div>
            </div>
        </div>
    </div>
   <footer class="panel-footer">
       {!! Form::submit('Update',['class'=>'btn btn-primary']) !!}
        <a href="/admin/promotions/email_footer_promotions" class="btn btn-primary">Cancel</a>
   </footer>
   {!! Form::close() !!}
</section>
@stop