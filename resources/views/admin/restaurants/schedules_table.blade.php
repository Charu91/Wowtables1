@if( ! empty($breakfast) )
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Breakfast</h2>
    </header>
    <div  class="panel-body">
        <div class="table-responsive">
            <table  class="table table-bordered mb-none">
                <thead>
                <tr>
                    <th>Action</th>
                    <th>Time</th>
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
                    @foreach($breakfast as $slot)
                        <tr>
                            <td> <a id="selectrow" class="btn btn-xs btn-success select-all">Select All</a> | <a class="btn btn-xs btn-danger select-none">None</a> </td>
                            <td>{{ $slot['time'] }}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif

@if( ! empty($lunch) )
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Lunch</h2>
        </header>
        <div  class="panel-body">
            <div class="table-responsive">
                <table  class="table table-bordered mb-none">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Time</th>
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
                    @foreach($lunch as $slot)
                        <tr>
                            <td> <a class="btn btn-xs btn-success select-all">Select All</a> | <a class="btn btn-xs btn-danger select-none">None</a> </td>
                            <td>{{ $slot['time'] }}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endif

@if( ! empty($dinner) )
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Dinner</h2>
        </header>
        <div  class="panel-body">
            <div class="table-responsive">
                <table  class="table table-bordered mb-none">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Time</th>
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
                    @foreach($dinner as $slot)
                        <tr>
                            <td> <a class="btn btn-xs btn-success select-all">Select All</a> | <a class="btn btn-xs btn-danger select-none">None</a> </td>
                            <td>{{ $slot['time'] }}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                            <td style="padding:0;">{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endif

