@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">Field Management</div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('FieldController@promotions')}}">
              Back
            </a>
          </div>
          <form method="post" action="{{isset($id) ? action('FieldController@edit_promotion', ['id' => $promotion->id]) : action('FieldController@add_promotion')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Name</td>
                    <td><input required type="text" pattern=".{4,30}" maxlength="30" class="form-control" name="title" value="{{isset($promotion) ? $promotion->title : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Price</td>
                    <td><input required type="number" min="10" max="9999999999" class="form-control" name="price" value="{{isset($promotion) ? $promotion->price : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Description</td>
                    <td><input required type="text" pattern=".{10,250}" maxlength="250" class="form-control" name="description" value="{{isset($promotion) ? $promotion->description : ''}}" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-6">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{isset($promotion) ? $promotion->id : ''}}" />
                <input type="hidden" name="field_id" value="{{session('field')->id}}" />
                <button type="submit" onclick="return confirm('Do you want to save the promotion?')" class="btn btn-success btn-block">
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
