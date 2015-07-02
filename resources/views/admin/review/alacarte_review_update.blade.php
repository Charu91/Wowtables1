@extends('templates.admin_layout')

@section('content')
    <header class="page-header">
        <h2>Alacarte Review Update</h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/admin/dashboard">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Alacarte Review Update</span></li>
            </ol>
        </div>
    </header>

    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">
                All Alacarte Review Update
            </h2>
        </header>
        <div class="panel-body">
            
            <div class="col-sm-4" >
            <section class="panel">
                <?php //print_r($expReviewDetails);
                ?>
                <div class="panel-body">
                    <form  accept-charset="UTF-8" action="{{URL::to('/')}}/admin/alacarte/updatesave" method="POST">

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="type_name">Name*</label>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" value="{{$alacarteReviewDetails[0]->guest_name}}" required disabled class="form-control">
                            <input type="hidden" id="review_id" name="review_id" value="{{$alacarteReviewDetails[0]->id}}">
                        </div>
                    </div>
                      <div class="form-group">
                        <label class="col-sm-4 control-label" for="email">Email*</label>
                        <div class="col-sm-8">
                            <input type="text" id="email" name="email" value="{{$alacarteReviewDetails[0]->guest_email}}" disabled required class="form-control">
                        </div>
                    </div>

                      <div class="form-group">
                        <label class="col-sm-4 control-label" for="review">Review*</label>
                        <div class="col-sm-8">
                           <textarea cols="50" name="review" required="" rows="3" class="form-control">{{$alacarteReviewDetails[0]->review}}</textarea>
                        </div>
                    </div>

                      <div class="form-group">
                        <label class="col-sm-4 control-label" for="rating">Rating</label>
                        <div class="col-sm-8">
                            <input type="text" id="rating" name="rating" value="{{$alacarteReviewDetails[0]->rating}}" required class="form-control">
                        </div>
                    </div>

                      <div class="form-group">
                        <label class="col-sm-4 control-label" for="show_review">Show review</label>
                        <div class="col-sm-8">
                            <input type="checkbox" id="show_review" name="show_review" value="{{$alacarteReviewDetails[0]->status}}" <?php if($alacarteReviewDetails[0]->status == 'Approved') { echo 'checked';} ?>>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="date">Date </label>
                        <div class="col-sm-8">
                            <input type="text" id="date" name="date" value= "{{$alacarteReviewDetails[0]->reservation_date}}" disabled class="form-control">
                        </div>
                    </div>
                
            </div>
                <footer class="panel-footer">
                    <input type="submit" value="Update Review" class="btn btn-primary">
                </footer>
                
            </section>
            </form>
        </div>

        </div>
    </section>

@stop