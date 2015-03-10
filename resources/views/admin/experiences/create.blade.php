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
                <a href="#limits_tab" data-toggle="tab" class="text-center">Limits</a>
            </li>
            <li>
                <a href="#pricing_details" data-toggle="tab" class="text-center">Pricing</a>
            </li>
            <li>
                <a href="#addon_details" data-toggle="tab" class="text-center">Addons</a>
            </li>
            <li>
                <a href="#variant_details" data-toggle="tab" class="text-center">Variants</a>
            </li>
            <li>
                <a href="#menu_details" data-toggle="tab" class="text-center">Menu</a>
            </li>
            <li>
                <a href="#miscellaneous_tab" data-toggle="tab" class="text-center">Miscellaneous</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                <div class="form-group">
                    <label for="restaurant_id" class="col-sm-3 control-label">Select Restaurant <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('restaurant_id',$restaurants_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="restaurant_locations[]" class="col-sm-3 control-label">Select Restaurant Locations <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('restaurant_locations[]',['0'=>'None','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('name','Experience Name',['class'=>'col-sm-3 control-label']) !!}
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
                    {!! Form::label('short_description','Short Description',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('short_description',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('description','Description',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('description',null,['rows'=>'10','class'=>'form-control','id'=>'description','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                @include('partials.forms.seo_details')
            </div>
            <div id="media_tab" class="tab-pane mt-lg">
                @include('partials.forms.add_media')
            </div>
            <div id="limits_tab" class="tab-pane mt-lg">
                @include('partials.forms.schedule_limits')
            </div>
            <div id="pricing_details" class="tab-pane mt-lg">
                @include('partials.forms.payment_details')
                <div class="form-group">
                    {!! Form::label('tax','Tax',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('tax',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('price_before_tax','Price Before Tax',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('price_before_tax',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('price_after_tax','Price After Tax',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::text('price_after_tax',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('price_type','Price Type',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('price_type',[''=>'','per_person'=>'Per Person','per_couple'=>'Per Couple','per_group'=>'Per Group'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('commission_calculated_on','Commission Calculated On',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('commission_calculated_on',[''=>'','before_tax'=>'Before Tax','after_tax'=>'After Tax'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="addon_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-1">
                        <a class="btn btn-primary" id="addNewExperienceAddonBtn" >Add New Addon</a>
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
                    <div class="form-group">
                        {!! Form::label('','Addon Tax',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('',null,['class'=>'form-control','id'=>'addonTax']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','Addon Info',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('',null,['class'=>'form-control','rows'=>'3','id'=>'addonInfo']) !!}
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
            <div id="variant_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-1">
                        <a class="btn btn-primary" id="addNewVariantBtn" >Add New Variant</a>
                    </div>
                </div>
                <div id="experienceVariantForm">
                    <div class="form-group">
                        {!! Form::label('','Variant Title',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('',null,['class'=>'form-control','id'=>'VariantTitle']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('variant_price_before_tax','Variant Price Before Tax',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('',null,['class'=>'form-control','id'=>'VariantPriceBeforeTax']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','Variant Price After Tax',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('',null,['class'=>'form-control','id'=>'VariantPriceAfterTax']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','Variant Tax',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('',null,['class'=>'form-control','id'=>'VariantTax']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','Variant Info',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('',null,['class'=>'form-control','rows'=>'3','id'=>'VariantInfo']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="row">
                                <a class="btn btn-primary" id="addExperienceVariantBtn" >Add Variant</a>
                                <a class="btn btn-primary" id="cancelUpdateExperienceVariantBtn" >Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
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
                    {!! Form::label('allow_gift_card_redemption','Allow Gift Card Redemption',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('allow_gift_card_redemption',[''=>'',true=>'Yes',false=>'No'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('allow_cancellations','Allow Cancellations',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('allow_cancellations',[''=>'',true=>'Yes',false=>'No'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('terms_conditions','Terms & Conditions',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('terms_conditions',null,['rows'=>'5','class'=>'form-control','id'=>'terms_conditions','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('status','Status',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::hidden('product_type_id','1') !!}
                        {!! Form::select('status',[''=>'','Publish'=>'Published','Draft'=>'Draft'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
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
            <div class="col-sm-2">
                <a class="btn btn-block btn-primary">Save Draft</a>
            </div>
            @include('partials.forms.publish_actions')
        </div>
    </section>


    {!! Form::close()  !!}
@stop