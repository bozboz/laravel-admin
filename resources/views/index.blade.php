@extends ('admin::layouts.default')

@section('main')
	@parent
  <div class="jumbotron">
    <div class="container">
      <h1>Hello, {{ $user->first_name }}</h1>
    </div>
  </div>
  <hr>
  @inject('widgets', 'admin.widgets')
  @foreach ($widgets->get('dashboard') as $widget)
      {!! $widget->render() !!}
  @endforeach
 @stop
