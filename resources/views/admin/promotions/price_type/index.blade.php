@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Price Types</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="/admin/promotions">
                        Promotions
                    </a>
                </li>
                <li><span>Price Types</span></li>
            </ol>
        </div>
    </header>

    <div class="row">
        <div class="col-sm-8">
            <section class="panel panel-featured panel-featured-primary">
                <header class="panel-heading">
                    <h2 class="panel-title">
                        All Price Types
                    </h2>
                </header>
                <div class="panel-body">
                    <table class="table table-striped table-responsive mb-none" id="usersTable">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Price Type Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($price_types as $price_type)
                            <tr>
                                <th>{!! $price_type->id !!}</th>
                                <th>{!! $price_type->type_name !!}</th>
                                <th>
                                    <a data-price_type-id="{!! $price_type->id !!}" class="btn btn-xs btn-primary edit-price_type-btn">Edit</a>
                                    &nbsp;|&nbsp;
                                    <a data-price_type-id="{!! $price_type->id !!}" class="btn btn-xs btn-danger delete-price_type-btn">Delete</a>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-sm-4" id="editPriceTypeHolder"></div>
        <div class="col-sm-4">

            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Add New</h2>
                </header>
                <div class="panel-body">
                    {!! Form::open(['route'=>'admin.promotions.price_type.store','novalidate'=>'novalidate']) !!}

                    <div class="form-group">
                        {!! Form::label('type_name','Name',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('type_name',null,['class'=>'form-control','required'=>'']) !!}
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    {!! Form::submit('Add Price Type',['class'=>'btn btn-primary']) !!}
                </footer>
                {!! Form::close() !!}
            </section>
        </div>
    </div>
@stop