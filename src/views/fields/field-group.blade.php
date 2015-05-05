<fieldset>
	<legend>{{ $legend }}</legend>
	@foreach ($fields as $field)
		<div class="form-group{{{ ($field->getErrors($errors)) ? ' bs-callout bs-callout-danger' : '' }}}">
			{{ $field->getLabel() }}
			{{ $field->getInput() }}
			{{ $field->getErrors($errors) }}
		</div>
	@endforeach
</fieldset>