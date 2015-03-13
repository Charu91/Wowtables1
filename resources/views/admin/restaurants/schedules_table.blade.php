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
                                            <td>{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Max T</td>
                                            <td>{!! Form::text('max_table['.$slot['mon']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Off Peak</td>
                                            <td>{!! Form::checkbox('off_peak['.$slot['mon']['schedule_id'].']','1',true) !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['tue']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['tue']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['wed']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['wed']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['thu']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['thu']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['fri']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['fri']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['sat']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['sat']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['sun']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['tue']['schedule_id'].']','1',true) !!}</td>
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
                                        <td>{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['mon']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['mon']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['tue']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['tue']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['wed']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['wed']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['thu']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['thu']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['fri']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['fri']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['sat']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['sat']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['sun']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['tue']['schedule_id'].']','1',true) !!}</td>
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
                                        <td>{!! Form::checkbox('schedules[]',$slot['mon']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['mon']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['mon']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['tue']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['tue']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['tue']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['wed']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['wed']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['wed']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['thu']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['thu']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['thu']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['fri']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['fri']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['fri']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['sat']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['sat']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['sat']['schedule_id'].']','1',true) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="padding:0;">
                                <table  class="table table-bordered mb-none">
                                    <tbody>
                                    <tr>
                                        <td>Sch</td>
                                        <td>{!! Form::checkbox('schedules[]',$slot['sun']['schedule_id'],true) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Max T</td>
                                        <td>{!! Form::text('max_table['.$slot['sun']['schedule_id'].']','5',['size'=>'2']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>{!! Form::checkbox('off_peak['.$slot['tue']['schedule_id'].']','1',true) !!}</td>
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

