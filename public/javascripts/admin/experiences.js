(function($){

    'use strict';

    $(document).on('ready', function(){

        $('#simpleExperienceDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1d',
            todayHighlight: true,
            autoclose: true
        });

        $('.addDatepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1d',
            todayHighlight: true,
            autoclose: true
        });

        $('#simpleExperienceTimePicker').timepicker({
            showSeconds: true,
            showMeridian: false
        });

        $("#experienceIncludes").redactor({
            minHeight: 300
        });

        $("#experienceInfo").redactor({
            minHeight: 300
        });

        var   token = $("meta[name='_token']").attr('content')
            , $addNewExperienceAddonBtn = $('#addNewExperienceAddonBtn')
            , $experienceAddonForm = $('#experienceAddonForm')
            , $experienceAddonHolder = $('#experienceAddonHolder')
            , $experienceAddonsTable = $('#experienceAddonsTable')
            , $experienceAddons = []
            , $experienceAddonBtn = $("#addExperienceAddonBtn")
            , $updateExperienceAddonBtn = $("#updateExperienceAddonBtn")
            , $cancelUpdateExperienceAddonBtn = $("#cancelUpdateExperienceAddonBtn")
            , $addonTitle = $('#addonTitle')
            , $addonPriceBeforeTax = $('#addonPrice')
            , $addonPriceAfterTax = $('#addonPriceAfterTax')
            , $addonTax = $('#addon_tax')
            , $addonCommissionPerCover = $('#addon_commission_per_cover')
            , $addonCommissionOn = $('#addonCommissionOn')
            , $addonShortDescription = $('#addonShortDescription')
            , $addonsMenu = $('#addonsMenu')




        $('#experiencesTable').DataTable();

        $addNewExperienceAddonBtn.on('click', function () {
            $experienceAddonForm.show();
            $(this).text($(this).text() == 'Add New Addon' ? 'Cancel Addon' : 'Add New Addon');
            $(this).hide();
            $cancelUpdateExperienceAddonBtn.show();
        });

        $experienceAddonForm.hide();
        $experienceAddonHolder.hide();
        $cancelUpdateExperienceAddonBtn.hide();

        $experienceAddonBtn.on('click',function() {

            if ( $addonTitle.val() != '' || $addonPriceBeforeTax.val() != '' || $addonPriceAfterTax.val() != '' || $addonShortDescription.val() != '' )
            {
                $experienceAddons.push({
                    addonTitle: $addonTitle.val(),
                    addonPriceBeforeTax: $addonPriceBeforeTax.val(),
                    addonPriceAfterTax: $addonPriceAfterTax.val(),
                    addonTax: $addonTax.val(),
                    addonCommissionPerCover: $addonCommissionPerCover.val(),
                    addonCommissionOn: $addonCommissionOn.val(),
                    addonsMenu: $addonsMenu.val(),
                    addonDescription: $addonShortDescription.val()
                });

                emptyAddonForm();
                $experienceAddonForm.hide();
                $addNewExperienceAddonBtn.text('Add New Addon');
                $addNewExperienceAddonBtn.show();
                showHideExperienceAddonHolder();

                var tablebody;
                $.each($experienceAddons, function (key, value) {
                    key += 1;
                    tablebody +=
                        '<tr id="'+key+'">' +
                        '<td>' + key + '</td>' +
                        '<td id="addonTitle_'+key+'">' + value.addonTitle + ' </td>' +
                        '<td id="addonPriceBeforeTax_'+key+'">' + value.addonPriceBeforeTax + '</td>' +
                        '<td id="addonPriceAfterTax_'+key+'">' + value.addonPriceAfterTax + '</td>' +
                        '<td id="addonTax_'+key+'">' + value.addonDescription + '</td>' +
                        '<td>' +
                        '<a class="edit-addon-btn" data-addon-row-id="' + key + '" href="javascript:void(0);">' +
                        '<i class="fa fa-edit"></i></a>&nbsp;|&nbsp;' +
                        '<a class="delete-addon-btn" data-addon-row-id="' + key + '" href="javascript:void(0);">' +
                        '<i class="fa fa-trash-o"></i></a>' +
                        '</td>' +
                        '<input type="hidden" name="addons['+key+'][name]" value="'+value.addonTitle+'" />'+
                        '<input type="hidden" name="addons['+key+'][price]" value="'+value.addonPriceBeforeTax+'" />'+
                        '<input type="hidden" name="addons['+key+'][tax]" value="'+value.addonTax+'" />'+
                        '<input type="hidden" name="addons['+key+'][post_tax_price]" value="'+value.addonPriceAfterTax+'" />'+
                        '<input type="hidden" name="addons['+key+'][commission_per_cover]" value="'+value.addonCommissionPerCover+'" />'+
                        '<input type="hidden" name="addons['+key+'][commission_on]" value="'+value.addonCommissionOn+'" />'+
                        '<input type="hidden" name="addons['+key+'][short_description]" value="'+value.addonDescription+'" />'+
                        '<input type="hidden" name="addons['+key+'][addonsMenu]" value="'+value.addonsMenu+'" />'+
                        '</tr>'
                        /*'<input id="addonInput_'+key+'" type="hidden" name="addons[]" value="'+
                            value.addonTitle +','+
                            value.addonPriceBeforeTax+','+
                            value.addonPriceAfterTax+','+
                            value.addonPriceBeforeTax+','+
                            value.addonInfo+
                        '">'*/
                });

                $("#experienceAddonsTable tbody").html(tablebody);
            }
        });

        $('body').delegate('.delete-addon-btn','click',function () {
            var id = $(this).data('addon-row-id');
            $('table#experienceAddonsTable tr#'+id).remove();
            $('#addonInput_'+id).remove();
            id = id - 1;
            $experienceAddons.splice(id,1);
            showHideExperienceAddonHolder();
        });

        $('body').delegate('.edit-addon-btn','click',function () {
            var id = $(this).data('addon-row-id');
            $addonTitle.val( $('#addonTitle_'+id).text() );
            $addonPriceBeforeTax.val( $('#addonPriceBeforeTax_'+id).text() );
            $addonPriceAfterTax.val( $('#addonPriceAfterTax_'+id).text() );
            $addonShortDescription.val( $('#addonTax_'+id).text() );
            $experienceAddonBtn.text('Update');
            $cancelUpdateExperienceAddonBtn.show();
            $experienceAddonForm.show();
            $addNewExperienceAddonBtn.hide();
        });

        function showHideExperienceAddonHolder () {
            if ( $experienceAddons.length == 0 ) {
                $experienceAddonHolder.hide();
            }
            if ( $experienceAddons.length > 0 ) {
                $experienceAddonHolder.show();
            }
        }

        function emptyAddonForm () {
            $addonTitle.val('');
            $addonPriceBeforeTax.val('');
            $addonPriceAfterTax.val('');
            $addonShortDescription.val('');
            //$addonInfo.val('');
        }

        $('body').delegate('#cancelUpdateExperienceAddonBtn','click',function () {
            emptyAddonForm();
            $addNewExperienceAddonBtn.text('Add New Addon');
            $experienceAddonBtn.text('Add Addon');
            $cancelUpdateExperienceAddonBtn.hide();
            $experienceAddonForm.hide();
            $addNewExperienceAddonBtn.show();
        });

        $('#experienceMenuHolder').hide();
        $('#addonsMenuHolder').hide();

        $('#insertExperienceMenu').on('click',function(){
            var menu = $('#expMenuText').val();
            var htmlmenu = $('.container').html();
            //console.log("menu == "+menu+"<br/> htmlmenu = "+htmlmenu); return false;
            $('#markdownmodal').modal('hide');
            $('#experienceMenuHolder').show();
            $('#expMenu').val(htmlmenu);
            $('#expMarkdownMenu').val(menu);
            $('#expMenuBtn').hide();
        });

        $('#insertAddonsMenu').on('click',function(){
            var menu = $('#addonsMenuText').val();
            $('#addonsMarkdownModal').modal('hide');
            $('#addonsMenuHolder').show();
            $('#addonsMenu').val(menu);
            $('#expMarkdownMenu').val(menu);
            $('#addOnsMenuBtn').hide();
        });

        var oldMarkdownSyntax = $('#oldMarkdownSyntax').val();

        if(oldMarkdownSyntax != ""){
            var getMarkdownhtml = markdown.toHTML( oldMarkdownSyntax );

            $("#oldExpMenu").val(getMarkdownhtml);
        }


        $("#loc_exp").change(function(){
           var curr_val = $(this).val();
            /*$.ajax({
                method: 'POST',
                url: 'getVendorLocationsDetails',
                type:'json',
                data:{vendor_id:curr_val},
                success: function(response){
                    console.log('response == '+response);
                }
            }).fail(function (jqXHR) {
                console.log(jqXHR);
            });*/

            /* $.ajax({
                url: 'getVendorLocationsDetails',
                method: 'GET',
                data: {
                    vendor_id: curr_val
                }
            }).done(function(data){
                locationSlugInput.val(data.slug);
            }).fail(function(jqXHR){
                console.log(jqXHR);
            });
            /*
            .done(function (editHTML) {
             $editFlagHolder.html(editHTML);
             $editFlagHolder.show();
             })
             */
        });



        /*$('#addNewExperienceForm').submit(function(e){
            e.preventDefault();
            var input = {};
            $('#addNewExperienceForm :input').each(function() {
                input[this.name] = $(this).val();
            });

            $.ajax({
                url: '/admin/experiences',
                method: 'Post',
                data: {
                    data: input
                }
            }).done(function(data){
                console.log(data);
            }).fail(function(jqXHR){
                console.log(jqXHR);
            });
        });

        $( "#addNewExperienceForm" ).validate({
            rules: {
                min_people_per_reservation:{number: true},
                max_reservation_per_day:{number: true},
                max_people_per_reservation:{number: true},
                min_reservation_time_buffer:{number: true},
                max_reservation_per_time_slot:{number: true},
                max_reservation_time_buffer:{number: true},
                commision_per_reservation:{number: true},
                reward_points_per_reservation:{number: true},
                tax:{number: true},
                price_before_tax:{number: true},
                price_after_tax:{number: true},
            },
            ignore: [],
            highlight: function( label ) {
                $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function( label ) {
                $(label).closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function( error, element ) {
                var placement = element.closest('.input-group');
                if (!placement.get(0)) {
                    placement = element;
                }
                if (error.text() !== '') {
                    placement.after(error);
                }
            }
        });*/

    });
})(jQuery);