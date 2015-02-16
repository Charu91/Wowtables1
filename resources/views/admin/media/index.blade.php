@extends('templates.admin_layout')

@section('content')
	<header class="page-header">
		<h2>Media</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="index.html">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Media Gallery</span></li>
			</ol>
		</div>
	</header>
	<!-- start: page -->
	<section class="content-with-menu content-with-menu-has-toolbar media-gallery">
		<div class="content-with-menu-container">
		    <div class="inner-menu-toggle">
				<a href="#" class="inner-menu-expand" data-open="inner-menu">
					Show Bar <i class="fa fa-chevron-right"></i>
				</a>
			</div>
			
			<menu id="content-menu" class="inner-menu dropzone-block" role="menu">
				<div class="nano">
					<div class="nano-content">
						<div class="inner-menu-toggle-inside">
							<a href="#" class="inner-menu-collapse">
								<i class="fa fa-chevron-up visible-xs-inline"></i><i class="fa fa-chevron-left hidden-xs-inline"></i> Hide Bar
							</a>
							<a href="#" class="inner-menu-expand" data-open="inner-menu">
								Show Bar <i class="fa fa-chevron-down"></i>
							</a>
						</div>

                        <div class="panel-body">
                            <div class="media-dropzone dz-square" id="mediaDropZone"></div>
                        </div>
					</div>
				</div>
			</menu>
			<div class="inner-body mg-main">
			   @if ($mediaCount <= 0)
                <em>No Media Available Yet!! Start uploading now!!</em>
			   @else
				<div class="inner-toolbar clearfix">
					<ul>
						<li>
							<a href="#" id="mgSelectAll"><i class="fa fa-check-square"></i> <span data-all-text="Select All" data-none-text="Select None">Select All</span></a>
						</li>
						<li>
							<a href="#"><i class="fa fa-trash-o"></i> Delete</a>
						</li>

						<li class="right" data-sort-source data-sort-id="media-gallery">
							<ul class="nav nav-pills nav-pills-primary">
								<li>
									<label>Filter:</label>
								</li>
								<li>
									<div class="form-group">
									    <input type="hidden" class="populate" id="mediaFilterSelect" />
									</div>
								</li>
							</ul>
						</li>

					</ul>
				</div>
				<div class="row mg-files" data-sort-destination data-sort-id="media-gallery">
				    @if( !empty($images) )
                        @foreach($images as $image)
                        <div class="isotope-item document col-sm-6 col-md-4 col-lg-3">
                            <div class="thumbnail">
                                <div class="thumb-preview">
                                    <a class="thumb-image" href="{{ $s3_url.$image->file }}">
                                        <img src="{{ $s3_url.$image->resized_file }}" class="img-responsive" alt="{{ $image->alt }}" title="{{ $image->title }}">
                                    </a>
                                    <div class="mg-thumb-options">
                                        <div class="mg-zoom"><i class="fa fa-search"></i></div>
                                        <div class="mg-toolbar">
                                            <div class="mg-option checkbox-custom checkbox-inline">
                                                <input name="media[]" type="checkbox" id="media_{{$image->media_id}}" value="{{$image->media_id}}">
                                                <label for="media_{{$image->media_id}}">SELECT</label>
                                            </div>
                                            <div class="mg-group pull-right">
                                                <button class="dropdown-toggle mg-toggle" type="button" data-toggle="dropdown">
                                                    <i class="fa fa-caret-up"></i>
                                                </button>
                                                <ul class="dropdown-menu mg-menu" role="menu">
                                                    <li>
                                                        <a href="javascript:void(0);" class="edit-media-link" data-media_id="{{ $image->media_id }}">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="delete-media-link" data-media_id="{{ $image->media_id }}">
                                                            <i class="fa fa-trash-o"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="mg-title text-semibold">{{ str_limit($image->name, 15) }}</h6>
                                <div class="mg-description">
                                    <small class="pull-right text-muted">{{$image->created_date}}</small>
                                </div>
                            </div>
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
                            <a href="{{ ($pagenum == 1)? 'javascript:void(0);': '/admin/media?'.http_build_query([ 'pagenum' => $pagenum - 1 ]) }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                            @for($i = 1; $i <= $pages; $i++)
                                <li class="{{ ($pagenum == $i)? 'active': '' }}">
                                    <a href="{{ ($pagenum == $i)? 'javascript:void(0);': '/admin/media?'. http_build_query([ 'pagenum' => $i ]) }}">
                                        {{$i}}
                                    </a>
                                </li>
                            @endfor
                        <li class="{{ ($pagenum == $pages)? 'disabled': '' }}">
                            <a href="/admin/media?{{ http_build_query([ 'pagenum' => $pagenum + 1 ]) }}" aria-label="Next">
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
			</div>
		</div>
	</section>
	<!-- end: page -->
@stop   
@section('sidebar-right')
	<div class="nano">
		<div class="nano-content">
		    <div class="clearfix hidden-xs">
                <a href="javascript:void(0);" class="pull-right" id="sidebarRightClose">
                    <i class="fa fa-times-circle"></i>
                </a>
            </div>
			<a href="#" class="mobile-close visible-xs">
				Collapse <i class="fa fa-chevron-right"></i>
			</a>
			<div class="sidebar-right-wrapper">

			</div>
		</div>
	</div>
@stop