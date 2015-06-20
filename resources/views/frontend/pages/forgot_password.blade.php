@extends('frontend.templates.details_pages')

@section('content')

    <!--==============Content Section=================-->
    <div class='container'>
        <div class="row">

            <div class="col-lg-4">
                <form action="{{URL::to('/')}}/users/save_changed_pass" method="post" onsubmit="return validate_frm()" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Email:</label>
                        <div class="col-sm-7">
                            <label class="control-label">{{$data->email}}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">New Password:</label>
                        <div class="col-sm-7">
                            <input type="password" class="form-control" name="password" id="password"  >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-7">
                            <button type="submit" class="btn btn-inverse">Change Password</button>
                            <input type="hidden" name="id" id="id" class="form-control" value="{{$data->id}}"  >
                            <input type="hidden" name="token" id="token" class="form-control" value="{{$data->request_token}}"  >
                        </div>
                    </div>
                </form>
            </div>
            <div style="height:300px"></div>
            <!--Content closed-->
            <script language="javascript">
                function validate_frm()    {

                    if(document.getElementById("password").value=='')
                    {
                        alert("Please enter Password");
                        return false;
                    }
                    else{
                        return true;
                    }
                }
            </script>
        </div>
    </div>
    <!--==============Content Section closed=================-->

@endsection