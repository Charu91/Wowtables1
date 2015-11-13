@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Alacarte Review Add</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Alacarte Review Add</span></li>
            </ol>
        </div>
    </header>

    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                 Alacarte Review Add
            </h2>
        </header>
        <div class="panel-body">
            
            <div class="col-sm-12" >
            <section class="panel">
                <?php //print_r($expReviewDetails);
                ?>
                <div class="panel-body">
                   {!! Form::open(['route'=>'alacarteReviewAdd']) !!}
					 <div class="form-group">
						
						{!! Form::label('experience_id','Select Experience*',['class'=>'col-sm-4 control-label']) !!}
						<div class="col-sm-8">
							{!! Form::select('experience_id',$experiences_list,null,['id'=>'loc_exp','class'=>'form-control ','data-plugin-selectTwo'=>'','required'=>'']) !!}
						</div>
					</div>
					<div class="form-group">
                        {!! Form::label('name','Name*',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">                           
						   {!! Form::text('name',null,['class'=>'form-control','required']) !!}						   
                        </div>
                    </div>
                    
                     <div class="form-group">
						{!! Form::label('email','Email*',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
							{!! Form::text('email',null,['class'=>'form-control','required']) !!}
                        </div>
                    </div>
					<div class="form-group">
						{!! Form::label('reservid','Reservation Detail ID*',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
							{!! Form::text('reservid',null,['class'=>'form-control','required']) !!}
                        </div>
                    </div>
					<div class="form-group">
						{!! Form::label('review','Review*',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                        
						   {!! Form::textarea('review',null,['class'=>'form-control','required','rows'=>'3','cols'=>'50']) !!}
						
                        </div>
                    </div>

					<div class="form-group">
						{!! Form::label('rating','Rating*',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
							{!! Form::text('rating',null,['class'=>'form-control','required']) !!}
                        </div>
                    </div>
					<div class="form-group">
						{!! Form::label('show_review','Show review',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            <input type="checkbox" id="show_review" name="show_review" value="1">
                        </div>
                    </div>
					
					<!--<div class="form-group">
						{!! Form::label('date','Date',['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('date',date('Y-m-d'),['class'=>'form-control','required']) !!}
                        </div>
                    </div>-->
					
                    
                
            </div>
                <footer class="panel-footer">
                    {!! Form::submit('Add Review',['class'=>'btn btn-primary']) !!}
                </footer>
                
            </section>
			{!! Form::close() !!}
        </div>

        </div>
    </section>

@stop