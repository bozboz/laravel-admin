{!! Form::open(['url' => $url, 'method' => $method, 'class' => 'inline-form']) !!}
	<button{!! HTML::attributes([
		'class' => $class . ' btn btn-sm',
		'type' => 'submit',
		'data-warn' => $warn,
	]) !!}>
		<i class="{{ $icon }}"></i>
		{{ $label }}
	</button>
{!! Form::close() !!}
