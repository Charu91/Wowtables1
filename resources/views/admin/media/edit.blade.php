<div class="panel panel-transparent">
    <img style="max-height: 140px" class="img-thumbnail img-responsive"  src="{!! $s3_url.$media['resized_file'] !!}"/>
</div>
<div id="imgEdit" class="panel-body">
     <form action="javascript:void(0)" method="POST">
         <div class="row">
             <div class="col-sm-12">
                 <div class="form-group">
                     <label class="control-label">Name</label>
                     <input type="text" name="name" id="mediaName" class="form-control" value="{{ $media['name'] }}">
                 </div>
             </div>
             <div class="col-sm-12">
                 <div class="form-group">
                     <label class="control-label">Title</label>
                     <input type="text" name="title" id="mediaTitle" class="form-control" value="{{ !empty($media['title'])? $media['title']:'' }}">
                 </div>
             </div>
             <div class="col-sm-12">
                 <div class="form-group">
                     <label class="control-label">Alt</label>
                     <input type="text" name="alt" id="mediaAlt" class="form-control" value="{{ !empty($media['alt'])? $media['alt']:'' }}">
                 </div>
             </div>
             <div class="col-sm-12">
                  <div class="form-group">
                      <label class="control-label">Tags</label>
                      <select name="media_tags" id="mediaTagsSelect" multiple="" data-role="tagsinput" data-tag-class="label label-primary">
                          @if(!empty($media['tags']))
                            @foreach($media['tags'] as $tag)
                                <option value="{{$tag}}">{{$tag}}</option>
                            @endforeach
                          @endif
                      </select>
                  </div>
             </div>
             <div class="col-sm-12">
                  <button type="submit" id="updateMediaBtn" data-media_id="{{$media['id']}}" data-loading-text="Updating..." class="btn btn-primary" autocomplete="off">
                    Update
                  </button>
              </div>
         </div>
     </form>
 </div>