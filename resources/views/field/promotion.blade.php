@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">Field Management</div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('HomeController@home')}}">
              Back
            </a>
          </div>
          <div class="col-sm-6 col-sm-offset-3">
            <a href="{{action('HomeController@add_promotion_list')}}">
              <button class="btn btn-info btn-block">
                Add Promotion
              </button>
            </a>
          </div>
          <div class="col-sm-12" style="padding-top: 20px;">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th></th>
                  <th>Title</th>
                  <th>Price</th>
                  <th>Description</th>
                  <th>Other</th>
                </tr>
              </thead>
              <tbody id="schedule-list">
                @foreach( $promotions as $promotion)
                <tr>
                  <td>{{$promotion->id}}</td>
                  <td>{{$promotion->title}}</td>
                  <td>{{$promotion->price}}</td>
                  <td>{{$promotion->description}}</td>
                  <td>
                    <a href="{{action('HomeController@update_promotion_list', ['id' => $promotion->id])}}">
                      <button class="btn btn-info btn-block">
                        Update Promotion
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
