{!! Form::open(['url' => $url, 'method' => $method, 'class' => 'inline-form']) !!}
	<button
		class="{{ $class }} btn btn-sm"
		type="submit"
		@if (!empty($warn))
			data-warn="{{ $warn }}"
		@endif
	>
		<i class="{{ $icon }}"></i> {{ $label }}
	</button>
{!! Form::close() !!}
