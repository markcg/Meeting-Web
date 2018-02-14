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
          <form method="post" action="{{action('FieldController@handle_edit')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Name</td>
                    <td><input required type="text" class="form-control" name="name" value="{{isset($model) ? $model->name : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Description</td>
                    <td><input required type="text" class="form-control" name="description" value="{{isset($model) ? $model->description : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td><input required type="text" class="form-control" name="email" value="{{isset($model) ? $model->email : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Address</td>
                    <td><input required type="text" class="form-control" name="address" value="{{isset($model) ? $model->address : ''}}" /></td>
                  </tr>
                  <tr>
                    <td>Phone Number</td>
                    <td><input required type="text" class="form-control" name="phone_number" value="{{isset($model) ? $model->phone_number : ''}}" /></td>
                  </tr>
                  <!-- <tr>
                    <td>Username</td>
                    <td><input required type="text" class="form-control" name="username" value="{{isset($model) ? $model->username : ''}}" /></td>
                  </tr> -->
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-12">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{isset($model) ? $model->id : ''}}" />
                <button type="submit" onclick="return confirm('Do you want to edit profile?')" class="btn btn-success btn-block">
                  Save
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
