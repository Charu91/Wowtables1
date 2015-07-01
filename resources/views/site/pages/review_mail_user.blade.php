<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>



<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Rewards</title>
    <style type="text/css">
        .ReadMsgBody {width: 100%; background-color: #ffffff;}
        .ExternalClass {width: 100%; background-color: #ffffff;}
        body     {width: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;font-family: Georgia, Times, serif}
        table {border-collapse: collapse;}

        @media only screen and (max-width: 640px)  {
            body[yahoo] .deviceWidth {width:440px!important; padding:0;}
            body[yahoo] .center {text-align: center!important;}
        }

        @media only screen and (max-width: 479px) {
            body[yahoo] .deviceWidth {width:280px!important; padding:0;}
            body[yahoo] .center {text-align: center!important;}
        }

    </style>
</head>

<body>
<!-- <table width="600" border="0" background="#fff" style="margin: 0 auto;  border-collapse: collapse;
  border-spacing: 0;">
  
</table> -->
<table style="width:100%;border-bottom:1px solid #eee;font-size:12px;line-height:135%;font-family:"Lucida Grande","Lucida Sans  Unicode",Tahoma,sans-serif" cellpadding="0" cellspacing="0">
    <tbody><tr style="background-color:#f5f5f5">
        <th style="vertical-align:top;color:#222;text-align:left;padding:7px 9px 7px 9px;border-top:1px solid #eee">
            Member Name 
        </th>
        <td style="vertical-align:top;color:#333;width:60%;padding:7px 9px 7px 0;border-top:1px solid #eee">
            <div>{{$member_name}}</div>
        </td>
    </tr>
    <tr style="background-color:#ffffff">
        <th style="vertical-align:top;color:#222;text-align:left;padding:7px 9px 7px 9px;border-top:1px solid #eee">
            Experience 
        </th>
        <td style="vertical-align:top;color:#333;width:60%;padding:7px 9px 7px 0;border-top:1px solid #eee">
            <div>{{$exp_name}}</div>
        </td>
    </tr>
    <tr style="background-color:#f5f5f5">
        <th style="vertical-align:top;color:#222;text-align:left;padding:7px 9px 7px 9px;border-top:1px solid #eee">
            Rate your WowTables experience 
        </th>
        <td style="vertical-align:top;color:#333;width:60%;padding:7px 9px 7px 0;border-top:1px solid #eee">
            <div>
                <span>{{$rating}}</span>                               
            </div>
        </td>
    </tr>
    <tr style="background-color:#ffffff">
        <th style="vertical-align:top;color:#222;text-align:left;padding:7px 9px 7px 9px;border-top:1px solid #eee">
            Review 
        </th>
        <td style="vertical-align:top;color:#333;width:60%;padding:7px 9px 7px 0;border-top:1px solid #eee">
            <div>{{$review_para}}</div>
        </td>
    </tr>
    <tr style="background-color:#f5f5f5">
        <th style="vertical-align:top;color:#222;text-align:left;padding:7px 9px 7px 9px;border-top:1px solid #eee">
            Name of server
        </th>
        <td style="vertical-align:top;color:#333;width:60%;padding:7px 9px 7px 0;border-top:1px solid #eee">
            <div>
                <span><?php echo (($name_server != "")? $name_server : "-"); ?></span>                               
            </div>
        </td>
    </tr>
    <tr style="background-color:#ffffff">
        <th style="vertical-align:top;color:#222;text-align:left;padding:7px 9px 7px 9px;border-top:1px solid #eee">
            Were you happy with the service
        </th>
        <td style="vertical-align:top;color:#333;width:60%;padding:7px 9px 7px 0;border-top:1px solid #eee">
            <div>{{$service}}</div>
        </td>
    </tr>
    <tr style="background-color:#f5f5f5">
        <th style="vertical-align:top;color:#222;text-align:left;padding:7px 9px 7px 9px;border-top:1px solid #eee">
            Would you like to give any feedback or suggestions to WowTables?
                    </th>
        <td style="vertical-align:top;color:#333;width:60%;padding:7px 9px 7px 0;border-top:1px solid #eee">
            <div><?php echo (($suggestion != "")? $suggestion : "-"); ?></div>
        </td>
    </tr>
    <tr style="background-color:#ffffff">
        <th style="vertical-align:top;color:#222;text-align:left;padding:7px 9px 7px 9px;border-top:1px solid #eee">
            Member ID
        </th>
        <td style="vertical-align:top;color:#333;width:60%;padding:7px 9px 7px 0;border-top:1px solid #eee">
            <div>{{$membership_num}}</div>
        </td>
    </tr>

</tbody>
</table>
</body>
</html>
