@extends ('admin::layouts.default')

@section('main')
	@parent
  <div class="jumbotron">
    <div class="container">
      <h1>Hello, {{ $user->first_name }}</h1>
    </div>
  </div>
  <hr>
 @stop
