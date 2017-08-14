@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">Field Management</div>
        <div class="panel-body">
          <div class="col-sm-6 col-sm-offset-3">
            <a href="{{action('FieldController@promotions')}}">
              <button class="btn btn-info btn-block">
                Promotions
              </button>
            </a>
          </div>
          <div class="col-sm-6 col-sm-offset-3" style="margin-top: 20px;">
            <a href="{{action('FieldController@schedule')}}">
              <button class="btn btn-info btn-block">
                Schedule
              </button>
            </a>
          </div>
          <div class="col-sm-6 col-sm-offset-3" style="margin-top: 20px;">
            <a href="{{action('FieldController@report')}}">
              <button class="btn btn-info btn-block">
                Report
              </button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
