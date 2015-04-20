@if ($mediaCount <= 0)
    <em>No Media Available Yet!! Start uploading now!!</em>
@else
    <div class="row">
        @if( !empty($images) )
            @foreach($images as $image)

                <div class="col-lg-2">
                    <section class="panel">
                        <div style="padding:6px;" class="panel-body bg-primary">
                            <p class="small">{{ str_limit($image->name, 5) }}<small class="pull-right">{{$image->created_date}}</small></p>
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
                                <img style="cursor: pointer;" alt="{{ $image->alt }}" title="{{ $image->title }}" src="{!! $s3_url.$image->resized_file !!}" class="img-thumbnail img-responsive modal-select-img" >
                            </label>
                        </div>
                    </section>
                </div>

            @endforeach
        @else
            <em>Could not find media matching the criteria that you entered</em>
        @endif
    </div>

    <nav id="mediaPaginationBlock">
        <ul class="pagination">
            @if ($pages > 1)
                <li class="{{ ($pagenum == 1)? 'disabled': '' }}">
                    <a class="media-modal-pagenum-btn" href="javascript:void(0);" data-media-pagenum="{{ ($pagenum == 1)? '0': $pagenum - 1 }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                @for($i = 1; $i <= $pages; $i++)
                    <li class="{{ ($pagenum == $i)? 'active': '' }}">
                        <a class="media-modal-pagenum-btn" href="javascript:void(0);" data-media-pagenum="{{ ($pagenum == $i)? '0': $i  }}">
                            {{$i}}
                        </a>
                    </li>
                @endfor
                <li class="{{ ($pagenum == $pages)? 'disabled': '' }}">
                    <a class="media-modal-pagenum-btn" href="javascript:void(0);" data-media-pagenum="{{ $pagenum + 1  }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @else
                <li class="disabled">
                    <a href="javascript:void(0)" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="active"><a href="javascript:void(0)">1</a></li>
                <li class="disabled">
                    <a href="javascript:void(0)" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif