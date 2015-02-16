@extends('templates.admin_layout_simple')

@section('content')
	<header class="page-header">
        <h2>Test</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
            </ol>
        </div>
    </header>
    <div class="panel-body">
        <div class="col-md-8">
            <table class="table table-striped table-responsive mb-none" id="locationsTable">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Slot</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule['time'] }}</td>
                            <td>{{ $schedule['slot_type'] }}</td>
                            <td>{{ $schedule['mon']['schedule_id'] }}</td>
                            <td>{{ $schedule['tue']['schedule_id'] }}</td>
                            <td>{{ $schedule['wed']['schedule_id'] }}</td>
                            <td>{{ $schedule['thu']['schedule_id'] }}</td>
                            <td>{{ $schedule['fri']['schedule_id'] }}</td>
                            <td>{{ $schedule['sat']['schedule_id'] }}</td>
                            <td>{{ $schedule['sun']['schedule_id'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop