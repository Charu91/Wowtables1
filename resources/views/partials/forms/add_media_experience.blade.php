<div class="mt-lg mb-lg ml-lg form-group">
    <div class="col-md-3">
        <span class="required">*</span>
        <button data-media-select="1" data-gallery-position="1"  data-media-type="listing_image" type="button" class="btn btn-success media-modal-btn media-modal-btn-listing" ><span class="fa fa-plus"></span>&nbsp;Listing Image (1)</button>
    </div>
    <div data-gallery-position="1" class="popup-gallery">
        @if( Input::old('media.listing_image') )
            {!! Form::hidden('media[listing_image]',null) !!}
        @else
            <input name="media[listing_image]" type="hidden" required>
        @endif
    </div>
</div>
<div class="mt-lg mb-lg ml-lg form-group">
    <div class="col-md-3">
        <span class="required">*</span>
        <button data-media-select="5" data-gallery-position="2" data-media-type="gallery_image" type="button" class="btn btn-warning media-modal-btn media-modal-btn-gallery" ><span class="fa fa-plus"></span>&nbsp;Gallery Images (5)</button>
    </div>
    <div data-gallery-position="2"  class="popup-gallery">
        @if( Input::old('media.gallery_images') )
            @foreach ( Input::old('media.gallery_images') as $key => $media )
                <div class="gallery-images">

                    {!! Form::hidden('media[gallery_images]['.$key.']',null) !!}
                </div>
            @endforeach
        @else
            <input name="media[gallery_images][]" type="hidden" required>
        @endif
    </div>
</div>

<div class="mt-lg mb-lg ml-lg form-group">
    <div class="col-md-3">
        <span class="required">*</span>
        <button data-media-select="1" data-gallery-position="3" data-media-type="mobile_listing_image" type="button" class="btn btn-success media-modal-btn media-modal-btn-mobile-listing-experience" ><span class="fa fa-plus"></span>&nbsp;Mobile Listing Images (1)</button>
    </div>
    <div data-gallery-position="3" class="popup-gallery">
        @if( Input::old('media.mobile_listing_image') )
            {!! Form::hidden('media[mobile]',null) !!}
        @else
            <input name="media[mobile]" type="hidden" required>
        @endif
    </div>
</div>