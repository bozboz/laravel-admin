@extends ('admin::layouts.default')

@section('main')
  @parent
  @inject('widgets', 'admin.widgets')
  <div style="display: flex; flex-wrap: wrap;">
  @foreach ($widgets->get('dashboard') as $widget)
      {!! $widget->render() !!}
  @endforeach
  </div>
 @stop
