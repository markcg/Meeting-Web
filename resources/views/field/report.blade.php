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
                  <th>Statistic</th>
                  <th>Status</th>
                  <!-- <th>Other</th> -->
                </tr>
              </thead>
              <tbody id="schedule-list">
                @foreach( $schedules as $schedule)
                <tr>
                  <td>{{$schedule->id}}</td>
                  <td>{{$schedule->date}}</td>
                  <td>{{$schedule->schedule}}</td>
                  <?php $user = Customer::find($schedule->customer_id);  ?>
                  <td>{{is_null($user) ? 0 : $user->attendance()}} %</td>
                    @if($schedule->status == 0)
                    <td>
                      <form action="{{action('FieldController@handle_report_schedule_confirm')}}">
                        <input type="hidden" name="id" value="<?php echo $schedule->id; ?>" />
                        <button class="btn btn-success">Accept</button>
                      </form>
                    </td>
                    <td>
                      <form action="{{action('FieldController@handle_report_schedule_cancel')}}">
                        <input type="hidden" name="id" value="<?php echo $schedule->id; ?>" />
                        <button class="btn btn-danger">Reject</button>
                      </form>
                    </td>
                    @elseif($schedule->status == 1)
                    <td>
                      <form action="{{action('FieldController@handle_report_schedule_show')}}">
                        <input type="hidden" name="id" value="<?php echo $schedule->id; ?>" />
                        <button class="btn btn-success">Show Up</button>
                      </form>
                    </td>
                    <td>
                      <form action="{{action('FieldController@handle_report_schedule_not_show')}}">
                        <input type="hidden" name="id" value="<?php echo $schedule->id; ?>" />
                        <button class="btn btn-danger">Not Show</button>
                      </form>
                    </td>
                    @elseif($schedule->status == 2)
                    <td>
                      <span class="label label-success">Show Up</span>
                    </td>
                    @elseif($schedule->status == -1)
                    <td>
                      <span class="label label-danger">Cancel</span>
                    </td>
                    @elseif($schedule->status == 3)
                    <td>
                      <span class="label label-danger">Not Show</span>
                    </td>
                    @endif
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
<div class="modal fade" id="stat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$('#stat').on('shown.bs.modal', function (e) {
// do something...
})
</script>
@endsection
