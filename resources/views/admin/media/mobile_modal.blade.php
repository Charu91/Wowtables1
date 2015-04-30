<div style="z-index: 999999" class="modal fade modal-fullscreen force-fullscreen" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <button style="color: #ffffff;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="row">
                    <h4 class="modal-title col-lg-3 ">Media Gallery</h4>
                    <div class="col-lg-3 pull-right">
                        <div class="form-group">
                            <input placeholder="Filter By:" type="hidden" class="populate" style="z-index: 9999;" id="mediaFilterSelect" data-sort-source data-sort-id="media-gallery" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-2">
                        <section class="panel">
                            <div class="panel-body bg-primary">
                                {{--<div style="height: 500px;" class="media-dropzone dz-square dz-clickable" id="mediaDropZone"></div>--}}
                                <div style="height: 500px;" class="media-dropzone dz-square dz-clickable" id="mediaDropZoneMobile"></div>
                            </div>
                        </section>
                    </div>
                    <div style="overflow-y: auto;max-height: 540px;" id="mediaModalFiles" class="col-lg-7">

                        @if ($mediaCount <= 0)
                            <em>No Media Available Yet!! Start uploading now!!</em>
                        @else
                            <div class="row">
                                @if( !empty($images) )
                                    @foreach($images as $image)

                                        <div class="col-lg-2">
                                            <section class="panel">
                                                <div style="padding:6px;" class="panel-body bg-primary">
                                                    <p class="small">{{ str_limit($image->file, 5) }}<small class="pull-right">{{$image->created_at}}</small></p>
                                                    <div style="margin-top: -5px;" class="btn-group mb-xs pull-right">
                                                        <button data-toggle="dropdown" class="mt-xs btn btn-xs btn-danger dropdown-toggle" type="button"><span class="caret"></span></button>
                                                        <ul role="menu" style="min-width: 60px;" class="dropdown-menu ">
                                                            <li class="small">
                                                                <a href="javascript:void(0);" class="edit-media-link-modal" data-media_id="{{ $image->media_id }}">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li class="small">
                                                                <a href="javascript:void(0);" class="delete-media-link" data-media_id="{{ $image->media_id }}">
                                                                    <i class="fa fa-trash-o"></i> Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <input name="media[]" class="mt-xs  multiple_checkbox" type="checkbox" id="media_{{$image->media_id}}" value="{{$image->media_id}}">
                                                    <label for="media_{{$image->media_id}}">
                                                        <img style="cursor: pointer;" src="{!! $s3_url.$image->file !!}" class="img-thumbnail img-responsive modal-select-img" >
                                                    </label>
                                                </div>
                                            </section>
                                        </div>

                                    @endforeach
                                @else
                                    <em>Could not find media matching the criteria that you entered</em>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div  id="mediaEditContentHolder" class="col-lg-3 hide">
                        <section class="panel">
                            <div style="max-height: 500px;" class="panel-body bg-primary">
                                <button id="closeMediaContentHolder" class="btn btn-sm btn-danger pull-right" type="button">Close</button>
                                <div style="color: #000;" id="mediaEditContent"></div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectMediaBtn">Select Media</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{!! Html::script( "javascripts/admin/mediagallery.js") !!}
{!! Html::script( "javascripts/admin/media.js") !!}


<script type="text/javascript">
    /*var myDropzone = new Dropzone("#mediaDropZone", {
     init: function () {
     this.on('success', function (file, response) {
     myDropzone.removeFile(file);
     $('#mediaModal').modal('hide');
     refreshMediaModal();
     });

     this.on('error', function (file, error) {
     myDropzone.removeFile(file);
     });
     },
     url: "/admin/media/store/",
     method: "POST",
     maxFilesize: 10,
     paramName: 'media',
     headers: {
     'X-XSRF-TOKEN': $("meta[name='_token']").attr('content')
     }
     });*/

    var mediaDropZoneMobile = new Dropzone("#mediaDropZoneMobile", {
        init: function () {
            this.on('success', function (file, response) {
                mediaDropZoneMobile.removeFile(file);
                $('#mediaModal').modal('hide');
                refreshMediaModal();
            });

            this.on('error', function (file, error) {
                mediaDropZoneMobile.removeFile(file);
            });
        },
        url: "/admin/media/mobile_media",
        method: "POST",
        maxFilesize: 10,
        paramName: 'media',
        headers: {
            'X-XSRF-TOKEN': $("meta[name='_token']").attr('content')
        }
    });

    /*function refreshMediaModal()
     {
     var   mediaSelect = $('#selectMediaBtn').data('media-select')
     , galleryPosition = $('#selectMediaBtn').data('gallery-position')
     , mediaType = $('#selectMediaBtn').data('media-type');

     $.ajax({
     method: 'GET',
     url: '/admin/media/modal'
     }).done( function(result) {
     $( "#mediaModalHolder" ).html( result );
     $('#mediaModal').modal('show');
     $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
     $('#selectMediaBtn').attr('data-media-select',mediaSelect);
     $('#selectMediaBtn').attr('data-media-type',mediaType);
     $('#mediaModal').checkboxes('max', mediaSelect);
     $("#selectMediaBtn").attr('disabled','disabled');
     }).fail(function (jqXHR) {
     console.log(jqXHR);
     });
     }*/

    function refreshMediaModal()
    {
        var   mediaSelect = $('#selectMediaBtn').data('media-select')
                , galleryPosition = $('#selectMediaBtn').data('gallery-position')
                , mediaType = $('#selectMediaBtn').data('media-type');

        $.ajax({
            method: 'GET',
            url: '/admin/media/mobile_modal'
        }).done( function(result) {
            $( "#mediaModalHolder" ).html( result );
            $('#mediaModal').modal('show');
            $('#selectMediaBtn').attr('data-gallery-position',galleryPosition);
            $('#selectMediaBtn').attr('data-media-select',mediaSelect);
            $('#selectMediaBtn').attr('data-media-type',mediaType);
            $('#mediaModal').checkboxes('max', mediaSelect);
            $("#selectMediaBtn").attr('disabled','disabled');
        }).fail(function (jqXHR) {
            console.log(jqXHR);
        });
    }

</script>