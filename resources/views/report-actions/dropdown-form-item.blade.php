<li>
	{!! Form::open(['url' => $url, 'method' => $method, 'class' => 'form']) !!}
		<button{!! HTML::attributes([
			'class' => $class,
			'type' => 'submit',
			'data-warn' => $warn,
		]) !!}>
			<i class="{{ $icon }}"></i>
			{{ $label }}
		</button>
	{!! Form::close() !!}
</li>