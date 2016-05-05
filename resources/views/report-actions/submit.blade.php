<button{!! HTML::attributes([
	'class' => $class . ' btn space-left pull-right',
	'type' => 'submit',
	'name' => $name,
	'value' => $value,
]) !!}>
	<i class="{{ $icon }}"></i>
	{!! $label !!}
</button>
