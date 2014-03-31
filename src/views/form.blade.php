@extends('admin::layouts.default')

@section('main')
@parent
{{ Form::model($model, array('method' => $method, 'action' => $action, 'role' => 'form', 'files' => true)) }}
	<div class="form-row discrete">
		@include('admin::partials.save')
	</div>

	<h2>@yield('heading')</h2>

	@foreach($fields as $field)
	 <div class="form-group{{{ ($field->getErrors($errors)) ? ' bs-callout bs-callout-danger' : '' }}}">
		{{ $field->getLabel(array('for' => $field->title)) }}
		{{ $field->getInput(array('class' => 'form-control', 'id' => $field->title)) }}
		{{ $field->getErrors($errors) }}
	</div>

	@endforeach

	<div class="form-row">
		@include('admin::partials.save')
	</div>

{{ Form::close() }}

@stop
