<option value="">Select</option>
@foreach($locations as $location_id => $location)
    <option value="{{ $location_id }}">{{$location}}</option>
@endforeach