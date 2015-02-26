<div class="form-group">
    {!! Form::label('restaurant_id','Select Restaurant',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('restaurant_id',$restaurants_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>
