    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Timing</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="start_time" class="col-sm-4 control-label">Start Time </label>
                        <div class="col-sm-8">
                            {!! Form::text('start_time','8:00',['class'=>'form-control','data-plugin-timepicker'=>'','id'=>'start_time']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="end_time" class="col-sm-4 control-label">End Time </label>
                        <div class="col-sm-8">
                            {!! Form::text('end_time','22:00',['class'=>'form-control','data-plugin-timepicker'=>'','id'=>'end_time']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <a class="btn btn-primary" id="createSchedule" >Create Schedule</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if( Input::old('schedule_times') )
        {{--*/ //var_dump(count(Input::old('schedules'))); /*--}}
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">All Schedules</h2>
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
                        @foreach(Input::old('schedule_times') as $key => $time)
                                {{--*/ //var_dump($time) /*--}}
                                <tr>
                                    <td>
                                        <table  class="table table-bordered mb-none">
                                            <tbody>
                                            <tr>
                                                <td><a id="selectrow" class="btn btn-xs btn-success select-all">Select All</a></td>
                                            </tr>
                                            <tr>
                                                <td><a class="btn btn-xs btn-danger select-none">Deselect</a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table  class="table table-bordered mb-none">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    {{ $time }}
                                                    <input type="hidden" name="schedules[time][]" value="{{ $time  }}">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                        @endforeach
                        @foreach(Input::old('schedules') as $key => $schedule )
                            {{--*/ //var_dump(Input::old('schedules')[$key]['id']); /*--}}
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules['.Input::old('schedules')[$key]['id'].'][id]',Input::old('schedules')[$key]['id'],true) !!}</td>
                                    </tr>
                                    <!--<tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('schedules['.Input::old('schedules')[$key]['id'].'][max_reservations]','5',['size'=>'2']) !!}</td>
                                    </tr>-->
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('schedules['.Input::old('schedules')[$key]['id'].'][off_peak]','1',false) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif
    <div id="schedules_table"></div>