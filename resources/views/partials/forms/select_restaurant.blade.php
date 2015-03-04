<div class="form-group">
    {!! Form::label('vendor_id','Select Restaurant',['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('vendor_id',$restaurants_list,null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>
