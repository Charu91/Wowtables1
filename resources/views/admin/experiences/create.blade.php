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
                    <label class="col-sm-3 control-label" for="attributes[experience_info]">Experience Info <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[experience_info]',null,['rows'=>'10','class'=>'form-control','id'=>'experienceInfo','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="attributes[experience_includes]">Experience Includes <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[experience_includes]',null,['rows'=>'10','class'=>'form-control','id'=>'experienceIncludes','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="attributes[short_description]">Short Description <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[short_description]',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="attributes[terms_and_conditions]">Terms & Conditions <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[terms_and_conditions]',null,['rows'=>'5','class'=>'form-control','id'=>'terms_conditions','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="attributes[seo_title]">SEO Title <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('attributes[seo_title]',null,['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="attributes[seo_meta_description]">SEO Meta Description <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[seo_meta_description]',null,['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="attributes[seo_meta_keywords]">SEO Keywords <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[seo_meta_keywords][]',[''=>''],null,['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'']) !!}
                    </div>
                </div>

            </div>
            <div id="media_tab" class="tab-pane mt-lg">
                @include('partials.forms.add_media_experience')
            </div>
            <div id="pricing_details" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="attributes[prepayment_allowed]" id="attributes[allow_prepayment]" value="1" checked="">
                            <label  for="attributes[prepayment_allowed]">Allow Prepayment </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="attributes[allow_gift_card_redemptions]" id="attributes[allow_gift_card_redemptions]" value="1" checked="">
                            <label  for="attributes[allow_gift_card_redemptions]">Allow Gift Card Redemptions</label>
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
                    <label for="pricing[price]" class="col-sm-3 control-label">Price <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('pricing[price]',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <!--<div class="form-group">
                    <label for="tax" class="col-sm-3 control-label">Tax <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('tax',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>-->
                <div class="form-group">
                    <label for="pricing[post_tax_price]" class="col-sm-3 control-label">Post Tax Price <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('pricing[post_tax_price]',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="pricing[tax]" class="col-sm-3 control-label">Tax<span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('pricing[tax]',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="pricing[price_types]" class="col-sm-3 control-label">Price Types<span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('pricing[price_types]',$price_type_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="pricing[taxes]" class="col-sm-3 control-label">Taxes<span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('pricing[taxes]',[''=>'Select Value','Inclusive-Taxes'=>'Inclusive-Taxes','Exclusive-Taxes'=>'Exclusive-Taxes'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="pricing[commission_per_cover]" class="col-sm-3 control-label">Commissions Per Cover<span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('pricing[commission_per_cover]',null,['class'=>'form-control','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="pricing[commission_on]" class="col-sm-3 control-label">Commission On <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('pricing[commission_on]',[''=>'Select Value','Pre-Tax'=>'Pre-Tax','Post-Tax'=>'Post-Tax'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
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
                        {!! Form::label('addon_title','Addon Title',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('addon_title',null,['class'=>'form-control','id'=>'addonTitle']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('addon_price_before_tax','Addon Price',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('addon_price_before_tax',null,['class'=>'form-control','id'=>'addonPrice']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('addon_tax','Addon Tax',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('addon_tax',null,['class'=>'form-control','id'=>'addon_tax']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('addon_post_tax_price','Addon Post Tax Price',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('addon_post_tax_price',null,['class'=>'form-control','id'=>'addonPriceAfterTax']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addon_commission_per_cover" class="col-sm-3 control-label">Addon Commissions Per Cover<span class="required">*</span></label>
                        <div class="col-sm-6">
                            {!! Form::text('addon_commission_per_cover]',null,['class'=>'form-control','required'=>'','id'=>'addon_commission_per_cover']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addon_commission_on]" class="col-sm-3 control-label">Addon Commission On <span class="required">*</span></label>
                        <div class="col-sm-6">
                            {!! Form::select('addon_commission_on',[''=>'Select Value','Pre-Tax'=>'Pre-Tax','Post-Tax'=>'Post-Tax'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','id'=>'addonCommissionOn']) !!}
                        </div>
                    </div>
                    <!--<div class="form-group">
                         {!! Form::label('','Addon Tax',['class'=>'col-sm-3 control-label']) !!}
                         <div class="col-sm-6">
                             {!! Form::text('',null,['class'=>'form-control','id'=>'addonTax']) !!}
                         </div>-->
                    <div class="form-group">
                        {!! Form::label('addon_short_description','Addon Short Description',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('addon_short_description',null,['class'=>'form-control','rows'=>'3','id'=>'addonShortDescription']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('addons_menu','Addon Menu',['class'=>'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('addons_menu',null,['rows'=>'8','class'=>'form-control','required'=>'','id'=>'addonsMenu']) !!}
                        </div>
                        <!--<div class="col-sm-6">
                            <a id="expMenuBtn" data-target="#markdownmodal" data-toggle="modal" class="btn btn-primary">Create Experience Menu</a>
                        </div>
                        <div id="addonsMenuHolder">
                            <label for="attributes[menu]" class="col-sm-2 control-label">Addons Menu <span class="required">*</span></label>
                            <div class="col-sm-6">
                                {!! Form::textarea('addons_menu',null,['rows'=>'30','class'=>'form-control','required'=>'','id'=>'addonsMenu']) !!}
                            </div>
                        </div>-->
                        <!--<div class="col-sm-6">
                        {!! Form::label('addon_menu','Addon Menu',['class'=>'col-sm-3 control-label']) !!}

                            {!! Form::textarea('addon_menu',null,['class'=>'form-control','rows'=>'5']) !!}
                        </div>-->
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
                {!! Form::hidden('attributes[menu_markdown]','',['id'=>'expMarkdownMenu']) !!}
            </div>
            <div id="miscellaneous_tab" class="tab-pane mt-lg">
                <div class="form-group">
                    <label for="attributes[start_date]" class="col-sm-3 control-label">Start Date <span class="required">*</span></label>
                    <div class="col-sm-2">
                        {!! Form::text('attributes[start_date]','',['class'=>'form-control addDatepicker','placeholder'=>'Select Start Date']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[end_date]" class="col-sm-3 control-label">End Date <span class="required">*</span></label>
                    <div class="col-sm-2">
                        {!! Form::text('attributes[end_date]','',['class'=>'form-control addDatepicker','placeholder'=>'Select Start Date']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[cuisine][]" class="col-sm-3 control-label">Cuisines <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('attributes[cuisines][]',$cuisines,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="flags" class="col-sm-3 control-label">Flags <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('flags[]',$flags_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="tags" class="col-sm-3 control-label">Tags <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('tags[]',$tags_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="curator" class="col-sm-3 control-label">Guest Curator <span class="required">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::select('curators[]',$curator_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="attributes[curator_tip]" class="col-sm-3 control-label">Curator Tips </label>
                    <div class="col-sm-6">
                        {!! Form::textarea('attributes[curator_tip]',null,['class'=>'form-control redactor-text','required'=>'']) !!}
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