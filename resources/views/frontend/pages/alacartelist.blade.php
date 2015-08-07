@extends('frontend.templates.inner_pages')

@section('content')

<?php 
if(Session::has('id'))
	$set_user_id = Session::get('id');
else
	$set_user_id = 0;
?>
<?php 
/*
<meta property="og:image" content="http://wowtables.com/uploads/list_image_8f2b905b7886f63f04a5793c4d06ac5c2%20Small.jpg" />
<meta property="og:image" content="http://wowtables.com/uploads/list_image_086cb459a1cdded82747cfe3133782db1%20Small.jpg" />
*/
?>
<script type="text/javascript" src="{{URL::to('/')}}/assets/js/jquery.lazyload.js"></script>
<!--<script type="text/javascript" src="{{URL::to('/')}}/js/jquery-ui-1.10.0.custom.min.js"></script>-->
<!--<script type="text/javascript" src="{{URL::to('/')}}/assets/js/search.js"></script>-->
<link rel="stylesheet" href="{{URL::to('/')}}/assets/css/jquery-ui.css">
<style>
	.lazy{
		height:auto;
		background: #ebebeb repeat;
	}
	.ui-autocomplete { max-height: 200px; max-width:300px;overflow-y: scroll; overflow-x: hidden;}
</style>
<script type="text/javascript">
function toggleClass(el){ if(el.className == "bookmark_plain"){ el.className = "bookmark_marked"; } else { el.className = "bookmark_plain"; } } 

/*$(function() {
     $("img.lazy").lazyload({
         effect : "fadeIn"
     });
});*/

$(document).ready(function(){

	$(".hide_suggestion_note").on("click" , function(){
			var id = $(this).attr('data-user_id');
			console.log("user_id = "+id);
			$.ajax({
				url: "{{URL::to('/')}}custom_search/hide_suggestion_for_user",
				dataType: "JSON",
				type: "post",
				data: {user_id:id},
				success: function(d) {
				}
			});

		});

	$( "#alacarte-datepicker" ).datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: 0
	});

	$( "#alacarte-datepicker-small" ).datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: 0
	});
});

</script>
<input type="hidden" name="current_city" value="<?php echo $current_city;?>">
<div class="col-md-12 col-sm-12" style="padding-bottom: 15px;">
	<img class="alacate_banner_img_mob img-responsive visible-xs" src="/assets/img/alacarte_img.jpg" alt="alacarte image" />
	<img class="alacate_banner_img_main img-responsive hidden-xs" src="/assets/img/collection.jpg" alt="alacarte image" />
</div>

