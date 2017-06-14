@extends('admin::layouts.default')

@section('main')
@parent
{!! Form::model($model, array('method' => $method, 'action' => $action, 'role' => 'form', 'files' => true)) !!}
	<div class="form-row discrete">
		@include('admin::partials.actions')
	</div>

	<h2>@yield('heading')</h2>

	@if ($errors->count())
		<div class="alert alert-danger" role="alert">
			<strong>There are errors in your submission.</strong> <br>
			Please refer to the errors in the form and try again.
		</div>
		<div style="display:none;">{{ var_export($errors->toArray()) }}</div>
	@endif

	@include('admin::fields.field-group', ['attributes' => []])

	<div class="form-row discrete">
		@include('admin::partials.actions')
	</div>

{!! Form::close() !!}

@stop

@section('scripts')
	@parent
	<script>
		@foreach($fields as $field)
			{!! $field->getJavascript() !!}
		@endforeach
	</script>
@stop
