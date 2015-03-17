@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Create Experience</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="/admin/experiences/">
                        Experiences
                    </a>
                </li>
                <li><span>Create Experience</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right">
                <i class="fa fa-chevron-left"></i></a>
        </div>
    </header>


    {!! Form::open(['route'=>'AdminExperienceStore','class'=>'form-horizontal','novalidate'=>'novalidate']) !!}

    <div class="tabs tabs-primary">
        <ul class="nav nav-tabs nav-justified">
            <li class="active">
                <a href="#basic_details" data-toggle="tab" class="text-center">Basic Details</a>
            </li>
            <li>
                <a href="#seo_details" data-toggle="tab" class="text-center">SEO Details</a>
            </li>
            <li>
                <a href="#media_tab" data-toggle="tab" class="text-center">Media</a>
            </li>
            <li>
                <a href="#pricing_details" data-toggle="tab" class="text-center">Pricing</a>
            </li>
            <li>
                <a href="#addon_details" data-toggle="tab" class="text-center">Addons</a>
            </li>
            <li>
                <a href="#menu_details" data-toggle="tab" class="text-center">Menu Details</a>
            </li>
            <li>
                <a href="#miscellaneous_tab" data-toggle="tab" class="text-center">Miscellaneous</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">Experience Name <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('name',null,['class'=>'form-control','id'=>'title','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="slug">Slug <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('slug',null,['class'=>'form-control','id'=>'slug','required'=>'']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="experience_info">Experience Info <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('experience_info',null,['rows'=>'10','class'=>'form-control','id'=>'experienceInfo','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="experience_includes">Experience Includes <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('experience_includes',null,['rows'=>'10','class'=>'form-control','id'=>'experienceIncludes','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="short_description">Short Description <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('short_description',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="terms_and_conditions">Terms & Conditions <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('terms_and_conditions',null,['rows'=>'5','class'=>'form-control','id'=>'terms_conditions','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                @include('partials.forms.seo_details')
            </div>
            <div id="media_tab" class="tab-pane mt-lg">
                @include('partials.forms.add_media')
            </div>
            <div id="pricing_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="attributes[allow_prepayment]" id="attributes[allow_prepayment]" value="1" checked="">
                            <label  for="attributes[allow_prepayment]">Allow Prepayment <span class="required">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="attributes[allow_gift_card_redemptions]" id="attributes[allow_gift_card_redemptions]" value="1" checked="">
                            <label  for="attributes[allow_gift_card_redemptions]">Allow Gift Card Redemptions <span class="required">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[reward_points_per_reservation]" class="col-sm-3 control-label">Reward Points per Reservation <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[reward_points_per_reservation]',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="price_before_tax" class="col-sm-3 control-label">Price Before Tax <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('price_before_tax',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <!--<div class="form-group">
                    <label for="tax" class="col-sm-3 control-label">Tax <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('tax',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>-->
                <div class="form-group">
                    <label for="price_after_tax" class="col-sm-3 control-label">Price After Tax <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('price_after_tax',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="addon_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-1">
                        <a class="btn btn-primary" id="addNewExperienceAddonBtn" >Add New Addon </a>
                    </div>
                </div>
                <div id="experienceAddonForm">
                    <div class="form-group">
                        {!! Form::label('','Addon Title',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('',null,['class'=>'form-control','id'=>'addonTitle']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('addon_price_before_tax','Addon Price Before Tax',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('',null,['class'=>'form-control','id'=>'addonPriceBeforeTax']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','Addon Price After Tax',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('',null,['class'=>'form-control','id'=>'addonPriceAfterTax']) !!}
                        </div>
                    </div>
                    <!--<div class="form-group">
                         {!! Form::label('','Addon Tax',['class'=>'col-sm-3 control-label']) !!}
                         <div class="col-sm-6">
                             {!! Form::text('',null,['class'=>'form-control','id'=>'addonTax']) !!}
                         </div>-->
                    <div class="form-group">
                        {!! Form::label('','Addon Info',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('',null,['class'=>'form-control','rows'=>'3','id'=>'addonInfo']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('addon_menu','Addon Menu',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('addon_menu',null,['class'=>'form-control','rows'=>'5']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="row">
                                <a class="btn btn-primary" id="addExperienceAddonBtn" >Add Addon</a>
                                <a class="btn btn-primary" id="cancelUpdateExperienceAddonBtn" >Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partials.forms.experience_addon')
            </div>
            <div id="menu_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-1">
                        <a id="expMenuBtn" data-target="#markdownmodal" data-toggle="modal" class="btn btn-primary">Create Experience Menu</a>
                    </div>
                </div>
                <div id="experienceMenuHolder">
                    <div class="form-group">
                        <label for="attributes[menu]" class="col-sm-2 control-label">Experience Menu <span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::textarea('attributes[menu]',null,['rows'=>'30','class'=>'form-control','required'=>'','id'=>'expMenu']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div id="miscellaneous_tab" class="tab-pane mt-lg">
                <div class="form-group">
                    <label for="cuisines" class="col-sm-3 control-label">Cuisines <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('cuisine',[''=>'','1'=>'Italian','2'=>'French'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="flags" class="col-sm-3 control-label">Flags <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('flags',[''=>'','1'=>'New','2'=>'Valentines Special'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="tags" class="col-sm-3 control-label">Tags <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('tags',['1'=>'Popular','2'=>'Featured'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="curator" class="col-sm-3 control-label">Guest Curator <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('curator',[''=>'','1'=>'Manoj','2'=>'Satheesh'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="curator_tips" class="col-sm-3 control-label">Curator Tips <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('curator_tips',null,['class'=>'form-control redactor-text','required'=>'']) !!}
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
                    {!! Form::text('publish_date',date('Y-m-d'),['class'=>'form-control','id'=>'simpleExperienceDatePicker']) !!}
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="publish_time" class="col-sm-4 control-label">Time <span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('publish_time',null,['class'=>'form-control','id'=>'simpleExperienceTimePicker']) !!}
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

    {!! Form::close()  !!}
@stop