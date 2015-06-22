@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Experience Locations</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Experience Locations</span></li>
            </ol>
        </div>
    </header>

    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                All Experience Locations
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="restaurantsTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Experience Name</th>
                    <th>Locations</th>
                    <th>Restaurant name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($experienceLocationDetails as $experienceLocationDetail)
                        <tr>
                            <td>{{$experienceLocationDetail->id}}</td>
                            <td>{{$experienceLocationDetail->product_name}}</td>
                            <td>{{$experienceLocationDetail->slug}}</td>
                            <td>{{$experienceLocationDetail->vendor_name}}</td>
                            <td>{{$experienceLocationDetail->status}}</td>
                            <td>
                                {!! link_to_route('AdminExperienceLocationsEdit','Edit',$experienceLocationDetail->id,['target'=>'_blank','class'=>'btn btn-xs btn-primary']) !!}
                                &nbsp;|&nbsp;
                                <a data-experience-id="{!! $experienceLocationDetail->id !!}" class="btn btn-xs btn-danger delete-experience">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@stop