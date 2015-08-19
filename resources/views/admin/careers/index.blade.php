@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Careers List</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Careers</span></li>
            </ol>
        </div>
    </header>
    <a href="/admin/careers/create"><button class="btn btn-primary mb-lg btn-lg">Add new Career</button></a>
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                All Careers
            </h2>
        </header>

        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="usersTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Job Title</th>
                    <th>Location</th>
                    <th>Show in FrontEnd</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($careers as $career)
                    <tr>
                        <th>{!! $career->id !!}</th>
                        <th>{!! $career->job_title !!}</th>
                        <th>{!! $career->location !!}</th>
                        <th>
                            @if($career->status)
                                {!! Form::checkbox('status',null,true,['disabled'=>'disabled']) !!}
                            @else
                                {!! Form::checkbox('status',null,false,['disabled'=>'disabled']) !!}
                            @endif
                        </th>
                        <th>
                            <a href="/admin/careers/{!! $career->id !!}/edit" data-curator-id="/admin/careers/{!! $career->id !!}/edit" class="btn btn-xs btn-primary edit-career-btn">Edit</a>
                            &nbsp;|&nbsp;
                            <a data-career-id="{!! $career->id !!}" class="btn btn-xs btn-danger delete-career-btn">Delete</a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@stop