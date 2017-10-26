@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">Admin Management</div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('AdminController@customers')}}">
              Back
            </a>
          </div>
          <form method="post" action="{{action('AdminController@handle_edit_customer', ['id' => $model->id])}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Name</td>
                    <td><input required type="text" pattern=".{4,30}" maxlength="30" class="form-control" name="name" value="{{isset($model) ? $model->name : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td><input required type="text" pattern=".{10,30}" maxlength="30" class="form-control" name="email" value="{{isset($model) ? $model->email : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Phone Number</td>
                    <td><input required type="text" pattern=".{10,10}" maxlength="10" class="form-control" name="phone_number" value="{{isset($model) ? $model->phone_number : ''}}" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-6">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{isset($model) ? $model->id : ''}}" />
                <button type="submit" onclick="return confirm('Do you want to edit profile?')" class="btn btn-success btn-block">
                  Save
                </button>
              </div>
              <div class="col-sm-6">
                <button type="reset" onclick="return confirm('Do you want to cancel?')" class="btn btn-info btn-block">
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
