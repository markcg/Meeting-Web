@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-8">Admin Management: Field</div>
            <div class="col-xs-4 text-right"><a href="{{action('AdminController@logout')}}">Logout</a></div>
          </div>
        </div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('AdminController@home')}}">
              Back
            </a>
          </div>
          <div class="col-sm-12" style="padding-top: 20px;">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th colspan="3">Other</th>
                </tr>
              </thead>
              <tbody id="schedule-list">
                @foreach( $collection as $item)
                <tr>
                  <td>{{$item->id}}</td>
                  <td>{{$item->name}}</td>
                  <td>{{$item->email}}</td>
                  <td>{{$item->phone_number}}</td>
                  <td>
                    <?php
                    $color = 'warning';
                    if($item->confirm == 2) {
                        $color = 'danger';
                    }
                    if($item->confirm == 1) {
                        $color = 'success';
                    } ?>
                    <?php
                    $active = 'Waiting';
                    if($item->confirm == 2) {
                        $active = 'Not Active';
                    }
                    if($item->confirm == 1) {
                        $active = 'Active';
                    } ?>
                    <a <?php if($active != 'Active') :?> onclick="return confirm('Do you want to approve this field owner?');" <?php 
                   endif;?> href="{{action('AdminController@confirm_field', ['id' => $item->id])}}">
                      <button class="btn btn-<?php echo $color;?> btn-block">
                        <?php echo $active;?>
                      </button>
                    </a>
                  </td>
                  <td>
                    <a href="{{action('AdminController@field', ['id' => $item->id])}}">
                      <button class="btn btn-info btn-block">
                        Edit
                      </button>
                    </a>
                  </td>
                  <td>
                    <a onclick="return confirm('Do you want to remove this account?');" href="{{action('AdminController@delete_field', ['id' => $item->id])}}">
                      <button class="btn btn-info btn-block">
                        Delete
                      </button>
                    </a>
                  </td>
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
