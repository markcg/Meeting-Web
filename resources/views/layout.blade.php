<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Football Meeting Manager</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

  <!-- Styles -->
  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  <script src="/js/app.js"></script>
  @yield('styles')
</head>
<body>
  <div class="flex-center position-ref full-height">
    @if (Route::has('login'))
    <div class="top-right links">
      @if (Auth::check())
      <a href="{{ url('/home') }}">Home</a>
      @else
      <a href="{{ url('/login') }}">Login</a>
      <a href="{{ url('/register') }}">Register</a>
      @endif
    </div>
    @endif
    @if(Session::has('success'))
    @foreach(Session::get('success') as $success)
    <div class="alert alert-success text-center" role="alert">{{$success}}</div>
    @endforeach
    {{Session::forget('success')}}
    @endif
    <?php $all = $errors->all(); ?>
    @if(!empty($all))
    <?php $error = $all[0]; ?>
    <div class="alert alert-danger text-center" role="alert">{{$error}}</div>
    @endif
    <div class="container">
      @yield('content')
    </div>
  </div>
  @yield('script')
</body>
</html>
