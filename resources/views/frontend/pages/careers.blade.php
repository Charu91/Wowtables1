@extends('frontend.templates.details_pages')

@section('content')

<div class="container cms-page">
    <div class="row">
        <div class="col-md-12 entry-content">
              <img class="img-responsive" src="/assets/img/careers_header.jpg">
              <div class="row">
      					<div class="col-md-12 col-sm-8" style="margin-top:10px;">
      						<p>WowTables is a funded start-up that partners with the best restaurants in India to bring you unique and exclusive culinary experiences.</p>

      						<p>Our work atmosphere is both fun and exciting with new opportunities and fresh horizons every week. We work with the best restaurants, food celebrities and luxury brands to create unique, exclusive and highly sought-after experiences and events and brainstorm new ways to market them to our audience consisting of HNIs, expats, bloggers and other distinguished members.</p>

      						<p>We're India's first digital platform serving the premium dining segment. We are a technology-driven company and believe in using cutting edge software to maximise the efficiency of our systems.</p>
      					</div>
      				  </div>
      				 <div class="row">
      					<div class="col-md-4 col-sm-8"><p class="lead intro-tag">Current Openings</p></div>
      				  </div>
                <div class="row">
                    <table class="table table-striped">
                        <thead>
                            <th>
                                Job Title
                            </th>
                            <th>Location</th>
                        </thead>
                        <tbody>
                            @foreach($careers as $career)
                                <tr onclick="window.open('/pages/careers/apply/{!! $career->id !!}')">
                                    <td>{!! $career->job_title !!}</td>
                                    <td>{!! $career->location !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
  </div>

@endsection
