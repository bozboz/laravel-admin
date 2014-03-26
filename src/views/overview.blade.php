@extends('admin::layouts.default')

@section('main')
@parent
	{{ Form::open(array('method' => 'get', 'action' => $controller . '@create')) }}
		<button class="btn btn-success pull-right" type="submit"><i class="fa fa-plus-square"></i>New {{ $modelName }}</button>
	{{ Form::close() }}
	<h1>{{ $modelName }}</h1>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
			</thead>

	@foreach ($instances as $i => $model)
		<tr>
		@foreach ($columns[$i] as $name => $value)
			<td>{{ $value }}</td>
		@endforeach
			<td class="no-wrap">
				<a href="{{ URL::action($controller . '@edit', array($model->id)) }}" class="btn btn-danger btn-sm" type="submit">
					<i class="fa fa-minus-square"></i>
					Edit
				</a>

				{{ Form::model($model, array('action' => array($controller . '@destroy', $model->id), 'method' => 'DELETE')) }}
					<button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
				{{ Form::close() }}
			</td>
		</tr>
	@endforeach 
	</table>
</div>
	{{ Form::open(array('method' => 'get', 'action' => $controller . '@create')) }}
		<button class="btn btn-success pull-right match-heading" type="submit"><i class="fa fa-plus-square"></i>New {{ $modelName }}</button>
	{{ Form::close() }}
@stop
