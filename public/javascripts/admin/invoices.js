(function($){
  'use strict';
    $(document).on('ready', function(){

      $("#vendor").click(function(){
        var vendor_id = "v"+$(this).data('vendor-id');
        var vendorReservationIds = decodeURIComponent($("#vendor_reservation_id").val());
        var vendor_reservation_id = $.parseJSON(vendorReservationIds);
        var resevation_ids = JSON.stringify(vendor_reservation_id[vendor_id]);
        var formData = {reservation_ids:resevation_ids,vendor_id:vendor_id}; //Array
        $.post("/admin/invoice/pdf",formData,function(data, status){
          var w = window.open();
          $(w.document.body).html(data);
        });
      });

    });
})(jQuery);
