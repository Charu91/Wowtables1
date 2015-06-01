@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Restaurants</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Restaurants</span></li>
            </ol>
        </div>
    </header>

    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                All Restaurants
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="restaurantsTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Restaurant Name</th>
                    {{--<th>Location Name</th>--}}
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($RestaurantLocations as $location)
                    <tr>
                        <th>{!! $location->id !!}</th>
                        <th>{!! $location->vendor->name !!}</th>
                        {{--<th>{!! $location->location->name !!}</th>--}}
                        <th>{!! $location->slug !!}</th>
                        <th>{!! $location->status !!}</th>
                        <th>{!! $location->created_at->format('d-m-Y') !!}</th>
                        <th>{!! $location->updated_at->format('d-m-Y') !!}</th>
                        <th>
                            {!! link_to_route('AdminRestaurantLocationsEdit','Edit',$location->id,['target'=>'_blank','class'=>'btn btn-xs btn-primary']) !!}
                            &nbsp;|&nbsp;
                            <a data-restaurant-location-id="{!! $location->id !!}" class="btn btn-xs btn-danger delete-restaurant-location">Delete</a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@stop