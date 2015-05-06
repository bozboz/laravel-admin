@extends('admin::layouts.default')

@section('main')
@parent
{{ Form::model($model, array('method' => $method, 'action' => $action, 'role' => 'form', 'files' => true)) }}
	<div class="form-row discrete">
		@include('admin::partials.save')
	</div>

	<h2>@yield('heading')</h2>

	@include('admin::fields.field-group', ['attributes' => []])

	<div class="form-row">
		@include('admin::partials.save')
		@include('admin::partials.listing')
	</div>

{{ Form::close() }}

@stop

@section('scripts')
	@parent
	{{ $javascript }}
@stop
