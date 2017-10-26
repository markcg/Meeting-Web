@extends('layout')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">Admin Management</div>
        <div class="panel-body">
          <div class="col-sm-12 text-center">
            <a href="{{action('AdminController@home')}}">
              Back
            </a>
          </div>
          <form method="post" action="{{action('AdminController@handle_edit')}}">
            <div class="col-sm-12">
              <table class="table table-bordered">
                <tbody id="schedule-list">
                  <tr>
                    <td>Username</td>
                    <td><input required type="text" class="form-control" name="username" maxlength="10" value="{{isset($model) ? $model->username : ''}}" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-12">
              <div class="col-sm-12">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{session('admin')->id}}" />
                <button type="submit" onclick="return confirm('Do you want to edit username?')" class="btn btn-success btn-block">
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
