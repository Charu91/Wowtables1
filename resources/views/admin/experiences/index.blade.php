@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Experiences</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Experiences</span></li>
            </ol>
        </div>
    </header>

    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                All Experiences
            </h2>
        </header>
        <div class="panel-body">
            <table class="table table-striped table-responsive mb-none" id="experiencesTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($experiences as $experience)
                    <tr>
                        <th>{!! $experience->id !!}</th>
                        <th>{!! $experience->name !!}</th>
                        <th>{!! $experience->slug !!}</th>
                        <th>{!! $experience->type !!}</th>
                        <th>{!! $experience->status !!}</th>
                        <th>{!! $experience->created_at->format('d-m-Y') !!}</th>
                        <th>{!! $experience->updated_at->format('d-m-Y') !!}</th>
                        <th>
                            {!! link_to_route('AdminExperienceEdit','Edit',$experience->id,['target'=>'_blank','class'=>'btn btn-xs btn-primary']) !!}
                            &nbsp;|&nbsp;
                            <a data-experience-id="{!! $experience->id !!}" class="btn btn-xs btn-danger delete-experience">Delete</a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@stop