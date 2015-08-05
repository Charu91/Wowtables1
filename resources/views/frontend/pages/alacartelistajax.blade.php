<div id="exp_list_load_layer" class="hidden">
	<img src="{{URL::to('/')}}/images/Loading-Image.gif">
</div>
<div class="col-sm-8">
	<p class="sort-info"><?php echo ((($resultCount)>0) ? $resultCount." restaurants match your search criteria" : "No restaurants match your search criteria"); ?></p><span style="display:none;margin-left: 315px;margin-top: -30px;position: absolute" class="show_loading_img"><img src="/assets/img/loading.gif" title='Loading' /></span>
</div>

<!--<div class="col-sm-4 text-right">
  <div>
    <select name="" class="form-control">
      <option value="">Sort By Popularity</option>
      <option value="">Sort By Price</option>
    </select>
  </div>
</div>-->

<div class="clearfix"></div>
<div class="col-sm-12">
	<div class="divider"></div>
</div>
<?php if(empty($resultCount)):?>
<p class="lead">
	<em>No restaurants match your current search, please refine or expand your current search.<a href="javascript:void(0);" title="Clear All Filters" class="clear_filters">Clear All Filters</a></em>
</p>
<?php endif;?>

<div class="widget conc-mobile col-md-12 visible-xs text-center">
	<a href="#">
		<span class="orange">MAKE A RESERVATION ONLINE</span><br/>OR<br/>CALL OUR CONCIERGE AT <span class="orange">9619551387</span>
	</a>
</div>
<ul class="experience-grid">

	<?php
	if(isset($data) && is_array($data) )
	{
	foreach($data as $row){?>
	<li class="col-md-6 col-sm-6">
		<div class="deal-img">
			<img src="<?php echo isset($row['image']['listing'])?$row['image']['listing']:'';?>" alt="" class="img-responsive">
			<?php if(isset($row['flag_name']) && $row['flag_name'] != "") {?>
			<div class="flag new alatop" id="flag_alcart_listing"style="background:<?php echo $row['color'];?>"><?php echo $row['flag_name']?></div>
			<?php } ?>
			<!--<div class="bookmark_overlay">
                <div class="bookmark_plain" onclick="toggleClass(this)"></div>
            </div>-->
		</div>

		<div class="discount" id="big_div_height">
			<div class="col-xs-12 rest_name">
				<a style="color:black;cursor:pointer;" href="{{URL::to('/')}}/<?php echo $current_city;?><?php echo '/alacarte/'.$row['slug'];?>">
					<h3><?php echo $row['restaurant'];?></h3>
				</a>
			</div>
			<div class="col-xs-5 text-center">
				<p><?php echo $row['cuisine'];?></p>
			</div>
			<div class="col-xs-2">
				<span>&bull;</span>
			</div>

			<div class="col-xs-5 text-center" id="rupee_symobol">
				<?php
				if(strtolower($row['pricing_level']) == 'low'){
					$price_tag = "<img src='/assets/img/ruppee_14.png' title='Low' />";
				} else if(strtolower($row['pricing_level']) == 'medium'){
					$price_tag = "<img src='/assets/img/ruppee14x2.png' title='Medium' />";
				} else if(strtolower($row['pricing_level']) == 'high'){
					$price_tag = "<img src='/assets/img/ruppee14x3.png' title='High' />";
				}
				?>
				<p><?php echo $price_tag;?></p>
			</div>

			<div class="col-xs-12 location text-center">
				<p><?php echo $row['area'];?></p>
			</div>
			<?php if(isset($row['review_count']) && $row['review_count'] > 0) {?>
			<div class="col-xs-12 text-center" style="padding-bottom:10px;float:left;">
				<div>
											<span class="star-all">
												<?php for($c=0;$c<$row['full_stars'];$c++){ ?>
												<span class="ala-star-icon full">&#9733;</span>
												<?php } ?>
												<?php if(isset($row['half_stars']) && $row['half_stars'] == 1) {?>
												<span class="ala-star-icon half">&#9733;</span>
												<?php }?>
												<?php for($c=1;$c<=$row['blank_stars'];$c++){?>
												<span class="ala-star-icon">&#9733;</span>
												<?php } ?>
											</span>
					<div class="rating_ala rating_review_point" id="rating_review">(<?php echo $row['review_count']; ?> <?php echo (($row['review_count'] > 1) ? 'Reviews' : 'Review');?>)</div>
				</div>
			</div>
			<?php } ?>
			<div><br></div>


			<div class="col-xs-6 col-sm-12 col-md-6 text-center" id="reserve_table reserve_a_table" style="padding-bottom:8px;width:100%;">
				<a href="{{URL::to('/')}}/<?php echo $current_city;?><?php echo '/alacarte/'.$row['slug'];?>" class="btn btn-inverse">Reserve A Table</a>
			</div>
			<div class="clearfix"></div>
		</div>
	</li>
	<?php }
	}
	?>
</ul>