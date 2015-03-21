@extends('.........templates.admin_layout')

@section('content')
<header class="page-header">
    <h2>Rewards</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/admin/dashboard">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li>
                 <a href="/admin/users">
                    <i class="fa">Users</i>
                 </a>
            </li>
            <li><span>Rewards</span></li>
        </ol>
    </div>
</header>

<section class="panel col-lg-8 col-lg-offset-2">
    <header class="panel-heading">
        <h2 class="panel-title">Add Reward</h2>
    </header>
     {!! Form::open(['route'=>'AdminUserStoreReward','novalidate'=>'novalidate']) !!}
    <div class="panel-body">
        <div class="form-group">
            {!! Form::label('total_points','Total points',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('total_rewards',$users->total_points,['class'=>'form-control','required'=>'','readonly'=>'readonly']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('points','No. of points',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('points',null,['class'=>'form-control','required'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="status">&nbsp;&nbsp;&nbsp;Status &nbsp;&nbsp;&nbsp;</label>

            <div class="radio-custom radio-success radio-inline">
                <input type="radio" name="status" value="add_points" checked="checked">
                <label for="add_points">Add points</label>
            </div>
            <div class="radio-custom radio-danger radio-inline">
                <input type="radio" name="status" value="redeem_points" >
                <label for="redeem_points">Redeem points</label>
            </div>
            <div class="radio-custom radio-danger radio-inline mt-none">
                <input type="radio" name="status" value="remove_points" >
                <label for="remove_points">Remove points</label>
            </div>

        </div>
        <div class="form-group">
            {!! Form::label('description','Description',['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::textarea('short_description',null,['class'=>'form-control','rows'=>'3','required'=>'']) !!}
            </div>
        </div>
    </div>
   <footer class="panel-footer">
       {!! Form::hidden('user_id',$user_id) !!}
       {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
   </footer>
   {!! Form::close() !!}
</section>
<section class="panel col-lg-8 col-lg-offset-2">
    <div class="panel-body">
        <table class="table table-striped table-responsive mb-none" id="usersTable">
            <thead>
            <tr>
                <th>Id</th>
                <th>Points</th>
                <th>Points Type</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rewards as $reward)
                @if($reward->points_earned != 0)
                    {{--*/
                        $points = $reward->points_earned;
                        $type = "Added";
                    /*--}}
                @elseif($reward->points_redeemed != 0)
                    {{--*/
                        $points = $reward->points_redeemed;
                        $type = "Redeemed";
                    /*--}}
                @elseif($reward->points_removed != 0)
                    {{--*/
                        $points = $reward->points_removed;
                        $type = "Removed";
                    /*--}}
                @endif
                <tr>
                    <th>{!! $reward->id !!}</th>
                    <th>{!! $points !!}</th>
                    <th>{!! $type !!}</th>
                    <th>{!! $reward->description !!}</th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
@stop
