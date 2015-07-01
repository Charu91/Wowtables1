@extends('frontend.templates.details_pages')

@section('content')
<script type="text/javascript" src="{{URL::to('/')}}/assets/js/jRating.jquery.js"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/assets/css/jRating.jquery.css?ver=1.0')" type="text/css" />
<script type="text/javascript">
$(document).ready(function(){
	$('.basic').jRating({
		showRateInfo:false,
		step:true,
		length : 5, // nb of stars
		decimalLength:0, // number of decimal in the rate
	});

	$(".save_user_reviews").click(function(){
			$(".show_rating_error").hide();
			var v = $(".rating_value").val();
			console.log("rating value == "+v);
			if(v == "" || v == 0){
				$(".show_rating_error").show();
				return false;
			} else {
				$(".show_rating_error").hide();
				return true;
			}
		});
});
</script>
<div class="container cms-page">
	<div class="row">
		<div class="col-md-12 entry-content">
	<?php if($data['review_status'] == "Yes")
				{
				echo "<h3>It looks like you have already submitted a review for this reservation. If there's something wrong, or you would like to edit your review, please write to us at concierge@wowtables.com. </h3>";
				}
			else { ?>	
					
		<?php // if($check_review['review_exist'] == "yes"){?>
			<?php //if($type == "experience"){?>
				<!--<h3>Your review for reservation '<?php //echo $get_details['exp_title'];?>' At '<?php //echo $get_details['venue'];?>'</h3>-->
			<?php //} else if($type == "alacarte") {?>
				<!--<h3>Your review for reservation At '<?php //echo $get_details['name'];?>'</h3>-->
			<?php //}?>
			<p><?php //echo $check_review['review_para']?></p>
		<?php // } else if($check_review['review_exist'] == "no"){?>
			<?php //print_r($data);?>
			<?php if($data['reservation_type'] == "experience")
				{ ?>
				<h3>Please review your reservation for '<?php echo $data['product_name'];?>' At '<?php echo $data['vendor_name'];?>'</h3>
			<?php } else if($data['reservation_type'] == "alacarte") 
					{ ?>
				<h3>Please review your reservation At '<?php echo $data['vendor_name'];?>'</h3>
			<?php }?>

			<form method="post" class="form-horizontal gift-form" autocomplete="off" action="{{URL::to('/')}}/review/save_user_review">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="MemberName"> Member Name: </label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="member_name" value="<?php echo $data['guest_name'];?>" required/>
							<?php if($data['reservation_type'] == "experience")
							{ ?>
							<!--for experience reservation product_id -->
							<input type="hidden" name="product_id" value="<?php echo $data['product_id'];?>" />
							<input type="hidden" name="exp_name" value="<?php echo $data['product_name'].' At '.$data['vendor_name'];?>" />
							<?php } else if($data['reservation_type'] == "alacarte") 
							{ ?>
							<!--for alacart reservation vendor_location_id -->
							<input type="hidden" name="vendor_location_id" value="<?php echo $data['vendor_location_id'];?>" />
							<input type="hidden" name="exp_name" value="<?php echo $data['vendor_name'];?>" />
							<?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="Rating:"> Rating: </label>
						<div class="col-sm-4">
							<!--<input type="text" class="form-control" name="rating" value="" required/>-->
							<div class="basic" data-id="1"></div>
							<span class="error show_rating_error" style="display:none;">This field is required. Please enter a value.</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="Rating:"> Your Review: </label>
						<div class="col-sm-4">
							<textarea name="review_para" class="form-control" required></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="Rating:"> Name of server: </label>
						<div class="col-sm-4">
							<input type="text" name="name_server" value="" class="form-control" required>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="Rating:"> Were you happy with the service: </label>
						<div class="col-sm-4">
							<input type="radio" name="service" value="Yes" required/>&nbsp;&nbsp;Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="service" value="No" required/>&nbsp;&nbsp; No
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="Rating:"> Would you like to give any feedback or suggestions to WowTables? : </label>
						<div class="col-sm-4">
							<textarea name="suggestion" class="form-control"></textarea>
						</div>
					</div>
					
					<div class="form-group">
						
						<div class="col-sm-6 text-right">
							  <?php /*$explode_date_time = explode(' ',$get_details['booking_time']);
							  $explode_date = explode('/',$explode_date_time[0]);
							  $format_date = $explode_date[2]."-".$explode_date[0]."-".$explode_date[1];*/
						//echo "<pre>"; print_r($get_details);
						?>
						<input type="hidden" name="user_id" value="<?php echo $data['user_id'];?>" />
						<input type="hidden" name="rating" value="0" class="rating_value" />
						<input type="hidden" name="user_email" value="<?php echo $data['guest_email'];?>" />
						<input type="hidden" name="reservid" value="<?php echo $data['reservid'];?>" />
						<input type="hidden" name="membership_num" value="<?php echo $data['membership_number'];?>" />
						<input type="hidden" name="seating_date" value="<?php echo $data['reservation_date'];?>" />
						<input type="hidden" name="reservation_type" value="<?php echo $data['reservation_type'];?>" />
						<input type="hidden" name="city" value="<?php echo $data['city_name'];?>" />
							<button class="btn btn-warning save_user_reviews" type="submit">Submit</button>
						</div>
					</div>
			</form>
		<?php } ?>
		</div>
	</div>
</div>
@endsection

