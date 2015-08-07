<?php 
if(Session::has('id'))
	$set_user_id = Session::get('id');
else
	$set_user_id = 0;
?>
<style type="text/css">
.clearbutton {
  color: #000 !important;
  width:100px;
  height:15px;
  font-family: "pt sans";
  font-size: 14px;
  padding: 10px 25px;
  -moz-border-radius: 10px;
  -webkit-border-radius: 10px;
  border-radius: 10px;
  border: #3866A3;
  background: #eab803;
  background: linear-gradient(top,  #eab803,  #eab803);
  background: -ms-linear-gradient(top,  #eab803,  #eab803);
  background: -webkit-gradient(linear, left top, left bottom, from(#eab803), to(#eab803));
  background: -moz-linear-gradient(top,  #eab803,  #eab803);
  text-decoration: none;
}
.clearbutton:hover {
  color: #14396A !important;
  background: #468CCF;
  background: linear-gradient(top,  #eab803,  #c99f06);
  background: -ms-linear-gradient(top,  #eab803,  #c99f06);
  background: -webkit-gradient(linear, left top, left bottom, from(#eab803), to(#c99f06));
  background: -moz-linear-gradient(top,  #eab803,  #c99f06);
}

</style>
<script type="text/javascript">


$(function() {
     $("img.lazy").lazyload({
         effect : "fadeIn"
     });

  });
</script>
<input type="hidden" name="current_city" value="<?php echo $current_city;?>">
<?php 
	$countOfExperiences = $resultCount; 
	if($countOfExperiences > 1) {
		$changeExpText = "experiences"; 
		$changeAre = "are";
	} else {
		$changeExpText = "experience";
		$changeAre = "is";
	}
?>
<div id="exp_list_load_layer" class="hidden">
		<img src="{{URL::to('/')}}/images/Loading-Image.gif">
	</div>
<div class="col-sm-6">
	<?php $set_exp_name = (($resultCount == 1) ? "experience" : "experiences")?>
	<p class="sort-info"><?php echo ((($resultCount)>0) ? $resultCount." ".$set_exp_name." match your search criteria" : "No experiences match your search criteria"); ?></p>
</div>
<div class="col-sm-6">
	<span style="display:none;margin-top: -30px;position: absolute" class="show_loading_img"><img src="<?php echo URL::asset('assets/img/loading.gif');?>" title='Loading' /></span>
</div>
	<div class="col-sm-6">
		<div class="row">
			<div class="col-sm-3">
				<p class="sort-info">Sort by</p>
			</div>
			<div class="col-sm-9 text-center">
				<div>
					<select name="sort_results" class="form-control sort_results" >
					  <option <?php echo (isset($sort_selected) && $sort_selected == 'popular' ? 'selected=selected' : '' )?> value="popular">Popularity</option>
					  <option <?php echo (isset($sort_selected) && $sort_selected == 'new' ? 'selected=selected' : '' )?> value="new">New</option>
					</select>
				  </div>
			</div>
		</div>
	</div>
            <div class="clearfix"></div>
<div class="col-sm-12">
  <div class="divider"></div>
</div>

	<?php if(empty($resultCount)):?>
		<p class="lead">
			<em>No experiences match your current search, please refine or expand your current search.<a href="javascript:void(0);" title="Clear All Filters" class="clear_filters">Clear All Filters</a></em>
		</p>
	<?php endif;?>
	<?php if($current_city == 'mumbai'){ ?>
		
	<?php } else if ($current_city == 'delhi'){ ?>
		
		
	<?php } else if($current_city == 'pune'){ ?>
		
		
	<?php 
	}
	?>
	
	
	
	<div class="widget conc-mobile col-md-12 visible-xs text-center">
		<a href="#">
			<span class="orange">MAKE A RESERVATION ONLINE</span><br/>OR<br/>CALL OUR CONCIERGE AT <span class="orange">9619551387</span>
		</a>
	</div>
	<?php
		$total_rows = $resultCount; ?>
    <ul class="experience-grid">   
	<?php                                                       
        for($j_count=0;$j_count<$total_rows;$j_count++){
			//echo "<pre>"; print_r($rows);
	?>
			
				<li class="col-md-6 col-sm-12 col-xs-12 col-lg-6">              
						<div>
							<a href="{{URL::to('/')}}/<?php echo $current_city;?>/experiences/<?php echo $data[$j_count]['slug'];?>">
								<table class="deal-head">
									<tr>
										<td>
											<?php echo $data[$j_count]['name'];?>
										</td>
										<td>
											<span> View</span> Details
											<?php 
												/*if ( $data[$j_count]['intval'] && $data[$j_count]['tickets_sold'] < $data[$j_count]['max_num_orders']  ) {
													if( $data[$j_count]['coming_soon']=='1'){?>
														<span>Coming</span> Soon
													<?php }else{ ?>
														<span> View</span> Details
													<?php }
												   } else { ?>
													<span>Sold</span> out
												<?php };
												*/ ?>
										</td>
									</tr>
								</table>
							</a>

							<div class="deal-img">
								<img src="<?php echo isset($data[$j_count]['image']['listing'])?$data[$j_count]['image']['listing']:'';?>" alt="image1" class="img-responsive"/>							
									<?php
									if(isset($data[$j_count]['flag']) &&  !empty($data[$j_count]['flag'])) {?>
									<div class="flag new valign" id="top_paddin"style="background:<?php echo $data[$j_count]['color'];?>"><?php echo $data[$j_count]['flag'];?></div>
								<?php } 
								/* 
								<div class="bookmark valign balign" id="top_alignmen">
									<div class="<?php echo ((isset($data[$j_count]['bookmark_status']) && $data[$j_count]['bookmark_status'] == 1 && (isset($data[$j_count]['bookmark_userid']) && $data[$j_count]['bookmark_userid'] == $set_user_id))? "bookmark_marked" : "bookmark_plain")?>" onclick="toggleClass(this,<?php echo $data[$j_count]['id']?>,<?php echo $set_user_id?>)"></div>
								</div>
								*/?>
								<div class="deal-desc" >
									<p><?php echo $data[$j_count]['short_description'];?></p>
								</div>
							</div>		

							<div class="discount">
								<div class="col-xs-7">
									<div>
										<span class="star-all">
											<?php if((isset($data[$j_count]['full_stars']) && $data[$j_count]['full_stars'] != "" && $data[$j_count]['full_stars'] != 0)) {?>
											<?php for($c=0;$c<$data[$j_count]['full_stars'];$c++){ ?>
											<span class="star-icon full star_icon_exper">&#9733;</span>	
											<?php }
											}?>
											<?php if((isset($data[$j_count]['half_stars']) && $data[$j_count]['half_stars'] != "" && $data[$j_count]['half_stars'] != 0)) {?>
											<span class="star-icon half">&#9733;</span>
											<?php } ?>
											<?php if((isset($data[$j_count]['blank_stars']) && $data[$j_count]['blank_stars'] != "" && $data[$j_count]['blank_stars'] != 0)){?>
											<?php for($c=1;$c<=$data[$j_count]['blank_stars'];$c++){?>
											<span class="star-icon">&#9733;</span>
											<?php }?>
											<?php } ?>
										</span>
										<span class="rating text-center"><?php echo (isset($data[$j_count]['total_reviews']) && $data[$j_count]['total_reviews'] > 0) ? "(".$data[$j_count]['total_reviews']." Reviews)" : "";?></span>
									</div>                  
								</div>
								<div class="col-xs-5 desc-price text-center">
									<?php if(isset($data[$j_count]['price']) && $data[$j_count]['price'] > 0 ) {?>
										<p>Rs <?php echo number_format($data[$j_count]['price'],0);?><span class="small">(<?php echo $data[$j_count]['price_type'];?>)</span></p>
									<?php } else {?> <p>&nbsp;<span class="small">&nbsp;</span></p><?php } ?>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					  </li>
			
		<?php } //end for ?> 
    </ul>			
					

