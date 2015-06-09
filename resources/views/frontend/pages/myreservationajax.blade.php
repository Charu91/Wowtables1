
<?php 
$weeks = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
  $week_number = date('w',strtotime($dateText));
  $week = $weeks[$week_number];
  $myscheduleTime = $data['schedule'][$vendorId][$week];
?>
<div id="time">
          <div class="btn-group col-lg-10 pull-right actives ">
            <?php 
            $i=1;
            ?>
            <!-- <label class="btn btn-warning btn-xs time_tab active" id="lunch">LUNCH</label><label class="btn btn-warning btn-xs time_tab" id="dinner">DINNER</label><label id="ac_breakfast" class="btn btn-warning btn-xs time_tab active">BREAKFAST</label>-->

            <?php foreach($myscheduleTime as $time=>$hours)
            { ?>

          <label class="btn btn-warning btn-xs time_tab <?php if($i=='2') {echo 'active';} else{'';}?>" 
            id="<?=strtolower($time)?>">{{strtoupper($time)}}</label>

          <?php $i++;}?>
          </div>
          <div class="clearfix">
          </div> 
          <input type="hidden" name="booking_time" id="booking_time" value="">
        </div>
        <div id="hours">
          <!-- <div id="lunch_tab" class=""> -->
            <!-- <div class="time col-lg-3" rel="12:00 PM"><a href="javascript:">12:00 PM</a></div>
            <div class="time col-lg-3" rel="12:30 PM"><a href="javascript:">12:30 PM</a></div><div class="time col-lg-3" rel="1:00 PM"><a href="javascript:">1:00 PM</a></div><div class="time col-lg-3" rel="1:30 PM"><a href="javascript:">1:30 PM</a></div><div class="time col-lg-3" rel="2:00 PM"><a href="javascript:">2:00 PM</a></div><div class="time col-lg-3" rel="2:30 PM"><a href="javascript:">2:30 PM</a></div><div class="time col-lg-3" rel="3:00 PM"><a href="javascript:">3:00 PM</a></div></div>
            <div id="dinner_tab" class="hidden"><div class="time col-lg-3" rel="8:00 PM"><a href="javascript:">8:00 PM</a></div><div class="time col-lg-3" rel="10:30 PM"><a href="javascript:">10:30 PM</a></div></div> -->
          <!-- </div>-->

            <?php
            $last_reservations= $last_reserv_time;
            $j=1;
               foreach($myscheduleTime as $time=>$hours):
              ?>
        <div id="<?=strtolower($time)."_tab";?>"  class="<?php if($j=='1') {echo 'hidden';} else{'';}?>">
               <?php foreach($hours as $hour):?>
                    <div class="time col-lg-3 <?=($hour == $last_reservations)? 'time_active' : '';?>" rel="<?=$hour;?>"><a href="javascript:"><?=$hour;?></a></div>
               <?php endforeach;?>
               </div>
              <?php 
              $j++;
              endforeach;
              ?>             
        </div>