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
@elseif( $media_type == 'gallery_image' )
    @foreach ( $media as $asset )
        <div class="gallery-images">
            {!! Form::hidden('media[gallery_images][]',$asset['id']) !!}
            <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
        </div>
    @endforeach
@elseif ( $media_type == 'single-media-image' )
    @foreach ( $media as $asset )
        {!! Form::hidden('media_id',$asset['id']) !!}
        <img class="pull-left mt-xs mb-xs mr-xs img-thumbnail img-responsive" src="{!! $s3_url.$asset['resized_file'] !!}" width="100">
    @endforeach
@endif