@if ( $media_type == 'main_image' )
    @foreach ( $media as $asset )
        {!! Form::hidden('main_image',$asset['id']) !!}
        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
    @endforeach
@elseif( $media_type == 'listing_image' )
    @foreach ( $media as $asset )
        {!! Form::hidden('media[listing_image]',$asset['id']) !!}
        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
    @endforeach
@elseif( $media_type == 'mobile_listing_image' )
    @foreach ( $media as $asset )
        {!! Form::hidden('media[mobile]',$asset['id']) !!}
        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
    @endforeach
@elseif( $media_type == 'web_images' )
    @foreach ( $media as $asset )
        {!! Form::hidden('media[web_collection]',$asset['id']) !!}
        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
    @endforeach
@elseif( $media_type == 'sidebar-image' )
    @foreach ( $media as $asset )
        {!! Form::hidden('sidebar_media_id',$asset['id']) !!}
        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
    @endforeach
@elseif( $media_type == 'gallery_image' )
    <section class="panel col-lg-8">
        <div class="panel-body">
        <h4 class="text-primary text-center">Drag the images to sort</h4>
            <ul class="list-inline" id="sortable">
                @foreach ( $media as $asset )
                     <li style="cursor:move;">
                        {!! Form::hidden('media[gallery_images][]',$asset['id']) !!}
                        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
                     </li>
                    <!--<div class="gallery-images">
                        {!! Form::hidden('media[gallery_images][]',$asset['id']) !!}
                        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
                    </div>-->
                @endforeach
            </ul>
        </div>
    </section>

@elseif ( $media_type == 'single-media-image' )
    @foreach ( $media as $asset )
        {!! Form::hidden('media_id',$asset['id']) !!}
        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
    @endforeach
@endif
<script type="text/javascript">
  $(function() {
     $( "#sortable" ).sortable();
     $( "#sortable" ).disableSelection();
 });
</script>

