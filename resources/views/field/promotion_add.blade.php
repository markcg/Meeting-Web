@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">Field Management</div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('HomeController@promotions')}}">
              Back
            </a>
          </div>
          <form method="post" action="{{action('HomeController@add_promotion')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Name</td>
                    <td><input type="text" class="form-control" name="title" value="{{isset($promotion) ? $promotion->title : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Price</td>
                    <td><input type="text" class="form-control" name="price" value="{{isset($promotion) ? $promotion->price : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Description</td>
                    <td><input type="text" class="form-control" name="description" value="{{isset($promotion) ? $promotion->description : ''}}" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-6">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{isset($promotion) ? $promotion->id : ''}}" />
                <button type="submit" class="btn btn-success btn-block">
                  Confirm
                </button>
              </div>
              <div class="col-sm-6">
                <button type="reset" class="btn btn-info btn-block">
                  Cancel
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
