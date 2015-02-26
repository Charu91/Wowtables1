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
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($restaurants as $restaurant)
                    <tr>
                        <th>{!! $restaurant->id !!}</th>
                        <th>{!! $restaurant->name !!}</th>
                        <th>{!! $restaurant->slug !!}</th>
                        <th>{!! $restaurant->status !!}</th>
                        <th>{!! $restaurant->created_at->format('d-m-Y') !!}</th>
                        <th>{!! $restaurant->updated_at->format('d-m-Y') !!}</th>
                        <th>
                            {!! link_to_route('AdminRestaurantEdit','Edit',$restaurant->id,['target'=>'_blank','class'=>'btn btn-xs btn-primary']) !!}
                            &nbsp;|&nbsp;
                            <a data-restaurant-id="{!! $restaurant->id !!}" class="btn btn-xs btn-danger delete-restaurant">Delete</a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@stop