@extends('frontend.templates.details_pages')

@section('content')

<div class="container cms-page">
        <div class="row">
            <div class="col-md-12 entry-content">
  	<?php //print_r($staticPage);
  		/*foreach($staticPage as $row){
  			echo $row->['page_title'];
  		}*/
  		echo $staticPage['0']->page_contents;
  	?>
      </div>
    </div>
  </div>

@endsection