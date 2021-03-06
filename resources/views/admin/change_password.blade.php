@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">Admin Management</div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('AdminController@home')}}">
              Back
            </a>
          </div>
          <form method="post" action="{{action('AdminController@handle_change_password')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Old Password</td>
                    <!-- <td><input required type="text" pattern=".{4,10}" maxlength="10"  class="form-control" name="old_password" /></td> -->
                    <td><input required type="text"  class="form-control" name="old_password" /></td>
                  </tr>
                  <tr>
                    <td>New Password</td>
                    <td><input required type="text" class="form-control" name="new_password" /></td>
                  </tr>
                  <tr>
                    <td>Confirm New Password</td>
                    <td><input required type="text" class="form-control" name="re_password" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-6">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{session('admin')->id}}" />
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
