@extends('frontend.templates.details_pages')

@section('content')

<div class="container cms-page">
        <div class="row">
            <div class="col-md-12 entry-content">
  	<?php //print_r($staticPage);
  		/*foreach($staticPage as $row){
  			echo $row->['page_title'];
  		}*/	//echo $staticPage['0']->page_contents;
			if((strpos($staticPage['0']->page_contents,'put_email_address') !== false) && (strpos($staticPage['0']->page_contents,'put_full_name') !== false)) {

				$email_address = ((isset(Auth::user()->email) && Auth::user()->email != "") ? Auth::user()->email : '');
				$full_name = ((isset(Auth::user()->full_name) && Auth::user()->full_name != "") ? Auth::user()->full_name : '');
				$change_email = str_ireplace('put_email_address',$email_address,$staticPage['0']->page_contents);
				$change_full_name = str_ireplace('put_full_name',$full_name,$change_email);


			} else {
				$change_full_name = $staticPage['0']->page_contents;
			}

					echo $change_full_name;
  	?>
      </div>
    </div>
  </div>

@endsection