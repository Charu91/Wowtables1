(function($){

    'use strict';

    $(document).on('ready', function(){

        var locationNameInput = $('#locationName')
          , locationSlugInput = $('#locationSlug')
          , locationTypeSelect = $('#locationType')
          , locationParentSelect = $('#locationParent')
          , newLocationForm = $('#newLocationForm')
          , token = $("meta[name='_token']").attr('content');

        var oTable = $('#locationsTable').DataTable({
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

        locationParentSelect.multiselect({
            disableIfEmpty: true,
            enableFiltering: true,
            filterBehavior: 'text'
        });

        locationNameInput.on('blur', function(){
            var $this = $(this);
            if($.trim($this.val()) !== ''){
                $.ajax({
                    url: '/admin/locations/slug',
                    method: 'GET',
                    data: {
                        location_name: $this.val()
                    }
                }).done(function(data){
                    locationSlugInput.val(data.slug);
                }).fail(function(jqXHR){
                    console.log(jqXHR);
                });
            }
        });

        locationTypeSelect.on('change', function(){
            var $this = $(this);
            if($.trim($this.val()) !== '' && $.trim($this.val()) !== 'Country'){
                $.ajax({
                    url: '/admin/locations/selectParents',
                    method: 'GET',
                    data: {
                        location_type: $this.val()
                    }
                }).done(function(data){
                    if($.trim(data) === ''){
                        locationParentSelect.html('');
                        locationParentSelect.prop('disabled',true);
                        locationParentSelect.multiselect('rebuild');
                        locationParentSelect.multiselect('disable');
                        locationParentSelect.rules('remove','required');
                    }else{
                        locationParentSelect.html(data);
                        locationParentSelect.prop('disabled',false);
                        locationParentSelect.multiselect('rebuild');
                        locationParentSelect.multiselect('enable');
                        locationParentSelect.rules('add','required');
                    }
                }).fail(function(jqXHR){
                    console.log(jqXHR);
                    locationParentSelect.html('');
                    locationParentSelect.prop('disabled',true);
                    locationParentSelect.multiselect('rebuild');
                    locationParentSelect.multiselect('disable');
                    locationParentSelect.rules('remove','required');
                })
            }else{
                locationParentSelect.html('');
                locationParentSelect.prop('disabled',true);
                locationParentSelect.multiselect('rebuild');
                locationParentSelect.multiselect('disable');
                locationParentSelect.rules('remove','required');
            }
        });

        newLocationForm.validate({
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

        newLocationForm.on('submit', function(e){
            var isvalidate = newLocationForm.valid();

            if(isvalidate)
            {
                e.preventDefault();
            }
        });

        $('#saveLocation').on('click', function(){

            var $this = $(this)
            //  , $btn = $this.button('loading');

            $.ajax({
                url: '/admin/locations',
                method: 'POST',
                data: {
                    location_name: locationNameInput.val(),
                    slug: locationSlugInput.val(),
                    location_type: locationTypeSelect.val(),
                    location_parent_id: (locationParentSelect.val() !== undefined)? locationParentSelect.val():null
                },
                headers: {
                    'X-XSRF-TOKEN': token
                }
            }).done(function(data){
                if(data.status === 'success'){
                    oTable.ajax.reload();
                    resetLocationForm()
                }else{
                    alert('There was a problem saving the location')
                }
                //$btn.button('reset');
            }).fail(function(jqXHR){
                console.log(jqXHR);
                //$btn.button('reset');
            });
        });

          $('#updateLocation').on('click', function(){

            var $this = $(this)
            //  , $btn = $this.button('loading');
            var location_id = $('#location_id').val();
            $.ajax({
                url: '/admin/locationsupdate',
                method: 'POST',
                data: {
                    location_name: locationNameInput.val(),
                    location_id:location_id,
                    slug: locationSlugInput.val(),
                    location_type: locationTypeSelect.val(),
                    location_parent_id: (locationParentSelect.val() !== undefined)? locationParentSelect.val():null
                },
                headers: {
                    'X-XSRF-TOKEN': token
                }
            }).done(function(data){
                if(data.status === 'success'){
                    oTable.ajax.reload();
                    //resetLocationForm()
                    window.location.href='/admin/settings/locations/';
                }else{
                    alert('There was a problem saving the location')
                }
                //$btn.button('reset');
            }).fail(function(jqXHR){
                console.log(jqXHR);
                //$btn.button('reset');
            });
        });

        $('#resetLocationForm').on('click', function(){
            resetLocationForm();
        });


        function resetLocationForm(){
            locationNameInput.val('');
            locationSlugInput.val('');
            locationTypeSelect.val('');
            locationParentSelect.html('');
            locationParentSelect.prop('disabled',true);
            locationParentSelect.multiselect('rebuild');
            locationParentSelect.multiselect('disable');
        }
    });
})(jQuery);

function removeLocation(id)
{
 if(confirm('Are you sure you want to delete location with id '+id+'?'))
            {
                 window.location.href='/admin/settings/locations/remove/'+id;
            }
}
