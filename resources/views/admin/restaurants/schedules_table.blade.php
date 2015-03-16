@if( ! empty($schedules) )
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
                    @foreach($schedules as $key => $slot)
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
                                        <td>{{ $slot['time'] }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                        <tr>
                                            <td>Sch</td>
                                            <td>{!! Form::checkbox('schedules['.$key.'][id]',$slot['mon']['schedule_id'],true) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Max T</td>
                                            <td>{!! Form::text('schedules['.$key.'][max_reservations]','5',['size'=>'2']) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Off Peak</td>
                                            <td>{!! Form::checkbox('schedules['.$key.'][off_peak]','1',true) !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][id]',$slot['tue']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('schedules['.$key.'][max_reservations]','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][off_peak]','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][id]',$slot['wed']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('schedules['.$key.'][max_reservations]','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][off_peak]','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][id]',$slot['thu']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('schedules['.$key.'][max_reservations]','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][off_peak]','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][id]',$slot['fri']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('schedules['.$key.'][max_reservations]','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][off_peak]','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][id]',$slot['sat']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('schedules['.$key.'][max_reservations]','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][off_peak]','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][id]',$slot['sun']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('schedules['.$key.'][max_reservations]','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('schedules['.$key.'][off_peak]','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif