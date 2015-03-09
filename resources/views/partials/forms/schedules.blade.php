    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Timing</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="start_time" class="col-sm-4 control-label">Start Time <span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::text('start_time','8:00',['class'=>'form-control','data-plugin-timepicker'=>'']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="end_time" class="col-sm-4 control-label">End Time <span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::text('end_time','22:00',['class'=>'form-control','data-plugin-timepicker'=>'']) !!}
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

    <div id="schedules_table"></div>