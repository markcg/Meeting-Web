@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading"><h1 class="text-center">Forgot Password</h1></div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('FieldController@forgot_password')}}">
              Back to Login
            </a>
          </div>
          <form method="post" action="{{action('FieldController@handle_forgot_password')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Username</td>
                    <td><input required type="text" class="form-control" name="username" /></td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td><input required type="text" class="form-control" name="email" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-6">
                {{csrf_field()}}
                <button type="submit" onclick="return confirm('Do you want to confirm forgot password?')" class="btn btn-success btn-block">
                  Confirm
                </button>
              </div>
              <div class="col-sm-6">
                <button type="reset" onclick="return confirm('Do you want to cancel?')" class="btn btn-danger btn-block">
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
@section('script')
@endsection
