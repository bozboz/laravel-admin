@extends ('admin::layouts.default')

@section('main')
	@parent
  <div class="jumbotron">
    <div class="container">
      @inject('widgets', 'admin.widgets')
      @foreach ($widgets->get('dashboard') as $widget)
          {{ $widget->render() }}
      @endforeach
    </div>
  </div>
  <hr>
 @stop
