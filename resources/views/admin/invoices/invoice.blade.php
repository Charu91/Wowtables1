@foreach($finaldata as $name => $vendor)
    <?php $i=0;  ?>
    @foreach($vendor as $loc_name => $reservDetails)

            <table width="100%" elname="zc-maincontent" height="100%" cellpadding="0" cellspacing="0" border="0" style="white-space: normal;">
                <tbody><tr>
                    <td valign="top">

                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <title></title>
                        <table style="font-family:Tahoma, Helvetica, Arial, Verdana; border-collapse:collapse; width:700px; font-size:13px; margin: 0 auto;">
                            <tbody><tr>
                                <td style="text-decoration: underline; padding: 0 5px; text-align:center">Invoice</td>
                            </tr>
                            <tr>
                                <td style="padding-top: 10px">
                                    <table width="100%" style="border-collapse:collapse; font-size:13px">
                                        <tbody><tr>
                                            <td width="50%" style="padding: 5px;">
                                                <div style="margin-bottom: 5px;">
                                                    <img src="http://wowtables.com/assets/img/logo_bygoumetitup.png" width="300" height="68" alt="">
                                                </div>
                                                <div>
                                                    WowTables (Tastebox Hospitality Pvt. Ltd.)<br>
                                                    254 A-Z Industrial Estate,<br>
                                                    G.K. Road, Lower Parel,<br>
                                                    Mumbai - 400013<br>
                                                    India<br>
                                                    <strong>concierge@wowtables.com | 91-40093030</strong><br>
                                                    Service Tax Number: AAECT6856GSD001<br>
                                                    CIN Number: U74900MH2013PTC245297
                                                </div>
                                            </td>
                                            <td width="50%" style="padding: 5px; vertical-align: top;">
                                                <div style="text-align: center; border: 1px solid #ccc; margin-top: 10px; padding: 5px;">
                                                    <strong>{!! $finaldata[$name][$loc_name][$i]['vendor_name'] !!}</strong><br>
                                                    {!! $finaldata[$name][$loc_name][$i]['address'] !!}
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px;">
                                    <table width="100%" style="border-collapse:collapse; font-size:13px">
                                        <tbody><tr>
                                            <td width="50%" style="border: 1px solid #ccc; padding: 5px; text-align: center;">
                                                Invoice no.: Inv 2843 A
                                            </td>
                                            <td width="50%" style="border: 1px solid #ccc; padding: 5px; text-align: center;">
                                                {!! date('d-M-Y') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" width="50%" style="border: 1px solid #ccc;padding: 5px; text-align: center;">
                                                Terms of Payment : 15 Days from issue date of the invoice
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px;">
                                    <table width="100%" style="border-collapse:collapse; border: 1px solid #ccc; font-size:13px">
                                        <tbody>
                                        <tr>
                                            <th width="50%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Reservation Details</th>
                                            <th width="50%" style="border: 1px solid #ccc; padding: 5px; text-align: right;">Commission Details</th>
                                        </tr>
                                        <tr>
                                            <th width="50%"></th>
                                            <td width="50%">
                                                <table width="100%" style="border-collapse:collapse; border: 1px solid #ccc; font-size:13px">
                                                    <tbody>
                                                    <tr>
                                                        <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Type</th>
                                                        <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Seated</th>
                                                        <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Unit</th>
                                                        <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Total</th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        @foreach($reservDetails as $data)
                                        <tr>
                                            <td width="50%" style="border: 1px solid #ccc; padding: 5px;">{!! $data['cust_name'] !!} on {!! date('d-M-Y',strtotime($data['date'])) !!} ({!! $data['vendor_name'] !!} - {!! $data['product_name'] !!}) - {!! $data['vendor_location'] !!}</td>
                                            <td width="50%">
                                            @if($data['type'] == 'experience')
                                            <table width="100%" style="border-collapse:collapse; border: 1px solid #ccc; font-size:13px">
                                                <tbody>
                                                    <!--<tr>
                                                        <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Type</th>
                                                        <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Seated</th>
                                                        <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Unit</th>
                                                        <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Total</th>
                                                    </tr>-->
                                                    <tr>
                                                        @if(isset($billinginfo[$data['reservation_id']]['base']))
                                                        <td width="33%" style="border: 1px solid #ccc; padding: 5px;">Base</td>
                                                        <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['base']['seated'] !!}</td>
                                                        <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['base']['unit'] !!}</td>
                                                        <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['base']['total'] !!}</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        @if(isset($billinginfo[$data['reservation_id']]['addon']))
                                                        <td width="33%" style="border: 1px solid #ccc; padding: 5px;">Addon</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['addon']['seated'] !!}</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['addon']['unit'] !!}</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['addon']['total'] !!}</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        @if(isset($billinginfo[$data['reservation_id']]['alacarte']))
                                                        <td width="33%" style="border: 1px solid #ccc; padding: 5px;">Alacarte</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['alacarte']['seated'] !!}</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['alacarte']['unit'] !!}</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['alacarte']['total'] !!}</td>
                                                        @endif
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @elseif($data['type'] == 'alacarte')
                                                    <table width="100%" style="border-collapse:collapse; border: 1px solid #ccc; font-size:13px">
                                                        <tbody>
                                                        <!--<tr>
                                                            <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Type</th>
                                                            <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Seated</th>
                                                            <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Unit</th>
                                                            <th width="33%" style="border: 1px solid #ccc; padding: 5px; text-align: left;">Total</th>
                                                        </tr>-->
                                                        <tr>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">Alacarte</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['seated'] !!}</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['unit'] !!}</td>
                                                            <td width="33%" style="border: 1px solid #ccc; padding: 5px;">{!! $billinginfo[$data['reservation_id']]['total'] !!}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                            @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td style="padding: 5px; border-top: 1px solid #ccc;" colspan="2"><strong>Add:- Service Tax</strong></td>
                                            <td style="padding: 5px; text-align: center;"><strong>14.00%</strong></td><td style="padding: 5px; text-align: right;"><strong>{!!  $billinginfo[$data['reservation_id']]['total_commission'] !!}</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;" colspan="2"><strong>Gross Amount</strong></td>
                                            <td></td>
                                            <td style="padding: 5px; text-align: right;"><strong>
                                                    <?php
                                                        //print_r($billinginfo[$data['reservation_id']]);

                                                        $addAmount = $billinginfo[$data['reservation_id']]['total_commission']*(14/100);
                                                        $gross_amount = $billinginfo[$data['reservation_id']]['total_commission']+$addAmount;
                                                    ?>
                                                    {!! round($gross_amount,2)  !!}</strong></td>
                                        </tr>
                                        <tr><td style="padding: 5px;" colspan="2"><strong>  Three Thousand Seven Hundred Thirty   only.</strong></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #ccc; padding: 5px; font-weight: bold" colspan="4">
                                                Please make the cheque in favor of "Tastebox Hospitality Pvt. Ltd"<br>
                                                Address: 157, A-Z industrial estate, Lower Parel, G.K. Road, Mumbai 400013, India<br>
                                                <br>
                                                In case you would like to make the payment through NEFT, here are the details:<br>
                                                <br>
                                                PAN No: AAECT6856G<br>
                                                Bank Name: HDFC Bank Ltd.<br>
                                                Bank Account Number: 50200002278082<br>
                                                Bank MICR Number (9 digits): 400240002<br>
                                                NEFT IFSC Code (11 digits): HDFC0000542<br>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </td>
                            </tr>
                            </tbody></table>




                    </td>
                </tr>
                </tbody></table>

    @endforeach
    <?php $i++;  ?>
@endforeach