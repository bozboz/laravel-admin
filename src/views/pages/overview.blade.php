@extends('admin::layouts.default')

@section('main')
@parent
	@include('admin::partials.new')
	<h1>{{ $modelName }}</h1>
	<div class="table-responsive">
		<ul class="table table-striped">

	@foreach ($instances as $i => $model)
		<li>
		@foreach ($columns[$i] as $name => $value)
			<td>{{ $value }}</td>
		@endforeach
			<span class="no-wrap">
				<a href="{{ URL::action($controller . '@edit', array($model->id)) }}" class="btn btn-info btn-sm" type="submit">
					<i class="fa fa-pencil"></i>
					Edit
				</a>

				{{ Form::model($model, array('class' => 'inline-form', 'action' => array($controller . '@destroy', $model->id), 'method' => 'DELETE')) }}
					<button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
				{{ Form::close() }}
			</span>
			<ul></ul>
		</li>
	@endforeach 
	</ul>
</div>
	@include('admin::partials.new')
@stop
