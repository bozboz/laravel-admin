@extends('admin::layouts.default')

@section('main')
@parent
{{ Form::model($model, array('method' => $method, 'route' => $route)) }}

@foreach($fields as $field)

	<div class="form-row">
		{{ $field->getLabel() }}
		{{ $field->getInput() }}
		{{ $field->getErrors($errors) }}
	</div>

@endforeach

	<div class="form-row">
		{{ Form::submit() }}
	</div>

{{ Form::close() }}

@stop