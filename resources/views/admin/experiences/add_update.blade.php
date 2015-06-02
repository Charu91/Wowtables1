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


	{!! Form::model($experience['Experience'],['route'=>['AdminExperienceUpdate',$experience['Experience']->id],'method'=>'PUT','novalidate'=>'novalidate','class'=>'form-horizontal']) !!}

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
							{!! Form::text('name',$experience['Experience']->name,['class'=>'form-control','id'=>'title','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="slug">Slug <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::text('slug',$experience['Experience']->slug,['class'=>'form-control','id'=>'slug','required'=>'']) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" for="attributes[experience_info]">Experience Info <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::textarea('attributes[experience_info]',$experience['attributes']['experience_info'],['rows'=>'10','class'=>'form-control','id'=>'experienceInfo','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="attributes[experience_includes]">Experience Includes <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::textarea('attributes[experience_includes]',$experience['attributes']['experience_includes'],['rows'=>'10','class'=>'form-control','id'=>'experienceIncludes','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="attributes[short_description]">Short Description <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::textarea('attributes[short_description]',$experience['attributes']['short_description'],['class'=>'form-control','rows'=>'3','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="attributes[terms_and_conditions]">Terms & Conditions <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::textarea('attributes[terms_and_conditions]',$experience['attributes']['terms_and_conditions'],['rows'=>'5','class'=>'form-control','id'=>'terms_conditions','required'=>'']) !!}
					</div>
				</div>
			</div>

			<div id="seo_details" class="tab-pane mt-lg">
				<div class="form-group">
					<label class="col-sm-3 control-label" for="attributes[seo_title]">SEO Title <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::text('attributes[seo_title]',$experience['attributes']['seo_title'],['class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'70','required'=>'']) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" for="attributes[seo_meta_description]">SEO Meta Description <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::textarea('attributes[seo_meta_desciption]',$experience['attributes']['seo_meta_desciption'],['rows'=>'3','class'=>'form-control','data-plugin-maxlength'=>'','maxlength'=>'140','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="attributes[seo_meta_keywords]">SEO Keywords <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::text('attributes[seo_meta_keywords]',$experience['attributes']['seo_meta_keywords'],['class'=>'form-control','rows'=>'3','multiple'=>'','data-role'=>'tagsinput','data-tag-class'=>'label label-primary','required'=>'']) !!}
					</div>
				</div>

			</div>
			<div id="media_tab" class="tab-pane mt-lg">
				@include('partials.forms.add_media_experience')
				<hr/>
				@foreach($experienceMedias as $experienceMediasKey => $experienceMediasKeyValue)
					<h2>{{$experienceMediasKey}}</h2>
					@foreach($experienceMediasKeyValue as $key => $experienceMedia)
						{{--{{print_r($experienceMediasKeyValue)}}--}}
						@if($experienceMediasKey == "listing")
							{{! $setInputName = "old_media[listing_image]"}}
							{{! $setImageUrl = "listing"}}
						@elseif($experienceMediasKey == "gallery")
							{{! $setInputName = "old_media[gallery_images][]"}}
							{{! $setImageUrl = "gallery"}}
						@elseif($experienceMediasKey == "mobile")
							{{! $setInputName = "old_media[mobile]"}}
							{{! $setImageUrl = "mobile"}}
						@endif
							<img width="100" src="https://s3-eu-west-1.amazonaws.com/wowtables/uploads/{{$setImageUrl}}/{{$experienceMedia}}" class="mt-xs mb-xs mr-xs img-thumbnail img-responsive">
						<input type="hidden" name="{{$setInputName}}" value="{{$key}}"/>
					@endforeach
				@endforeach
			</div>
			<div id="pricing_details" class="tab-pane mt-lg">
				<div class="form-group">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox-custom checkbox-primary">
							<input type="checkbox" name="attributes[prepayment_allowed]" id="attributes[allow_prepayment]" value="1" {{(isset($experience['attributes']['prepayment_allowed']) && $experience['attributes']['prepayment_allowed'] == 1) ? "checked='checked'" : ""}}>
							<label  for="attributes[prepayment_allowed]">Allow Prepayment </label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-9 col-sm-offset-3">
						<div class="checkbox-custom checkbox-primary">
							<input type="checkbox" name="attributes[allow_gift_card_redemptions]" id="attributes[allow_gift_card_redemptions]" value="1" {{(isset($experience['attributes']['allow_gift_card_redemptions']) && $experience['attributes']['allow_gift_card_redemptions'] == 1) ? "checked='checked'" : ""}}>
							<label  for="attributes[allow_gift_card_redemptions]">Allow Gift Card Redemptions</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="attributes[reward_points_per_reservation]" class="col-sm-3 control-label">Reward Points per Reservation <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::text('attributes[reward_points_per_reservation]',$experience['attributes']['reward_points_per_reservation'],['class'=>'form-control','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="pricing[price]" class="col-sm-3 control-label">Price <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::text('pricing[price]',$experiencePricing->price,['class'=>'form-control','required'=>'']) !!}
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
						{!! Form::text('pricing[post_tax_price]',$experiencePricing->post_tax_price,['class'=>'form-control','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="pricing[tax]" class="col-sm-3 control-label">Tax<span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::text('pricing[tax]',$experiencePricing->tax,['class'=>'form-control','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="pricing[price_types]" class="col-sm-3 control-label">Price Types<span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::select('pricing[price_types]',$price_type_list,$experiencePricing->price_type,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="pricing[taxes]" class="col-sm-3 control-label">Taxes<span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::select('pricing[taxes]',[''=>'Select Value','Taxes Applicable'=>'Taxes Applicable','Inclusive of Taxes'=>'Inclusive of Taxes'],$experiencePricing->taxes,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="pricing[commission]" class="col-sm-3 control-label">Commissions Per Cover<span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::text('pricing[commission]',$experiencePricing->commission,['class'=>'form-control','required'=>'']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="pricing[commission_on]" class="col-sm-3 control-label">Commission On <span class="required">*</span></label>
					<div class="col-sm-6">
						{!! Form::select('pricing[commission_on]',[''=>'Select Value','Pre-Tax'=>'Pre-Tax','Post-Tax'=>'Post-Tax'],$experiencePricing->commission_on,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
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
						{!! Form::label('addon_reservation_title','Addon Reservation Title',['class'=>'col-sm-3 control-label']) !!}
						<div class="col-sm-6">
							{!! Form::text('addon_reservation_title',null,['class'=>'form-control','id'=>'addonReservationTitle']) !!}
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
							{!! Form::text('addon_commission_per_cover]',null,['class'=>'form-control','id'=>'addon_commission_per_cover']) !!}
						</div>
					</div>
					<div class="form-group">
						<label for="addon_commission_on]" class="col-sm-3 control-label">Addon Commission On <span class="required">*</span></label>
						<div class="col-sm-6">
							{!! Form::select('addon_commission_on',[''=>'Select Value','Pre-Tax'=>'Pre-Tax','Post-Tax'=>'Post-Tax'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','id'=>'addonCommissionOn']) !!}
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
							{!! Form::textarea('addon_short_description'	,null,['class'=>'form-control','rows'=>'3','id'=>'addonShortDescription']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('addons_menu','Addon Menu',['class'=>'col-sm-3 control-label']) !!}
						<div class="col-sm-6">
							{!! Form::textarea('addons_menu',null,['rows'=>'8','class'=>'form-control','id'=>'addonsMenu']) !!}
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
				<hr/>
				<h4>Previous Addons Data</h4>
				<div>
					<div class="panel-body">
						<table class="table table-striped table-responsive mb-none">
							<thead>
							<tr>
								<th>S.No</th>
								<th>Addon title</th>
								<th>Price</th>
								<th>Post Tax Price</th>
								<th>Addon Short Description</th>
								<th>Check to Inactive</th>
							</tr>
							</thead>
							<tbody>

							@foreach($experienceAddons as $key => $experienceAddon)
								<tr>
									<th>{{$key}}</th>
									<th>{{$experienceAddon['addOnsName']}}</th>
									<th>{{$experienceAddon['price']}}</th>
									<th>{{$experienceAddon['post_tax_price']}}</th>
									<th>{{$experienceAddon['short_description']}}</th>
									<th><input type="checkbox" {{ (($experienceAddon['status'] == "Publish") ? "value=1" : "value=0")}} {{ (($experienceAddon['status'] == "Inactive") ? 'checked="checked" ' : ' ')  }} class="inactive_addon" data-addon_id="{{$key}}"/> <span class="addon_change_status_{{$key}}">({{$experienceAddon['status']}})</span></th>
									<input type="hidden" id="addonTitle_{{$key}}" name="old_addons[{{$key}}][name]" value="{{$experienceAddon['addOnsName']}}" />
									<input type="hidden" id="addon_reservation_title_{{$key}}" name="old_addons[{{$key}}][reservation_title]" value="{{$experienceAddon['reservation_title']}}" />
									<input type="hidden" id="addonPriceBeforeTax_{{$key}}" name="old_addons['{{$key}}'][price]" value="{{$experienceAddon['price']}}" />
									<input type="hidden" id="addon_tax_{{$key}}" name="old_addons[{{$key}}][tax]" value="{{$experienceAddon['tax']}}" />
									<input type="hidden" id="addonPriceAfterTax_{{$key}}" name="old_addons[{{$key}}][post_tax_price]" value="{{$experienceAddon['post_tax_price']}}" />
									<input type="hidden" id="addon_commission_per_cover_{{$key}}" name="old_addons[{{$key}}][commission_per_cover]" value="{{$experienceAddon['commission']}}" />
									<input type="hidden" id="addon_commission_on_{{$key}}" name="old_addons[{{$key}}][commission_on]" value="{{$experienceAddon['commission_on']}}" />
									<input type="hidden" id="addon_short_description_{{$key}}" name="old_addons[{{$key}}][short_description]" value="{{$experienceAddon['short_description']}}" />
									<input type="hidden" id="addon_addonsMenu_{{$key}}" name="old_addons[{{$key}}][addonsMenu]" value="{{$experienceAddon['menu']}}" />
								</tr>
							@endforeach
							</tbody>
						</table>
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
							{!! Form::textarea('attributes[menu]',null,['rows'=>'30','class'=>'form-control','id'=>'expMenu']) !!}
						</div>
					</div>
				</div>
				{!! Form::hidden('attributes[menu_markdown]','',['id'=>'expMarkdownMenu']) !!}
				<hr/>
				{!! Form::textarea('attributes[old_menu_markdown]',$experience['attributes']['menu_markdown'],['class'=>'form-control','readonly'=>'readonly','id'=>'oldMarkdownSyntax']) !!}
				{!! Form::textarea('attributes[old_menu]',null,['class'=>'form-control','readonly'=>'readonly','id'=>'oldExpMenu']) !!}
			</div>

			<div id="miscellaneous_tab" class="tab-pane mt-lg">
				<div class="form-group">
					<label for="attributes[start_date]" class="col-sm-3 control-label">Start Date <span class="required">*</span></label>
					<div class="col-sm-2">
						{{! $set_start_date = ($experience['attributes']['start_date'] != "-0001-11-30 00:00:00" ? date('Y-m-d', strtotime($experience['attributes']['start_date'])) : ' ') }}
						{!! Form::text('attributes[start_date]',$set_start_date,['class'=>'form-control addDatepicker','placeholder'=>'Select Start Date']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="attributes[end_date]" class="col-sm-3 control-label">End Date <span class="required">*</span></label>
					<div class="col-sm-2">
						{{! $set_end_date = ($experience['attributes']['end_date'] !="-0001-11-30 00:00:00" ? date('Y-m-d', strtotime($experience['attributes']['end_date'])) : ' ') }}
						{!! Form::text('attributes[end_date]',$set_end_date,['class'=>'form-control addDatepicker','placeholder'=>'Select Start Date']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="attributes[cuisine][]" class="col-sm-3 control-label">Cuisines <span class="required">*</span></label>
					<div class="col-sm-6">
						{{! $set_cuisines = (isset($experience['attributes']['cuisines']) && $experience['attributes']['cuisines'] !="" ? $experience['attributes']['cuisines'] : ' ') }}
						{!! Form::select('attributes[cuisines][]',$cuisines,$set_cuisines,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="flags" class="col-sm-3 control-label">Flags <span class="required">*</span></label>
					<div class="col-sm-6">
						{{! $set_flags = (isset($experienceFlags->flag_id) && $experienceFlags->flag_id !="" ? $experienceFlags->flag_id : ' ')}}
						{{--{!! Form::select('flags',$flags_list,$experienceFlags->flag_id,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}--}}
						{!! Form::text('attributes[flags]',$set_flags,['class'=>'form-control populate flags-select-box flagsList']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="tags" class="col-sm-3 control-label">Tags <span class="required">*</span></label>
					<div class="col-sm-6">
						{{! $set_tags = (isset($experienceTags->tag_id) && $experienceTags->tag_id !="" ? $experienceTags->tag_id : ' ')}}
						{!! Form::select('tags[]',$tags_list,$set_tags,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'','multiple'=>'multiple']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="curator" class="col-sm-3 control-label">Guest Curator <span class="required">*</span></label>
					<div class="col-sm-6">
						{{--{!! Form::select('curators',$curator_list,$experienceCurator->curator_id,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}--}}
						{{! $set_curators = (isset($experienceCurator->curator_id) && $experienceCurator->curator_id !="" ? $experienceCurator->curator_id : ' ')}}
						{!! Form::text('curators',$set_curators,['class'=>'form-control populate curators-select-box curatorsList']) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="attributes[curator_tip]" class="col-sm-3 control-label">Curator Tips </label>
					<div class="col-sm-6">
						{!! Form::textarea('attributes[curator_tip]',$experience['attributes']['curator_tip'],['class'=>'form-control redactor-text','required'=>'']) !!}
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