@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">Field Management</div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('FieldController@home')}}">
              Back
            </a>
          </div>
          <div class="col-sm-12" style="padding-top: 20px;">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th></th>
                  <th>Date</th>
                  <th>Schedule</th>
                  <!-- <th>Other</th> -->
                </tr>
              </thead>
              <tbody id="schedule-list">
                @foreach( $schedules as $schedule)
                <tr>
                  <td>{{$schedule->id}}</td>
                  <td>{{$schedule->date}}</td>
                  <td>{{$schedule->schedule}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">

  </div>
</div>
@endsection
