<a{!! HTML::attributes([
	'href' => $url,
	'class' => $class . ' btn btn-sm',
	'data-warn' => $warn
]) !!}>
	<i class="{{ $icon }}"></i>
	{!! $label !!}
</a>
