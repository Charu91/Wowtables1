<div class="form-group">
    <label for="cuisine" class="col-sm-3 control-label">Cuisine <span class="required">*</span></label>
    <div class="col-sm-6">
        {!! Form::select('cuisine',[''=>'','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','required'=>'']) !!}
    </div>
</div>
<div class="form-group">
    <label for="collections" class="col-sm-3 control-label">Collections <span class="required">*</span></label>
    <div class="col-sm-6">
        {!! Form::select('collections[]',['0'=>'None','1'=>'First'],null,['class'=>'form-control populate','data-plugin-selectTwo'=>'','multiple'=>'','required'=>'']) !!}
    </div>
</div>
