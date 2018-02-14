@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading"><h1 class="text-center">Admin</h1></div>
        <div class="panel-body">
          <form method="post" action="{{action('AdminController@handle_login')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Username</td>
                    <td><input required type="text" maxlength="10" class="form-control" name="username" /></td>
                  </tr>
                  <tr>
                    <td>Password</td>
                    <td><input required type="password" maxlength="10" class="form-control" name="password" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-12">
                {{csrf_field()}}
                <button type="submit" class="btn btn-success btn-block">
                  Login
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
