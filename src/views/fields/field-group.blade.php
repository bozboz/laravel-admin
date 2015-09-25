<fieldset{{ HTML::attributes($attributes) }}>
	@if (isset($legend))
		<legend>{{ $legend }}</legend>
	@endif
	@foreach ($fields as $field)
		{!! $field->render($errors) !!}
	@endforeach
</fieldset>
