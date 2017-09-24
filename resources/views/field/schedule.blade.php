@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-8">Field Management: Schedule</div>
            <div class="col-xs-4 text-right"><a href="{{action('FieldController@logout')}}">Logout</a></div>
          </div>
        </div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('FieldController@home')}}">
              Back
            </a>
          </div>
          <div class="col-sm-12 text-center">
            <form method="get" action="">
              <input type="date" name="search" />
              <input type="submit" value="Go" />
            </form>
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
  var data = JSON.parse('<?php echo isset($schedules) ? json_encode($schedules) : '' ?>');
  var field_id = <?php echo session('field')->id?>;
  meetingApp.field_id = field_id;
  meetingApp.schedules = data;
  meetingApp.schedule('<?php print(isset($_GET['search']) ? $_GET['search'] : '') ?>');
});
</script>
@endsection
