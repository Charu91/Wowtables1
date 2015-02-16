@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Restaurants</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Add/Update Restaurant</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right">
            <i class="fa fa-chevron-left"></i></a>
        </div>
    </header>

    {!! Form::open(['route'=>'AdminRestaurantStore','class'=>'form-horizontal','id'=>'addNewRestaurantForm']) !!}

    <section  class="panel">
        <div class="panel-body">

                @include('partials.forms.basic_details')

                @include('partials.forms.seo_details')

        </div>
    </section>

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

    {!! Form::close() !!}

@stop