<div class="col-md-8 col-sm-8 deal-listing-left">
			<?php if(Session::has('suggestion_status') != 0) {?>
				<div class="alert alert-warning alert-dismissible" role="alert">
					
						<?php if($current_city != "delhi"):?>
							<?php if (!empty($resultCount)): ?>
								<button type="button" class="close hide_suggestion_note" data-dismiss="alert" data-user_id="<?php echo $this->session->userdata('id');?>"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<p class="lead">
										<em>"WowTables partners with the best restaurants in <?php echo ucfirst($current_city); ?> to bring you unique and exclusive dining experiences."</em>
								</p>

							<?php else: ?>
									<button type="button" class="close hide_suggestion_note" data-dismiss="alert" data-user_id="<?php echo $this->session->userdata('id');?>"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<p class="lead">
											<em>The WowTables team is working hard to launch our first set of experiences for <?php echo ucfirst($current_city); ?>. To	find out how you can be on our VIP launch list for <?php echo ucfirst($current_city); ?> <?php echo anchor('http://vip.gourmetitup.com?lrRef=wetsMj', 'click here');?>.</em>
										</p>
							<?php endif; ?>
						 <?php else:?>
								<button type="button" class="close hide_suggestion_note" data-dismiss="alert" data-user_id="<?php echo $this->session->userdata('id');?>"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<p class="lead">
										<em>WowTables brings you exclusive curated dining experiences at the best restaurants in <?php echo ucfirst($current_city); ?>.
										If you would like to help us curate our upcoming experiences, please <?php echo anchor('http://getstarted.gourmetitup.com/delhi-guest-curator/', 'click here');//http://getstarted.gourmetitup.com/delhi-guest-curator-special-invite?>.</em>
									</p>
						   
						<?php endif;?>
					
				</div>
			<?php }?>
			<div class="row">

            <!-- filter for small screen -->
	            <div class="col-md-12 visible-xs visible-sm">
	              <div class="widget filter-widget-wrap small">
	                <h3 class="text-center">Refine your search</h3>
					<span id="date_error_small" style="color:red;display:none;padding: 10px;text-align: justify;">Selected date should be greater than or equal to todays date!</span>
	                <div class="filter-widget">
	                  <form role="form" id="alacarte_custom_refine_search_form">
	                    <div class="form-group">
	                      <label for="">Search</label> 
	                      <div class="input-group ">
	                        <input type="text" id="alacarte_search_by_rest" data-items="4" data-provide="typeahead" class="form-control" placeholder="Restaurant, Cuisine or Suburb"><span class="input-group-addon" id="Alacarte_Manual_Search" style="cursor:pointer;"><i class="glyphicon glyphicon-search"></i></span>
	                      </div>
	                    </div>                                             
	                  <div class="more-filter">
	                    <div class="panel-group" id="accordion-small">
	                      <div class="panel panel-default">
	                        <div class="panel-heading">
	                          <p class="panel-title text-center">
	                            <a data-toggle="collapse" data-parent="#accordion-small" href="#AlacartecollapseOneSmall">
	                              More Options <span class="caret"></span>
	                            </a>
	                          </p>
	                        </div>
	                        <div id="AlacartecollapseOneSmall" class="panel-collapse collapse">
	                          <div class="panel-body">
	  
	                          <div class="form-group date-group">
	                      <label for="">Select a Date</label>
	                      <div class="input-group">
	                        <input type="text" id="alacarte-datepicker-small" class="form-control" placeholder="">
	                      </div>
	                    </div>
	                    <div class="form-group time-group">
	                      <label for="">Select a Time</label>
	                        <select class="form-control" id="Alacarte_Search_By_Time" placeholder="Choose a time">
	                          <option value="">--Select--</option>  
							  <option value="lunch">Lunch</option>
							  <option value="dinner">Dinner</option>
							  <option value="">----</option>
							  <option value="11:00">11 am</option>
							  <option value="12:00">12 pm</option>
							  <option value="13:00">1 pm</option>
							  <option value="14:00">2 pm</option>
							  <option value="18:00">6 pm</option>
							  <option value="19:00">7 pm</option>
							  <option value="20:00">8 pm</option>
							  <option value="21:00">9 pm</option>
							  <option value="22:00">10 pm</option>
							  <option value="23:00">11 pm</option>
							  <option value="00:00">12 am</option>
	                        </select>
	                    </div>
	                    <div class="form-group">
	                  <label for="amount">Price range:</label>
						<div class="row">
							<div class="col-sm-4 col-xs-4">
								<div class="btn-group" data-toggle="buttons">
								  <label class="btn btn-warning" id="rupee_symbol">
									<input type="checkbox" class="alacarte_search_by_price_type" value="Low" style="padding-left:50px;"><span style="font-family:serif;">&#x20B9;</span>
								  </label>  
							  </div>
							</div>
							<div class="col-sm-4 col-xs-4">
								<div class="btn-group" data-toggle="buttons">
								  <label class="btn btn-warning" id="rupee_symbol">
									<input type="checkbox" class="alacarte_search_by_price_type" value="Medium" style="padding-left:50px;"><span style="font-family:serif;">&#8377;&#8377;</span>
								  </label>  
							  </div>
							</div>
							<div class="col-sm-4 col-xs-4">
								<div class="btn-group" data-toggle="buttons">
								  <label class="btn btn-warning" id="rupee_symbol">
									<input type="checkbox" class="alacarte_search_by_price_type" value="High" style="padding-left:50px;"><span style="font-family:serif;">&#8377;&#8377;&#8377;</span>
								  </label>  
							  </div>
							</div>
						</div>             
	                </div>
	                            <div class="area option">
	                              <p class="lead">Area</p>
	                              <div class="alacarte_dynamic_areas">
									<?php  
									if(isset($filters['locations']))
									{
										foreach($filters['locations'] as $key => $allAreasData){  ?>
										<div class="checkbox">
										  <label>
											<input class="alacarte_search_by_place" type="checkbox" value="<?php echo $allAreasData['id'];?>">
											<?php echo $allAreasData['name'];?> <span class="badge"><?php echo $allAreasData['count'];?></span>
										  </label>
										  </div>
										<?php }
									}
									?>
								  </div>
	                            </div>
	                            <div class="cuisine option" style="float:none;">
	                              <p class="lead">Cuisine</p>
	                              <div class="alacarte_dynamic_cuisine">
									<?php  
									if(isset($filters['cuisines']))
									{
										foreach($filters['cuisines'] as $key => $allCuisinesData)
										{  
											?>
											<div class="checkbox">
											  <label>
												<input class="alacarte_search_by_cuisine" type="checkbox" value="<?php echo $allCuisinesData['id'];?>">
												<?php echo $allCuisinesData['name'];?> <span class="badge"><?php echo $allCuisinesData['count'];?></span>
											  </label>
											  </div>
											<?php 
										}
									}
									?>
								  </div>
	                            </div>
	                            <div class="tags option" style="float:left;">
	                              <p class="lead">Tags</p>
	                              <div class="btn-group alacarte_dynamic_tags" data-toggle="buttons">
									<?php  
									if(isset($filters['tags']))
									{
										foreach($filters['tags'] as $key => $alltagsData)
										{  
											?>
											<label class="btn btn-warning">
												<input type="checkbox" class="alacarte_search_by_tags" value="<?php echo $alltagsData['id'];?>"> <?php echo $alltagsData['name']?>
											</label>  
											<?php 
										}
									}
									?>
								  </div>
	                            </div>
	                          </div>

	                        </div>
	                      </div>
	                    </div>
	                  </div>  
	                  <p class="text-center" style="margin-top: 15px;"><button class="btn btn-warning text" type="button" id="alacarte_reset_filters">Reset Filters</button></p>
					  <!--<p class="small text-center"><a href="#" id="reset_filters">Reset Filters</a></p>-->
	                  </form>          
	                </div>
	              </div>
	            </div> <!-- end filter widget -->
				<script>
				  $(document).ready(function(){
					$( "#ala_accordion" ).accordion({header: "h3", collapsible: true, active: false});
				  });
					
				  
				  </script>

			<div id="alacarte-left-content" style="clear:both;">
				<div id="exp_list_load_layer" class="hidden">
					<img src="{{URL::to('/')}}/images/Loading-Image.gif">
				</div>
			<?php //echo "count of id = ".count($rows['id']);//echo "<pre>"; print_r($rows);?>
			<div class="col-sm-8">
				<?php $set_exp_name = (($resultCount == 1) ? "experience" : "experiences")?>
				<p class="sort-info"><?php echo ((($resultCount)>0) ? $resultCount." ".$set_exp_name." match your search criteria" : "No experiences match your search criteria"); ?></p>
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
			</div>
		</div>  

		</div>
		<!--Filter for desktop screens-->
		<div class="col-md-4 col-sm-4 deal-listing-right">
			<div class="widget filter-widget-wrap hidden-xs hidden-sm">
	          <h3 class="text-center">Refine your search</h3>
			  <span id="date_error" style="color:red;display:none;padding: 10px;text-align: justify;">Selected date should be greater than or equal to todays date!</span>
	            <div class="filter-widget">
	              <form role="form" id="alacarte_custom_refine_search">
	                <div class="form-group">
	                  <label for="">Search</label> 
	                  <div class="input-group ">
	                    <input type="text" data-items="4" data-provide="typeahead" class="form-control" id="alacarte_search_by" placeholder="Restaurant, Cuisine or Suburb" ><span style="cursor:pointer;"class="input-group-addon" id="alacarte_manual_search"><i class="glyphicon glyphicon-search"></i></span>
	                  </div>
					  <span class="search-ajax-loader"></span>
					  <input type="hidden" name="id-holder" id="id-holder"> 
	                </div>
	                <div class="form-group date-group">
	                  <label for="">Select a Date</label>
	                  <div class="input-group">
	                    <input type="text" id="alacarte-datepicker" class="form-control" placeholder=""><!--<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>-->
	                  </div>
	                </div>
	                <div class="form-group time-group">
	                  <label for="">Select a Time</label>
	                    <select class="form-control" id="alacarte_search_by_time" placeholder="Choose a time">
	                      <option value="">--Select--</option>  
	                      <option value="lunch">Lunch</option>
	                      <option value="dinner">Dinner</option>
	                      <option value="">----</option>
	                      <option value="11:00">11 am</option>
	                      <option value="12:00">12 pm</option>
	                      <option value="13:00">1 pm</option>
	                      <option value="14:00">2 pm</option>
	                      <option value="18:00">6 pm</option>
	                      <option value="19:00">7 pm</option>
	                      <option value="20:00">8 pm</option>
	                      <option value="21:00">9 pm</option>
	                      <option value="22:00">10 pm</option>
	                      <option value="23:00">11 pm</option>
	                      <option value="00:00">12 am</option>
	                    </select>
	                </div>
	                <div class="form-group">
	                  <label for="amount">Price range:</label>
						<div class="row">
							<div class="col-sm-4">
								<div class="btn-group" data-toggle="buttons">
								  <label class="btn btn-warning" id="rupee_symbol">
									<input type="checkbox" class="alacarte_search_by_price_type" value="Low" style="padding-left:50px;"><span style="font-family:serif;">&#x20B9;</span>
								  </label>  
							  </div>
							</div>
							<div class="col-sm-4">
								<div class="btn-group" data-toggle="buttons">
								  <label class="btn btn-warning" id="rupee_symbol">
									<input type="checkbox" class="alacarte_search_by_price_type" value="Medium" style="padding-left:50px;"><span style="font-family:serif;">&#8377;&#8377;</span>
								  </label>  
							  </div>
							</div>
							<div class="col-sm-4">
								<div class="btn-group" data-toggle="buttons">
								  <label class="btn btn-warning" id="rupee_symbol">
									<input type="checkbox" class="alacarte_search_by_price_type" value="High" style="padding-left:50px;"><span style="font-family:serif;">&#8377;&#8377;&#8377;</span>
								  </label>  
							  </div>
							</div>
						</div>             
	                </div>                          
	              
	              <div class="more-filter">
	                <div class="panel-group" id="accordion">
	                  <div class="panel panel-default">
	                    <div class="panel-heading">
	                      <p class="panel-title text-center">
	                        <a data-toggle="collapse" data-parent="#accordion" href="#AlacartecollapseOne">
	                          More Options <span class="caret"></span>
	                        </a>
	                      </p>
	                    </div>
	                    <div id="AlacartecollapseOne" class="panel-collapse collapse">
	                      <div class="panel-body">                        
	                        <div class="area option">
	                          <p class="lead">Area</p>
							  <div class="alacarte_dynamic_areas">
								<?php
								if(isset($filters['locations']))
								{
									foreach($filters['locations'] as $key => $allAreasData)
									{  ?>
									
									<div class="checkbox">
									  <label>
										<input class="alacarte_search_by_place" type="checkbox" value="<?php echo $allAreasData['id'];?>">
										<?php echo $allAreasData['name'];?> <span class="badge"><?php echo $allAreasData['count'];?></span>
									  </label>
									  </div>
									<?php 
									}
								}
								?>
	                          </div>
	                        </div>
	                        <div class="cuisine option" style="float:none;">
	                          <p class="lead">Cuisine</p>
	                          <div class="alacarte_dynamic_cuisine">
								<?php  
								if(isset($filters['cuisines']))
								{
									foreach($filters['cuisines'] as $key => $allCuisinesData)
									{  
										?>
										<div class="checkbox">
										  <label>
											<input class="alacarte_search_by_cuisine" type="checkbox" value="<?php echo $allCuisinesData['id'];?>">
											<?php echo $allCuisinesData['name'];?> <span class="badge"><?php echo $allCuisinesData['count'];?></span>
										  </label>
										  </div>
										<?php 
									}
								}
								?>
	                          </div>
	                        </div>
	                        <div class="tags option" style="float:left;">
	                          <p class="lead">Tags</p>
	                          <div class="btn-group alacarte_dynamic_tags" data-toggle="buttons">
	                            <?php  
								if(isset($filters['tags']))
								{
									foreach($filters['tags'] as $key => $alltagsData)
									{  
										?>
										<label class="btn btn-warning">
											<input type="checkbox" class="alacarte_search_by_tags" value="<?php echo $alltagsData['id'];?>"> <?php echo $alltagsData['name']?>
										</label>  
										<?php 
									}
								}
								?>
	                          </div>
	                        </div>
	                      </div>

	                    </div>
	                  </div>
	                </div>
	              </div>  
	              <p class="text-center" style="margin-top: 15px;"><button class="btn btn-warning text" type="button" id="alacarte_reset_form">Reset Filters</button></p>
				  <!--<p class="small text-center"><a href="javascript:void(0);" id="reset_form">Reset Filters</a></p>-->
				  </form>
	            </div>
	          </div>
			<?php foreach($ListpageSidebars as $sidebars){ ?>
			<div class="widget">

				<?php if(isset($sidebars->link) && $sidebars->link != ''){ ?>
				<a href="<?php echo $sidebars->link;?>">
					<?php } ?>
					<img src="<?php echo $listpage_sidebar_url.$sidebars->imagename;?>" class="img-responsive" alt="WowTables Concierge">
					<?php if(isset($sidebars->link) && $sidebars->link != ''){ ?>
				</a>
				<?php } ?>
				<?php if((isset($sidebars->promotion_title) && $sidebars->promotion_title != '') && (isset($sidebars->description) && $sidebars->description != '')){ ?>
				<div class="desc">
					<p><?php echo $sidebars->promotion_title;?></p>
					<p class="small"> <?php echo strip_tags($sidebars->description);?></p>
				</div>
				<?php } ?>

			</div>
			<?php } ?>
        </div>
	</div>
