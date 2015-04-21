@if($errors->has())
        <div class="alert alert-danger fade in alert-dismissible">
            <strong>
                <button type="button" style="right:0;" class="close" data-dismiss="alert"
                        aria-hidden="true">×
                </button>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </strong>
        </div>
@endif