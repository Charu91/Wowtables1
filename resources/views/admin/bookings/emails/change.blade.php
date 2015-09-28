<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table>
    <tr>
        <td>There is change in the WowTables reservation for {{ $data['cust_name'] }}</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>The new reservation is as follows:
        <br/>Guest Name: {{ $data['cust_name'] }}
        <br/>Guest Contact: {{ $data['contact'] }}
        <br/>Guest Email: {{ $data['email'] }}
        <br/>No Of Guests: {{ $data['no_of_people'] }}
        <br/>Date Of Reservation: {{ $data['date'] }}
        <br/>Time Of Reservation: {{ $data['time'] }}
        <br/>Experience: {{ $data['experience'] }}
        <br/>Outlet: {{ $data['outlet'] }}
        </td>
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