</div>
<input type='hidden' name='hdn_search_type' id='hdn_search_type' value=""/>
<input type='hidden' name='hdn_search_id' id='hdn_search_id' value=""/>
<script type="text/javascript">
	$(document).ready(function(){
		var c = $("#uri_city_id").val();

		/*** alacarte clear filter tags starts here***/

			$(".alacarte_clear_filters").click(function(){ console.log("clicke");
				window.location.href = '{{URL::to('/')}}/mumbai/alacarte';
			});

			/*** alacarte clear filter tags ends here***/
		
		$(".price_range").on("change",function(){
			var v = $(this).val();
			$("#range_value").val(v);
		});
		/*** alacarte autocomplete and on select ajax starts from here***/
		$('#alacarte_search_by, #alacarte_search_by_rest').autocomplete({
			source: function( request, response ) {

				$.ajax({
					url: "/alacarte_custom_search/new_custom_search",
					dataType: "JSON",
					data: {
						term: request.term,city : c
					},
					success: function( data ) {
						//console.log('response for all== '+data);
						response( data );
					}
				});
			},
			focus: function( event, ui ) { //console.log('ui = '+ui.item.label);
				var itemArr = ui.item.label.split('~~~');
				$( this ).val( itemArr[0] );
				return false;
			},
			select: function(event,ui){ //console.log("called");
				event.preventDefault();
				var itemArr = ui.item.value.split('~~~');
				
				var rest_val = itemArr[0];			
				var date_val = $("#datepicker").val();
				var time_val = $("#search_by_time").val();
				var sList1 = "";

				$( ".alacarte_search_by_price_type" ).each(function() {
					var sThisVal1 = (this.checked ? $(this).val() : "null");
					if(sThisVal1 != "null") {
						sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
					}
				});
				
				var sList2        = "";
				var sList3        = "";
				var sList4         = "";

				if(itemArr[2] == 'location' || itemArr[2] == 'no_data')
				{
					sList2	= 	itemArr[1]
				}

				if(itemArr[2] == 'cuisine')
				{
					sList3	= 	itemArr[1]
				}

				if(itemArr[2] == 'vendor')
				{
					sList4	= 	itemArr[1]
				}

				$( "#alacarte_search_by_rest" ).val( rest_val);
				$( "#alacarte_search_by" ).val( rest_val );

				$( "#hdn_search_id" ).val( itemArr[1]);
				$( "#hdn_search_type" ).val(itemArr[2]);
				
				var c = $("#uri_city_id").val();
				search_var = 1;
				//console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val);
				$.ajax({

					url: "/alacarte_custom_search/search_filter",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1, city: c,area_values : sList2,cuisine_values : sList3,vendor_value : sList4},
					beforeSend:function(){
						//$(".show_loading_img").css("display","block");
						$('#exp_list_load_layer').removeClass('hidden');
					},
					success: function(d) {
					  console.log(d.area_count);
					  $("#alacarte-left-content").fadeOut(500, function() {
							$("#alacarte-left-content").empty();
							$("#alacarte-left-content").html(d.restaurant_data);
						});
						var area_replace = '';
						$.each(d.area_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							area_replace += '<div class="checkbox"><label><input class="alacarte_search_by_place" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
						});

						var cuisine_replace = '';
						$.each(d.cuisine_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							cuisine_replace += '<div class="checkbox"><label><input class="alacarte_search_by_cuisine" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
						});

						var tags_replace = '';
						$.each(d.tags_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
						});

					  
					if(area_replace == "") {
						area_replace = "No Areas found";
					}
					if(cuisine_replace == "") {
						cuisine_replace = "No Cuisine found";
					}
					if(tags_replace == "") {
						tags_replace = "No Tags found";
					}
					  $(".alacarte_dynamic_areas").html(area_replace);
					  $(".alacarte_dynamic_cuisine").html(cuisine_replace);
					  $(".alacarte_dynamic_tags").html(tags_replace);
					  
					},
					complete: function() {
						$(".show_loading_img").css("display","none");
						$("#alacarte-left-content").fadeIn(500);
						$('#exp_list_load_layer').addClass('hidden');
						$('html, body').animate({
							scrollTop: $('#alacarte-left-content').offset().top
						}, 'slow');
					},
					timeout: 9999999
				  });
			},
			create: function () {
	          $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
	               itemArr = item.value.split('~~~');
					return $( "<li>" )
					.append( "<a data-id='"+itemArr[1]+"' data-type='"+itemArr[2]+"'>"+itemArr[0] + "</a>" )
					.appendTo( ul );
	            };
	        },	
			minLength: 1
		});

		/*** alacarte autocomplete and on select ajax ends here***/

		/*** alacarte manual search starts from here***/

		$("#alacarte_manual_search").click(function(){
			var rest_val = $("#alacarte_search_by").val();
			//var rest_val = $(this).val();
			var date_val = $("#alacarte-datepicker").val();
			var time_val = $("#alacarte_search_by_time").val();
			var sList1 = "";

			$( ".alacarte_search_by_price_type" ).each(function() {
				var sThisVal1 = (this.checked ? $(this).val() : "null");
				if(sThisVal1 != "null") {
					sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
				}
			});
			//var amount_value = $("#amount").val();
			
			var c = $("#uri_city_id").val();
			search_var = 1;
			$("#alacarte_search_by").val(rest_val);
			$("#alacarte_search_by_rest").val(rest_val);

			console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val);
			//ajax call beings required results and according to results bring area,cuisine and tags results if any of above values are not null  
			if(rest_val != "") {
			  $.ajax({
				//url: "custom_search/search_result",
				url: "/alacarte_custom_search/search_filter",
				dataType: "JSON",
				type: "post",
				//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
				data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1, city: c},
				beforeSend:function(){
					//$(".show_loading_img").css("display","block");
					$('#exp_list_load_layer').removeClass('hidden');
				},
				success: function(d) {
				  $("#alacarte-left-content").fadeOut(500, function() {
							$("#alacarte-left-content").empty();
							$("#alacarte-left-content").html(d.restaurant_data);
						});
					var area_replace = '';
					$.each(d.area_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="alacarte_search_by_place" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
					});

					var cuisine_replace = '';
					$.each(d.cuisine_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="alacarte_search_by_cuisine" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
					});

					var tags_replace = '';
					$.each(d.tags_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
					});

					  
					if(area_replace == "") {
						area_replace = "No Areas found";
					}
					if(cuisine_replace == "") {
						cuisine_replace = "No Cuisine found";
					}
					if(tags_replace == "") {
						tags_replace = "No Tags found";
					}
					  $(".alacarte_dynamic_areas").html(area_replace);
					  $(".alacarte_dynamic_cuisine").html(cuisine_replace);
					  $(".alacarte_dynamic_tags").html(tags_replace);
				  
				},
				complete: function() {
					$(".show_loading_img").css("display","none");
					$("#alacarte-left-content").fadeIn(500);
					$('#exp_list_load_layer').addClass('hidden');
					$('html, body').animate({
						scrollTop: $('#alacarte-left-content').offset().top
					}, 'slow');
				},
				timeout: 9999999
			  });
			}
		});

		/*** alacarte manual search ends here***/

		/*** alacarte manual search for mobile starts from here***/

		$("#Alacarte_Manual_Search").click(function(){
			var rest_val = $("#search_by_rest").val();
			//var rest_val = $(this).val();
			var date_val = $("#alacarte-datepicker-small").val();
			var time_val = $("#Alacarte_Search_By_Time").val();
			var sList1 = "";

			$( ".alacarte_search_by_price_type" ).each(function() {
				var sThisVal1 = (this.checked ? $(this).val() : "null");
				if(sThisVal1 != "null") {
					sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
				}
			});
			//var amount_value = $("#amount-small").val();
			/*var final_amount = amount_value.split(' ');
			var start_from = final_amount[1];
			var end_with = final_amount[4];*/
			var c = $("#uri_city_id").val();
			search_var = 1;
			$("#alacarte_search_by").val(rest_val);
			$("#alacarte_search_by_rest").val(rest_val);

			console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val+" , amount_val = "+amount_value);
			//ajax call beings required results and according to results bring area,cuisine and tags results if any of above values are not null  
			if(rest_val != "") {
			  $.ajax({
				//url: "custom_search/search_result",
				url: "/alacarte_custom_search/search_filter",
				dataType: "JSON",
				type: "post",
				//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
				data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1, city: c},
				beforeSend:function(){
					//$(".show_loading_img").css("display","block");
					$('#exp_list_load_layer').removeClass('hidden');
				},
				success: function(d) {
				  //console.log(d.area_count);
					var area_replace = '';
					$.each(d.area_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						area_replace += '<div class="checkbox"><label><input class="alacarte_search_by_place" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
					});

					var cuisine_replace = '';
					$.each(d.cuisine_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						cuisine_replace += '<div class="checkbox"><label><input class="alacarte_search_by_cuisine" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
					});

					var tags_replace = '';
					$.each(d.tags_count,function(index, value){
						//console.log('city' + index + ',  value: ' + value);
						tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
					});

				  $("#alacarte-left-content").fadeOut(500, function() {
						$("#alacarte-left-content").empty();
						$("#alacarte-left-content").html(d.restaurant_data);
					});
				if(area_replace == "") {
					area_replace = "No Areas found";
				}
				if(cuisine_replace == "") {
					cuisine_replace = "No Cuisine found";
				}
				if(tags_replace == "") {
					tags_replace = "No Tags found";
				}
				  $(".alacarte_dynamic_areas").html(area_replace);
				  $(".alacarte_dynamic_cuisine").html(cuisine_replace);
				  $(".alacarte_dynamic_tags").html(tags_replace);
				  
				},
				complete: function() {
					$(".show_loading_img").css("display","none");
					$("#alacarte-left-content").fadeIn(500);
					$('#exp_list_load_layer').addClass('hidden');
					$('html, body').animate({
						scrollTop: $('#alacarte-left-content').offset().top
					}, 'slow');
				},
				timeout: 9999999
			  });
			}
		});

		/*** alacarte manual search for mobile ends here***/

		/*** alacarte search by date ajax starts from here***/

		$('#alacarte-datepicker').on('change', function() {
				  $("#date_error").css("display","none");
				  $('#alacarte-datepicker').css("border","");
				  var selectedDate = $('#alacarte-datepicker').datepicker('getDate');
				  var date_val = $(this).val();
				  var rest_val = $("#alacarte_search_by").val();
				  var time_val = $("#alacarte_search_by_time").val();
				  var sList1 = "";

					$( ".alacarte_search_by_price_type" ).each(function() {
						var sThisVal1 = (this.checked ? $(this).val() : "null");
						if(sThisVal1 != "null") {
							sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
						}
					});
				  //var amount_value = $("#amount").val();
				  /*var final_amount = amount_value.split(' ');
				  var start_from = final_amount[1];
				  var end_with = final_amount[4];*/
				  var c = $("#uri_city_id").val();
				  var today = new Date();
				  today.setHours(0);
				  today.setMinutes(0);
				  today.setSeconds(0);
				  if(today || selectedDate) {
					  //ajax call brings results according to date selected and accordingly area,cuisine and tags results is selected date is future date
					  if (Date.parse(today) < Date.parse(selectedDate)) {
							
							$("#date_error").css("display","none");
							$('#alacarte-datepicker').css("border","");
							
							$.ajax({
								//url: "custom_search/search_future_date_restaurant",
								url: "/alacarte_custom_search/search_filter",
								dataType: "JSON",
								type: "post",
								//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
								data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1,city: c},
								beforeSend:function(){
									//$(".show_loading_img").css("display","block");
									$('#exp_list_load_layer').removeClass('hidden');
								},
								success: function(d) {
									//$("#results").append(d);
									//console.log(d);
									var area_replace = '';
									$.each(d.area_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										area_replace += '<div class="checkbox"><label><input class="alacarte_search_by_place" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
									});

									var cuisine_replace = '';
									$.each(d.cuisine_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										cuisine_replace += '<div class="checkbox"><label><input class="alacarte_search_by_cuisine" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
									});

									var tags_replace = '';
									$.each(d.tags_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
									});

									$("#alacarte-left-content").fadeOut(500, function() {
										$("#alacarte-left-content").empty();
										$("#alacarte-left-content").html(d.restaurant_data);
									});
									//console.log(text);
									if(area_replace == "") {
										area_replace = "No Areas found";
									}
									if(cuisine_replace == "") {
										cuisine_replace = "No Cuisine found";
									}
									if(tags_replace == "") {
										tags_replace = "No Tags found";
									}
									$(".alacarte_dynamic_areas").html(area_replace);
									$(".alacarte_dynamic_cuisine").html(cuisine_replace);
									$(".alacarte_dynamic_tags").html(tags_replace);
								},
								complete: function() {
									$(".show_loading_img").css("display","none");
									$("#alacarte-left-content").fadeIn(500);
									$('#exp_list_load_layer').addClass('hidden');
									$('html, body').animate({
										scrollTop: $('#alacarte-left-content').offset().top
									}, 'slow');
								},
								timeout: 9999999
							});
						
					  } //ajax call brings todays details and accordingly area,cuisine and tags results is selected date is todays date
					  else if(Date.parse(today) == Date.parse(selectedDate)){
							$("#date_error").css("display","none");
							$('#alacarte-datepicker').css("border","");
							
							$.ajax({
								//url: "custom_search/search_todays_date_restaurant",
								url: "/alacarte_custom_search/search_filter",
								dataType: "JSON",
								type: "post",
								//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
								data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1,city: c},
								beforeSend:function(){
									//$(".show_loading_img").css("display","block");
									$('#exp_list_load_layer').removeClass('hidden');
								},
								success: function(d) {
									//$("#results").append(d);
									//console.log(d);
									var area_replace = '';
									$.each(d.area_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										area_replace += '<div class="checkbox"><label><input class="alacarte_search_by_place" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
									});

									var cuisine_replace = '';
									$.each(d.cuisine_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										cuisine_replace += '<div class="checkbox"><label><input class="alacarte_search_by_cuisine" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
									});

									var tags_replace = '';
									$.each(d.tags_count,function(index, value){
										//console.log('city' + index + ',  value: ' + value);
										tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
									});
					
									$("#alacarte-left-content").fadeOut(500, function() {
										$("#alacarte-left-content").empty();
										$("#alacarte-left-content").html(d.restaurant_data);
									});
									//console.log(text);
									if(area_replace == "") {
										area_replace = "No Areas found";
									}
									if(cuisine_replace == "") {
										cuisine_replace = "No Cuisine found";
									}
									if(tags_replace == "") {
										tags_replace = "No Tags found";
									}
									$(".alacarte_dynamic_areas").html(area_replace);
									$(".alacarte_dynamic_cuisine").html(cuisine_replace);
									$(".alacarte_dynamic_tags").html(tags_replace);
								},
								complete: function() {
									$(".show_loading_img").css("display","none");
									$("#alacarte-left-content").fadeIn(500);
									$('#exp_list_load_layer').addClass('hidden');
									$('html, body').animate({
										scrollTop: $('#alacarte-left-content').offset().top
									}, 'slow');
								},
								timeout: 9999999
							});
					  } 
					  //show error if selected date is less than todays date
					  else if (Date.parse(today) > Date.parse(selectedDate)) {

						$("#date_error").css("display","block");
						$('#alacarte-datepicker').css("border","1px solid red");
					  }
				  }
			});

			/*** alacarte search by date ajax ends here***/

			/*** alacarte search by time starts from here***/

			$("#alacarte_search_by_time").on('change',function(){ 
				var time_val = $(this).val();
				var date_val = $("#alacarte-datepicker").val();
				var rest_val = $("#alacarte_search_by").val();
				var sList1 = "";

				$( ".alacarte_search_by_price_type" ).each(function() {
					var sThisVal1 = (this.checked ? $(this).val() : "null");
					if(sThisVal1 != "null") {
						sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
					}
				});
				//var amount_value = $("#amount").val();
				
				var c = $("#uri_city_id").val();
				//console.log('final amount split = '+final_amount);
				//console.log(" first amount =="+final_amount[1]+" , second amount == "+final_amount[4]);
				//console.log("time value == "+time_val+" , date value = "+date_val+" , rest val = "+rest_val+" , start_from = "+start_from+" , end_with = "+end_with);
				if(time_val != "") {
					$.ajax({
						url: "/alacarte_custom_search/search_filter",
						dataType: "JSON",
						type: "post",
						//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
						data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1,city: c},
						beforeSend:function(){
							//$(".show_loading_img").css("display","block");
							$('#exp_list_load_layer').removeClass('hidden');
						},
						success: function(d) {
							//$("#results").append(d);
							//console.log(d);
							var area_replace = '';
							$.each(d.area_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								area_replace += '<div class="checkbox"><label><input class="alacarte_search_by_place" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
							});

							var cuisine_replace = '';
							$.each(d.cuisine_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								cuisine_replace += '<div class="checkbox"><label><input class="alacarte_search_by_cuisine" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
							});

							var tags_replace = '';
							$.each(d.tags_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
							});

							$("#alacarte-left-content").fadeOut(500, function() {
								$("#alacarte-left-content").empty();
								$("#alacarte-left-content").html(d.restaurant_data);
							});
							//console.log(text);
							if(area_replace == "") {
								area_replace = "No Areas found";
							}
							if(cuisine_replace == "") {
								cuisine_replace = "No Cuisine found";
							}
							if(tags_replace == "") {
								tags_replace = "No Tags found";
							}
							$(".alacarte_dynamic_areas").html(area_replace);
							$(".alacarte_dynamic_cuisine").html(cuisine_replace);
							$(".alacarte_dynamic_tags").html(tags_replace);
						},
						complete: function() {
							$(".show_loading_img").css("display","none");
							$("#alacarte-left-content").fadeIn(500);
							$('#exp_list_load_layer').addClass('hidden');
							$('html, body').animate({
								scrollTop: $('#alacarte-left-content').offset().top
							}, 'slow');
						},
						timeout: 9999999
					});
				} 
			});

			/*** alacarte search by time ends here***/

			/*** alacarte search by price starts from here***/

			$("body").delegate(".alacarte_search_by_price_type","change",function(){
				var rest_val = $("#alacarte_search_by").val();
				var date_val = $("#alacarte-datepicker").val();
				var time_val = $("#alacarte_search_by_time").val();
				var c = $("#uri_city_id").val();
				var sList1 = "";

				$( ".alacarte_search_by_price_type" ).each(function() {
					var sThisVal1 = (this.checked ? $(this).val() : "null");
					if(sThisVal1 != "null") {
						sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
						//console.log(sThisVal1);
					}

				});
				//console.log("slist == "+sList1);
				$.ajax({
					url: "/alacarte_custom_search/search_filter",
					dataType: "JSON",
					type: "post",
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1,city: c},
					beforeSend:function(){
						//$(".show_loading_img").css("display","block");
						$('#exp_list_load_layer').removeClass('hidden');
					},
					success: function(d) {
						//$("#results").append(d);
							//console.log("asd = "+ d.locations);
							var area_replace = '';
						    $.each(d.area_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								area_replace += '<div class="checkbox"><label><input class="alacarte_search_by_place" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
							});

						    var cuisine_replace = '';
							$.each(d.cuisine_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								cuisine_replace += '<div class="checkbox"><label><input class="alacarte_search_by_cuisine" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
							});

							var tags_replace = '';
							$.each(d.tags_count,function(index, value){
								//console.log('city' + index + ',  value: ' + value);
								tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
							});

							$("#alacarte-left-content").fadeOut(500, function() {
								$("#alacarte-left-content").empty();
								$("#alacarte-left-content").html(d.restaurant_data);
							});
							//console.log(text);
							if(area_replace == "") {
								area_replace = "No Areas found";
							}
							if(cuisine_replace == "") {
								cuisine_replace = "No Cuisine found";
							}
							if(tags_replace == "") {
								tags_replace = "No Tags found";
							}
							$(".alacarte_dynamic_areas").html(area_replace);
							$(".alacarte_dynamic_cuisine").html(cuisine_replace);
							$(".alacarte_dynamic_tags").html(tags_replace);
					},
					complete: function() {
						$(".show_loading_img").css("display","none");
						$("#alacarte-left-content").fadeIn(500);
						$('#exp_list_load_layer').addClass('hidden');
						$('html, body').animate({
							scrollTop: $('#alacarte-left-content').offset().top
						}, 'slow');
					},
					timeout: 9999999
				});
			});

			/*** alacarte search by price ends here***/

			/*** alacarte search by place starts here***/

			$("body").delegate(".alacarte_search_by_place","change",function(){ //console.log("called");
				//$(this).attr("checked","checked");
				var rest_val = $("#alacarte_search_by").val();
				var date_val = $("#alacarte-datepicker").val();
				var time_val = $("#alacarte_search_by_time").val();
				var c = $("#uri_city_id").val();
				var sList = "";
				var sList1 = "";

				$( ".alacarte_search_by_place" ).each(function() {
					var sThisVal = (this.checked ? $(this).val() : "0");
					if(parseInt(sThisVal)) {
						sList += (sList=="" ? sThisVal : "," + sThisVal+"");
					}
				});

				$( ".alacarte_search_by_price_type" ).each(function() {
					var sThisVal1 = (this.checked ? $(this).val() : "null");
					if(sThisVal1 != "null") {
						sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
					}
				});
				//console.log (sList);
				//console.log("time value == "+time_val+" , date value = "+date_val+" , rest val = "+rest_val+" , start_from = "+start_from+" , end_with = "+end_with+" , sList = "+sList);
				$.ajax({
					url: "/alacarte_custom_search/search_filter",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1,area_values : sList,city: c},
					beforeSend:function(){
						//$(".show_loading_img").css("display","block");
						$('#exp_list_load_layer').removeClass('hidden');
					},
					success: function(d) {
						//$("#results").append(d);
						//console.log(d);
						console.log("here = "+d);
						var cuisine_replace = '';
						$.each(d.cuisine_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							cuisine_replace += '<div class="checkbox"><label><input class="alacarte_search_by_cuisine" type="checkbox" value="'+value.id+'">'+value.name+'<span class="badge">'+value.count+'</span></label></div>'
						});

						var tags_replace = '';
						$.each(d.tags_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
						});

						$("#alacarte-left-content").fadeOut(500, function() {
							$("#alacarte-left-content").empty();
							$("#alacarte-left-content").html(d.restaurant_data);
						});
						//console.log(text);
						//$(".dynamic_areas").html(area_replace);
						
						if(cuisine_replace == "") {
							cuisine_replace = "No Cuisine found";
						}
						if(tags_replace == "") {
							tags_replace = "No Tags found";
						}
						$(".alacarte_dynamic_cuisine").html(cuisine_replace);
						$(".alacarte_dynamic_tags").html(tags_replace);
					},
					complete: function() {
						$(".show_loading_img").css("display","none");
						$("#alacarte-left-content").fadeIn(500);
						$('#exp_list_load_layer').addClass('hidden');
						$('html, body').animate({
							scrollTop: $('#alacarte-left-content').offset().top
						}, 'slow');
					},
					timeout: 9999999
				});
			});

			/*** alacarte search by price ends here***/

			/*** alacarte search by cuisine starts here***/

			$("body").delegate(".alacarte_search_by_cuisine","change",function(){
				//$(this).attr("checked","checked");
				var rest_val = $("#alacarte_search_by").val();
				var date_val = $("#alacarte-datepicker").val();
				var time_val = $("#alacarte_search_by_time").val();
				var c = $("#uri_city_id").val();
				var sList = "";
				var sList1 = "";
				var sList2 = "";

				$( ".alacarte_search_by_place" ).each(function() {
					var sThisVal = (this.checked ? $(this).val() : "0");
					if(parseInt(sThisVal)) {
						sList += (sList=="" ? sThisVal : "," + sThisVal+"");
					}
				});

				$( ".alacarte_search_by_price_type" ).each(function() {
					var sThisVal1 = (this.checked ? $(this).val() : "null");
					if(sThisVal1 != "null") {
						sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
					}

				});

				$( ".alacarte_search_by_cuisine" ).each(function() {
					var sThisVal2 = (this.checked ? $(this).val() : "0");
					if(parseInt(sThisVal2)) {
						sList2 += (sList2=="" ? sThisVal2 : "," + sThisVal2+"");
					}
				});
				//console.log (sList);
				$.ajax({
					url: "/alacarte_custom_search/search_filter",
					//url: "custom_search/refine_search",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1,area_values : sList,cuisine_values:sList2,city: c},
					beforeSend:function(){
						//$(".show_loading_img").css("display","block");
						$('#exp_list_load_layer').removeClass('hidden');
					},
					//data: {cuisine_values : sList},
					success: function(d) {
						//$("#results").append(d);
						var tags_replace = '';
						$.each(d.tags_count,function(index, value){
							//console.log('city' + index + ',  value: ' + value);
							tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="alacarte_search_by_tags" value="'+index+'"> '+value.name+'</label>'
						});

						$("#alacarte-left-content").fadeOut(500, function() {
							$("#alacarte-left-content").empty();
							$("#alacarte-left-content").html(d.restaurant_data);
						});
						//console.log(text);
						//$(".dynamic_areas").html(area_replace);
						
						if(tags_replace == "") {
							tags_replace = "No Tags found";
						}
						
						$(".alacarte_dynamic_tags").html(tags_replace);
					},
					complete: function() {
						$(".show_loading_img").css("display","none");
						$("#alacarte-left-content").fadeIn(500);
						$('#exp_list_load_layer').addClass('hidden');
						$('html, body').animate({
							scrollTop: $('#alacarte-left-content').offset().top
						}, 'slow');
					},
					timeout: 9999999
				});
			});

			/*** alacarte search by cuisine ends here***/

			/*** alacarte search by tags starts here***/

			 $("body").delegate(".alacarte_dynamic_tags","change",function(){
				var rest_val = $("#alacarte_search_by").val();
				var date_val = $("#alacarte-datepicker").val();
				var time_val = $("#alacarte_search_by_time").val();
				var c = $("#uri_city_id").val();
				var sList = "";
				var sList1 = "";
				var sList2 = "";
				var sList3 = "";

				$( ".alacarte_search_by_place" ).each(function() {
					var sThisVal = (this.checked ? $(this).val() : "0");
					if(parseInt(sThisVal)) {
						sList += (sList=="" ? sThisVal: "," + sThisVal+"");
					}
				});

				$( ".alacarte_search_by_price_type" ).each(function() {
					var sThisVal1 = (this.checked ? $(this).val() : "null");
					if(sThisVal1 != "null") {
						sList1 += (sList1 == "" ? sThisVal1 : "," + sThisVal1 );
					}
				});

				$( ".alacarte_search_by_cuisine" ).each(function() {
					var sThisVal2 = (this.checked ? $(this).val() : "0");
					if(parseInt(sThisVal2)) {
						sList2 += (sList2=="" ? sThisVal2: "," + sThisVal2+"");
					}
				});
				
				
				$( ".alacarte_search_by_tags" ).each(function() {
					var sThisVal3 = (this.checked ? $(this).val() : "0");
					if(parseInt(sThisVal3)) {
						sList3 += (sList3=="" ? sThisVal3: "," + sThisVal3+"");
					}
				});
				//console.log (sList);
				$.ajax({
					url: "/alacarte_custom_search/search_filter",
					dataType: "JSON",
					type: "post",
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
					//data: {tags_values : sList},
					//data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,area_values : sList1,cuisine_values : sList2,tags_values : sList, city : c},
					data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,price: sList1,area_values : sList,cuisine_values:sList2,tags_values : sList3,city: c},
					beforeSend:function(){
						//$(".show_loading_img").css("display","block");
						$('#exp_list_load_layer').removeClass('hidden');
					},
					success: function(d) {
						//$("#results").append(d);
						$("#alacarte-left-content").fadeOut(500, function() {
							$("#alacarte-left-content").empty();
							$("#alacarte-left-content").html(d.restaurant_data);
						});
					},
					complete: function() {
						$(".show_loading_img").css("display","none");
						$("#alacarte-left-content").fadeIn(500);
						$('#exp_list_load_layer').addClass('hidden');
						$('html, body').animate({
							scrollTop: $('#alacarte-left-content').offset().top
						}, 'slow');
					},
					timeout: 9999999
				});
			});

			/*** alacarte search by tags ends here***/

			/*** alacarte reset filter tags starts here***/

			$("#alacarte_reset_form").on("click",function(){
				window.location.href = '{{URL::to('/')}}/mumbai/alacarte';
			});

			


			/*** alacarte reset filter tags ends here***/

	});
</script>

@endsection