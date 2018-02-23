@extends('layout')
@section('styles')
<style>
#map {
  width: 100%;
  height: 400px;
  background-color: grey;
}
</style>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading"><h1 class="text-center">Register</h1></div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('FieldController@login')}}">
              Back to Login
            </a>
          </div>
          <form method="post" action="{{action('FieldController@handle_register')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Field Name</td>
                    <td><input required type="text" novalidate class="form-control" name="name" value="{{isset($model) ? $model->name : old('name')}}" /></td>
                  </tr>
                  <tr>
                    <td>Field Description</td>
                    <td><input required type="text" novalidate class="form-control" name="description" value="{{isset($model) ? $model->description : old('description')}}" /></td>
                  </tr>
                  <tr>
                    <td>Field Email</td>
                    <td><input required type="text" novalidate class="form-control" name="email" value="{{isset($model) ? $model->email : old('email')}}" /></td>
                  </tr>
                  <tr>
                    <td>Field Address</td>
                    <td><input required type="text" novalidate class="form-control" name="address" value="{{isset($model) ? $model->address : old('address')}}" /></td>
                  </tr>
                  <tr>
                    <td>Field Phone Number</td>
                    <td><input required type="text" novalidate class="form-control" name="phone_number" value="{{isset($model) ? $model->phone_number : old('phone_number')}}" /></td>
                  </tr>
                  <tr>
                    <td>Field Username</td>
                    <td><input required type="text" novalidate class="form-control" name="username" value="{{isset($model) ? $model->username : old('username')}}" /></td>
                  </tr>
                  <tr>
                    <td>Field Password</td>
                    <td><input required type="password" class="form-control" name="password" value="{{isset($model) ? $model->password : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Field Re-Password</td>
                    <td><input required type="password" novalidate class="form-control" name="repassword" value="{{isset($model) ? $model->password : ''}}" /></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <div id="map"></div>
                    </td>
                  </tr>
                  <tr style="display: none;">
                    <td>Latitude: <input id="latitude" required type="hidden" class="form-control" name="latitude" value="{{isset($model) ? $model->latitude : ''}}" /></td>
                    <td>Longitude: <input id="longitude" required type="hidden" class="form-control" name="longitude" value="{{isset($model) ? $model->longitude : ''}}" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-12">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{isset($model) ? $model->id : ''}}" />
                <button type="submit" onclick="return confirm('Do you want to regsiter?')" class="btn btn-success btn-block">
                  Submit
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
function initMap() {
  // var uluru = {lat: 18.808028, lng: 98.979263};
  var myLatlng = new google.maps.LatLng(18.808028,98.979263);
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 14,
    center: myLatlng
  });
  var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    draggable:true,
    title:"Your Place"
  });
  map.addListener('center_changed', function(e) {
    var center = map.getCenter();
    marker.setPosition(center);
  });
  map.addListener('idle', function(e) {
    var center = map.getCenter();
    console.log(center);
    $('#latitude').val(center.lat());
    $('#longitude').val(center.lng());
  });
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCUplnEZPj0eV71ZwqQWU9x-dGmTRyi-_s&callback=initMap">
</script>
@endsection
