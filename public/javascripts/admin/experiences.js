(function($){

    'use strict';

    $(document).on('ready', function(){

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
            , $addonPriceBeforeTax = $('#addonPriceBeforeTax')
            , $addonPriceAfterTax = $('#addonPriceAfterTax')
            , $addonTax = $('#addonTax')
            , $addonInfo = $('#addonInfo');

        $('#experiencesTable').DataTable({
            lengthChange: false,
            processing: true,
            serverSide: true,
            ajax: '/admin/locations',
            columns: [
                {
                    name: 'location',
                    sortable: true
                },
                {
                    name: 'slug',
                    sortable: false
                },
                {
                    name: 'location_type',
                    sortable: true
                },
                {
                    name: 'parent',
                    sortable: false
                },
                {
                    name: 'actions',
                    sortable: false
                }
            ]
        });

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

            if ( $addonTitle.val() != '' || $addonPriceBeforeTax.val() != '' || $addonPriceAfterTax.val() != '' || $addonTax.val() != '' || $addonInfo.val() != '' )
            {
                $experienceAddons.push({
                    addonTitle: $addonTitle.val(),
                    addonPriceBeforeTax: $addonPriceBeforeTax.val(),
                    addonPriceAfterTax: $addonPriceAfterTax.val(),
                    addonTax: $addonTax.val(),
                    addonInfo: $addonInfo.val()
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
                        '<td id="addonTitle_'+key+'">' + value.addonTitle + '</td>' +
                        '<td id="addonPriceBeforeTax_'+key+'">' + value.addonPriceBeforeTax + '</td>' +
                        '<td id="addonPriceAfterTax_'+key+'">' + value.addonPriceAfterTax + '</td>' +
                        '<td id="addonTax_'+key+'">' + value.addonTax + '</td>' +
                        '<td id="addonInfo_'+key+'">' + value.addonInfo + '</td>' +
                        '<td>' +
                        '<a class="edit-addon-btn" data-addon-row-id="' + key + '" href="javascript:void(0);">' +
                        '<i class="fa fa-edit"></i></a>&nbsp;|&nbsp;' +
                        '<a class="delete-addon-btn" data-addon-row-id="' + key + '" href="javascript:void(0);">' +
                        '<i class="fa fa-trash-o"></i></a>' +
                        '</td>' +
                        '</tr>' +
                        '<input id="addonInput_'+key+'" type="hidden" name="addons[]" value="'+
                            value.addonTitle +','+
                            value.addonPriceBeforeTax+','+
                            value.addonPriceAfterTax+','+
                            value.addonPriceBeforeTax+','+
                            value.addonInfo+
                        '">'
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
            $addonTax.val( $('#addonTax_'+id).text() );
            $addonInfo.val( $('#addonInfo_'+id).text() );
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
            $addonTax.val('');
            $addonInfo.val('');
        }

        $('body').delegate('#cancelUpdateExperienceAddonBtn','click',function () {
            emptyAddonForm();
            $addNewExperienceAddonBtn.text('Add New Addon');
            $experienceAddonBtn.text('Add Addon');
            $cancelUpdateExperienceAddonBtn.hide();
            $experienceAddonForm.hide();
            $addNewExperienceAddonBtn.show();
        });

        $('#addNewExperienceForm').submit(function(e){
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
        });

    });
})(jQuery);