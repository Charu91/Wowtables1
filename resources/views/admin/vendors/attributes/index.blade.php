@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Restaurant Attributes</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="/admin/dashboard/restaurants">
                        Restaurants
                    </a>
                </li>
                <li><span>Restaurant Attributes</span></li>
            </ol>
        </div>
    </header>

    <div class="row">
        <div class="col-sm-8">
            <section class="panel panel-featured panel-featured-primary">
                <header class="panel-heading">
                    <h2 class="panel-title">
                        All Restaurant Attributes
                    </h2>
                </header>
                <div class="panel-body">
                    <table class="table table-striped table-responsive mb-none" id="usersTable">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Alias</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($attributes as $attribute)
                            <tr>
                                <th>{!! $attribute->id !!}</th>
                                <th>{!! $attribute->name !!}</th>
                                <th>{!! $attribute->alias !!}</th>
                                <th>{!! $attribute->type !!}</th>
                                <th>
                                    <a data-restaurant-attribute-id="{!! $attribute->id !!}" class="btn btn-xs btn-primary edit-restaurant-attribute">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    &nbsp;|&nbsp;
                                    <a data-restaurant-attribute-id="{!! $attribute->id !!}" class="btn btn-xs btn-danger delete-restaurant-attribute">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-sm-4" id="editVendorAttributeHolder"></div>
        <div class="col-sm-4">

            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Add New</h2>
                </header>
                <div class="panel-body">
                    {!! Form::open(['route'=>'admin.restaurant.attributes.store']) !!}

                    @include('admin.vendors.partials.create_vendor_attributes')
                </div>
                <footer class="panel-footer">
                    {!! Form::submit('Add Attribute',['class'=>'btn btn-primary']) !!}
                </footer>
                {!! Form::close() !!}
            </section>
        </div>
    </div>
@stop