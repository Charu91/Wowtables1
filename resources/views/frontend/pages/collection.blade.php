@extends('frontend.templates.details_pages')

@section('content')

<?php 
if(Session::has('id'))
	$set_user_id = Session::get('id');
else
	$set_user_id = 0;
?>

<?php 

/*print_r($arrData);
foreach ($arrData['data'] as $key=>$data)
                {
                  echo $data['cityname'];
                  echo '<br>';
                  };*/
/*print_r($alaCartaArData);
exit;*/
?>

<!--==============Top Section closed=================-->
<div class="container deal-listing variant featured-variant">
<div class="row">

<div class="clearfix"></div>

<div class="col-md-12 listing-cover">

    <img src="https://s3-eu-west-1.amazonaws.com/wowtables/uploads/collections/{{$collectionResult['0']->file}}" height="{{$collectionResult['0']->height}}" width="{{$collectionResult['0']->width}}" class="img-responsive">
      </div>

<div class="col-md-12 listing-cover-desc">
      <div class="row">
<div class="col-md-4 col-sm-4"><?php //print_r($exclusiveExperiences);?>
  <p class="lead intro-tag text-center">{{$collectionResult['0']->name}}</p>
</div>
<div class="col-md-8 col-sm-8">
  				<!-- <p>A selection of experiences that are perfect for our Jain members. Get ready to enjoy Jain set meals at some of the top restaurants in Mumbai. </p> -->
  				<?php echo $collectionResult['0']->description;?>
              </div>
</div>
</div>

