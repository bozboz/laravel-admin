@extends('admin::layouts.default')

@section('main')
	@parent
	<table>
	@foreach ($instances as $i => $model)
		<tr>
		@foreach ($columns[$i] as $name => $value)
			<td>{{ $value }}</td>
		@endforeach
			<td>
			{{ Form::model($model, array('route' => array($prefix . 'destroy', $model->id), 'method' => 'DELETE')) }}
				{{ Form::submit('Delete') }}
			{{ Form::close() }}
			</td>
		</tr>
	@endforeach
	</table>

	{{ Form::open(array('method' => 'get', 'route' => $prefix . 'create')) }}
		{{ Form::submit('New ' . $modelName) }}
	{{ Form::close() }}
@stop