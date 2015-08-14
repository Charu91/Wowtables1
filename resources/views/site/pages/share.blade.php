<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>



<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Share Details</title>
</head>

<body>
    <p>{{$share_data['user']}} said "{{$share_data['content']}}"</p>
    <?php if($share_data['reservation_type'] == "experience_detail") { ?>
        <p>{{$share_data['user']}} has suggested you view WowTables dining experience at {{$share_data['restaurant_name']}}.</p>
    <?php } else { ?>
        <p>{{$share_data['user']}} has invited you to a WowTables dining experience at {{$share_data['restaurant_name']}} on {{$share_data['date_reservation']}} at {{$share_data['date_seating']}}.</p>
    <?php }?>
    <p>The WowTables experience you will be enjoying:<br>
    <?php if($share_data['reservation_type'] == "experience_detail") { ?>
        Experience: {{$share_data['expname']}}.
    <?php } ?>
    <?php if($share_data['reservation_type'] == "alacarte_detail") { ?>
        Restaurant name : {{$share_data['restaurant_name']}}<br/>
        Location : {{$share_data['outlet_name']}}
    <?php } ?>
    <?php if($share_data['reservation_type'] == "experience"){?>
        Description: {{$share_data['short_description']}}<br>
    <?php } ?>
    <?php if($share_data['reservation_type'] == "experience" || $share_data['reservation_type'] == "alacarte"){?>
        Venue:{{$share_data['restaurant_name']}}, {{$share_data['outlet_name']}}<br>
        Guest:{{$share_data['guests']}}</p>
    <?php } ?>

    <p><a href="{{$share_data['url_product']}}" target="_blank">Click here to view this experience on WowTables.com</a></p>
    <p>Join <a href="http://wowtables.com" target="_blank">WowTables.com</a> to make reservations for exclusive fine dining experiences at the best restaurants.</p><br>

    <?php if(isset($share_data['is_admin']) && $share_data['is_admin'] == "yes"){ ?>
        <p>Forwaded Emails:</p>
            <?php
            $emails= explode(',',$share_data['emails_list']);
                foreach($emails as $email){?>
                <p><?php echo $email;?></p>
            <?php }
         } ?>
</body>
</html>