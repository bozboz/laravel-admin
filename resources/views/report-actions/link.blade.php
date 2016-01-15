@if ($row->check($permission))
	<a href="{{ $url }}"
		class="{{ $class }} btn btn-sm"
		@if (!empty($warn))
			data-warn="{{ $warn }}"
		@endif
	>
		<i class="{{ $icon }}"></i>
		{{ $label }}
	</a>
@endif