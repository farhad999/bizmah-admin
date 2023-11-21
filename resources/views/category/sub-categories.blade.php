<option value="">--Select One--</option>
@foreach($categories as $key=>$category)
  <option value="{{$key}}">{{$category}}</option>
@endforeach
