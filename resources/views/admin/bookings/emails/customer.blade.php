<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table>
    <tr><td>Dear {{ $data['cust_name'] }}</td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>Your reservation for the WowTables experience at {{ $data['restaurant_name'] }}, {{ $data['outlet'] }} is confirmed for {{ $data['date'] }} at {{ $data['time'] }} for {{ $data['no_of_people'] }} guests</td>
    </tr>
    <tr>
        <td>We hope you enjoy your experience. You can change or cancel reservations upto 2 hours in advance by visitingwowtables.com/myreservations. For last minute changes please call our concierge desk at 9619551387 or the restaurant directly. Note that certain reservations cannot be changed or cancelled.</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>Regards,</td>
    </tr>
    <tr>
        <td>WowTables Concierge</td>
    </tr>
    <tr>
        <td>9619551387</td>
    </tr>
</table>
</body>
</html>