<?php if(empty($arrData))
{

}
else{?>
<div class="clearfix"></div>

			<h3 style="padding-bottom:10px;margin-left:15px;">Exclusive Dining Experiences: </h3>
			<ul class="experience-grid">
				<?php
				foreach ($arrData['data'] as $key=>$data)
             		{

				?>
			
			  <li class="col-md-4 col-sm-4">
			  <a href="{{URL::to('/')}}/{{$data['cityname']}}/experiences/{{$data['slug']}}">
					<table class="deal-head deal-head-exp" id="table_head_exp">
						  <tr>
							  <td id="large_td"><?php //echo Config::get('media.base_s3_url_listing');?>
								 <?php echo $data['productname']; ?></td>
							  <td id="small_td">
								<span>View</span> Details
							  </td>
							</tr>
					   </table>
			  </a>
			   <div id="background_image_exp" style="background:url('{{Config::get('media.base_s3_url_listing')}}{{$data['file']}}');padding-top: 2px;">
					 <div  class="book_mark1">          
					</div>
				   		<?php
						if(isset($data['flagname']) && $data['flagname'] !="") {?>
						<div class="flag new valign" id="top_paddin"style="margin-top: 52px;background-color:{{$data['color']}}"><?php echo $data['flagname'];?></div>
						<?php }?>
				 
			   </div>
			   <div class="deal-desc" id="desc_tag_exp">
				  <p id="dummy_text">{{$data['short_description']}} </p>
			   </div>
			   <div class="white_background1_exp">
				  <div class="row">
					<div class="col-sm-7 col-xs-7" id="bottom_padding_footer">
						<div id="big_col">
					<span class="star-all">
						<?php if((isset($data['full_stars']) && $data['full_stars'] != "" && $data['full_stars'] != 0)) {?>
						<?php for($c=0;$c<$data['full_stars'];$c++){ ?>
						<span class="star-icon full star_icon_exper">&#9733;</span>	
						<?php }
						}?>
						<?php if((isset($data['half_stars']) && $data['half_stars'] != "" && $data['half_stars'] != 0)) {?>
						<span class="star-icon half">&#9733;</span>
						<?php } ?>
						<?php if((isset($data['blank_stars']) && $data['blank_stars'] != "" && $data['blank_stars'] != 0)){?>
						<?php for($c=1;$c<=$data['blank_stars'];$c++){?>
						<span class="star-icon">&#9733;</span>
						<?php }?>
						<?php } ?>
					</span>
						<br />
						<span class="rating text-center"><?php echo (isset($data['total_reviews']) && $data['total_reviews'] > 0) ? "(".$data['total_reviews']." Reviews)" : "";?></span>
						
					  </div>
					</div>
					<div class="col-sm-5 col-xs-5">
					  									  <div id="small_col">
							<span style="font-size:18px;font-weight:bold;"> Rs {{$data['price']}}</span><br />
							<div class="rating_ala" id="rating_review rating_reviews">{{$data['type_name']}}</div>
						  </div>
					  								</div>
				  </div>
			   </div> 
			</li>	
			<?php }?>			
		</ul> 

</div>
</div>
<?php }?>
<?php //exit;?>
   <!--==============close Experiences code=================-->

   <?php //print_r($alaCartaArData);
   if(empty($alaCartaArData))
   {

   }
   else{


    ?>
<!-- start code form alacart product-->
<!--==============Top Section closed=================-->
<div class="container deal-listing variant featured-variant">
<div class="row">

<div class="clearfix"></div>

<!-- start code form alacart product-->
<div class="clearfix"></div>

			<h3 style="padding-bottom:10px;margin-left:15px;">A la carte Reservations: </h3>
			<ul class="experience-grid">
				<?php //$total_rows = count($exclusiveExperiences);
					//$total_rows =5;
				 foreach ($alaCartaArData['data'] as $aladata)
				 {
				 	$cityname = strtolower($aladata['city']);
				?>
				<li class="col-md-4 col-sm-4">
								<div class="deal-img">
									<img src="{{Config::get('media.base_s3_url_listing')}}{{$aladata['imagename']}}" alt="" class="img-responsive">
												
						<?php
						if(isset($aladata['flagname']) && $aladata['flagname'] !="") {?>
						<div class="flag new alatop" id="flag_alcart_listing" style="background:{{$aladata['color']}}"><?php echo $aladata['flagname'];?></div>
						<?php }?>
																		<!--<div class="bookmark_overlay">
										<div class="bookmark_plain" onclick="toggleClass(this)"></div>
									</div>-->
								</div>                
								
								<div class="discount" id="big_div_height">
									<div class="col-xs-12 rest_name">
										<a style="color:black;cursor:pointer;" href="{{URL::to('/')}}/{{$cityname}}/alacarte/{{$aladata['vendorlocationslug']}}">
											<h3>{{$aladata['vendorlocationsName']}}</h3>
										</a>
									</div>
									<div class="col-xs-5 text-center">
										<p>{{$aladata['option']}}</p>
									</div>
									<div class="col-xs-2">
										<span>•</span>
									</div>							 

										<div class="col-xs-5 text-center" id="rupee_symobol">
										<?php 
											if(strtolower($aladata['pricing_level']) == 'low'){
													$price_tag = "<img src='/assets/img/ruppee_14.png' title='Low' />";
											  } else if(strtolower($aladata['pricing_level']) == 'medium'){
													$price_tag = "<img src='/assets/img/ruppee14x2.png' title='Medium' />";
											  } else if(strtolower($aladata['pricing_level']) == 'high'){
													$price_tag = "<img src='/assets/img/ruppee14x3.png' title='High' />";
											  }
										?>
										<p><?php echo $price_tag;?></p>                    
									</div>

									<div class="col-xs-12 location text-center">
										<p>{{$aladata['locationarea']}} </p>
									</div>
									<?php if(isset($aladata['review_detail']['review_count']) && $aladata['review_detail']['review_count'] > 0) {?>
										<div class="col-xs-12 text-center" style="padding-bottom:10px;float:left;">
											<div>
											<span class="star-all">
												<?php for($c=0;$c<$aladata['review_detail']['full_stars'];$c++){ ?>
													<span class="ala-star-icon full">&#9733;</span>
												<?php } ?>
												<?php if(isset($aladata['review_detail']['half_stars']) && $aladata['review_detail']['half_stars'] == 1) {?>
													<span class="ala-star-icon half">&#9733;</span>
												<?php }?>
												<?php for($c=1;$c<=$aladata['review_detail']['blank_stars'];$c++){?>
													<span class="ala-star-icon">&#9733;</span>
												<?php } ?>
											</span>
												<div class="rating_ala rating_review_point" id="rating_review">(<?php echo $aladata['review_detail']['review_count']; ?> <?php echo (($aladata['review_detail']['review_count'] > 1) ? 'Reviews' : 'Review');?>)</div>
											</div>   
										</div>
									<?php } ?>
									<div><br></div>


									<div class="col-xs-6 col-sm-12 col-md-6 text-center" id="reserve_table reserve_a_table" style="padding-bottom:8px;width:100%;">
										<a href="{{URL::to('/')}}/{{$cityname}}/alacarte/{{$aladata['vendorlocationslug']}}" class="btn btn-inverse">Reserve A Table</a>
									</div>
									<div class="clearfix"></div>
								</div>
							</li>	
			 
			<?php }?>

		</ul> 

</div>
</div>
   <!--==============Close alacart view code =================-->
<?php } ?>


@endsection