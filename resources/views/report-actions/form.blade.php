@if ($row->check($permission))
	<form class="inline-form" action="{{ $url }}" method="POST">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="{{ $method }}">
		<button
			class="{{ $class }} btn btn-sm"
			type="submit"
			@if (!empty($warn))
				data-warn="{{ $warn }}"
			@endif
		>
			<i class="{{ $icon }}"></i> {{ $label }}
		</button>
	</form>
@endif