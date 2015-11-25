@if(!empty($invoices))
    <div id="invoices_list" class="mt-lg">
        <div class="panel-body">
            <table class="table" id="invoices">
                <thead>
                <tr>
                    <th>Vendor Details</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoices as $name => $vendor)
                    <?php $vendorDetails = explode('-',$name); ?>
                    <tr><td style="background: #eeeeff;text-align: center;font-weight: bolder;">{!! $vendorDetails[0] !!}</td>
                        <td>
                            <button class="btn btn-primary btn-xs" id="vendor" data-vendor-id ="{!! $vendorDetails[1] !!}">Generate All</button>
                        </td>
                    </tr>
                    @foreach($vendor as $loc_name => $reservDetails)
                        <?php $locDetails = explode('-',$loc_name);?>
                        <tr><td style="background: #eeeeee;text-align: center;font-weight: 300;">{!! $locDetails[0] !!}</td>
                            <td>
                                <button class="btn btn-primary btn-xs" id="vendor_location" data-vendor-location-id ="{!! $locDetails[1] !!}" data-vendor-id ="{!! $vendorDetails[1] !!}">Generate</button>
                            </td></tr>
                        @foreach($reservDetails as $data)
                            <tr><td>
                                {!! $data['cust_name']  !!} on {!! $data['date'] !!} for
                                @if(!empty($data['product_name']))
                                    {!! $data['product_name'] !!}
                                @else
                                    Alacarte
                                @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
        <h3>No records</h3>
    @endif
    <form method="POST" action="/admin/invoice/pdf" id="finalpdf" name="finalpdf">
    <input type="hidden" name="vendor_reservation_id" id="vendor_reservation_id" value="@if(isset($vendor_reservation_id)){!! $vendor_reservation_id !!}@endif">
    <input type="hidden" name="vendor_location_reservation_id" id="vendor_location_reservation_id" value="@if(isset($vendor_location_reservation_id)){!! $vendor_location_reservation_id !!}@endif">
    <input type="hidden" name="finalvendor_id" id="finalvendor_id">
    <input type="hidden" name="finalvendorlocl_id" id="finalvendorlocl_id">
    <input type="hidden" name="finalreserv_ids" id="finalreserv_ids">
    <input type="hidden" name="finallocreserv_ids" id="finallocreserv_ids">
    </form>