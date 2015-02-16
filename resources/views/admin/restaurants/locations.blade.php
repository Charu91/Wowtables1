@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Create Restaurant Location</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Restaurant Locations</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right">
                <i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

{!! Form::open(['route'=>'AdminRestaurantLocationsStore','class'=>'form-horizontal validate-form','id'=>'addNewRestaurantLocationForm']) !!}

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
                <a href="#schedule_tab" data-toggle="tab" class="text-center">Scheduling</a>
            </li>
            <li>
                <a href="#alacarte_tab" data-toggle="tab" class="text-center">Alacarte</a>
            </li>
            <li>
                <a href="#location_details" data-toggle="tab" class="text-center">Location</a>
            </li>
            <li>
                <a href="#miscellaneous_tab" data-toggle="tab" class="text-center">Miscellaneous</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="basic_details" class="tab-pane active mt-lg">
                @include('partials.forms.select_restaurant')
                @include('partials.forms.select_locations')
                @include('partials.forms.basic_details')
            </div>
            <div id="seo_details" class="tab-pane mt-lg">
                @include('partials.forms.seo_details')
            </div>
            <div id="media_tab" class="tab-pane mt-lg">
                @include('partials.forms.add_media')
            </div>
            <div id="schedule_tab" class="tab-pane mt-lg">
                @include('partials.forms.schedule_limits')
                @include('partials.forms.schedules')
            </div>
            <div id="alacarte_tab" class="tab-pane mt-lg">
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="allow_alacarte_reservation" id="allow_alacarte_reservation" checked="">
                            <label  for="allow_alacarte_reservation">Allow Alacarte Reservations</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('alacarte_terms_conditions','Alacarte Terms & Conditions',['class'=>'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('alacarte_terms_conditions',null,['class'=>'form-control','id'=>'alacarte_terms_conditions','required'=>'']) !!}
                    </div>
                </div>
            </div>
            <div id="location_details" class="tab-pane mt-lg">
                @include('partials.forms.locations')
            </div>
            <div id="miscellaneous_tab" class="tab-pane mt-lg">
                @include('partials.forms.cousines_collections')
                @include('partials.forms.payment_details')
                @include('partials.forms.status')
            <div>

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