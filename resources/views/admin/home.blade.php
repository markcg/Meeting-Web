@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-8">Admin Management: Customer</div>
            <div class="col-xs-4 text-right"><a href="{{action('AdminController@logout')}}">Logout</a></div>
          </div>
        </div>
        <div class="panel-body">
          <div class="col-sm-12">
            <a href="{{action('AdminController@customers')}}">
              <button class="btn btn-info btn-block">
                Customers
              </button>
            </a>
          </div>
          <div class="col-sm-12" style="margin-top: 20px;">
            <a href="{{action('AdminController@fields')}}">
              <button class="btn btn-info btn-block">
                Fields
              </button>
            </a>
          </div>
          <div class="col-sm-12" style="margin-top: 20px;">
            <a href="{{action('AdminController@edit')}}">
              <button class="btn btn-info btn-block">
                Edit
              </button>
            </a>
          </div>
          <div class="col-sm-12" style="margin-top: 20px;">
            <a href="{{action('AdminController@change_password')}}">
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
