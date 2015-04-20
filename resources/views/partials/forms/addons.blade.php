<div id="addon_details" class="tab-pane mt-lg">
    <div class="form-group">
        <div class="col-sm-3 col-sm-offset-1">
            <a class="btn btn-primary" id="addNewExperienceAddonBtn" >Add New Addon</a>
        </div>
    </div>
    <div id="experienceAddonForm">
        <div class="form-group">
            {!! Form::label('','Addon Title',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('',null,['class'=>'form-control','id'=>'addonTitle']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('addon_price_before_tax','Addon Price Before Tax',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('',null,['class'=>'form-control','id'=>'addonPriceBeforeTax']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('','Addon Price After Tax',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('',null,['class'=>'form-control','id'=>'addonPriceAfterTax']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('','Addon Tax',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('',null,['class'=>'form-control','id'=>'addonTax']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('','Addon Info',['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('',null,['class'=>'form-control','rows'=>'3','id'=>'addonInfo']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="row">
                    <a class="btn btn-primary" id="addExperienceAddonBtn" >Add Addon</a>
                    <a class="btn btn-primary" id="cancelUpdateExperienceAddonBtn" >Cancel</a>
                </div>
            </div>
        </div>
    </div>
    @include('partials.forms.experience_addon')
</div>
