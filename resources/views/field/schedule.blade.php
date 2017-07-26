@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="">
      <div class="panel panel-default">
        <div class="panel-heading">Schedule</div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('HomeController@home')}}">
              Back
            </a>
          </div>
          <div class="col-sm-12 text-center">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th></th>
                  <th id="first-header"></th>
                  <th id="second-header"></th>
                  <th id="third-header"></th>
                  <th id="fourth-header"></th>
                  <th id="fifth-header"></th>
                  <th id="sixth-header"></th>
                  <th id="seventh-header"></th>
                </tr>
              </thead>
              <tbody id="schedule-list">

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){
  // let current = moment();
  var app = window.meetingApp;
  meetingApp.schedule();
});
</script>
@endsection
