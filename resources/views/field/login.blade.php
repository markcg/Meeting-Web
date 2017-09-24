@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading"><h1 class="text-center">Field Management</h1></div>
        <div class="panel-body">
          <form method="post" action="{{action('FieldController@handle_login')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Username</td>
                    <td><input required type="text" class="form-control" name="username" /></td>
                  </tr>
                  <tr>
                    <td>Password</td>
                    <td><input required type="password" class="form-control" name="password" /></td>
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
