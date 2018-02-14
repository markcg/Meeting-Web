@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-8">Field Management</div>
            <div class="col-xs-4 text-right"><a href="{{action('FieldController@logout')}}">Logout</a></div>
          </div>
        </div>
        <div class="panel-body">
          <div class="col-sm-12">
            <a href="{{action('FieldController@promotions')}}">
              <button class="btn btn-info btn-block">
                Promotions
              </button>
            </a>
          </div>
          <div class="col-sm-12" style="margin-top: 20px;">
            <a href="{{action('FieldController@schedule')}}">
              <button class="btn btn-info btn-block">
                Schedule
              </button>
            </a>
          </div>
          <div class="col-sm-12" style="margin-top: 20px;">
            <a href="{{action('FieldController@report')}}">
              <button class="btn btn-info btn-block">
                Report
              </button>
            </a>
          </div>
          <div class="col-sm-12" style="margin-top: 20px;">
            <a href="{{action('FieldController@edit')}}">
              <button class="btn btn-info btn-block">
                Edit Profile
              </button>
            </a>
          </div>
          <div class="col-sm-12" style="margin-top: 20px;">
            <a href="{{action('FieldController@change_password')}}">
              <button class="btn btn-info btn-block">
                Change Password
              </button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
