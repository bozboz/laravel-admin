@if ($row->check($permission))
	<a href="{{ URL::action($action, [$row->getId()]) }}"
		class="{{ $class }} btn btn-sm"
		@if (!empty($warn))
			data-warn="{{ $warn }}"
		@endif
	>
		<i class="{{ $icon }}"></i>
		{{ $label }}
	</a>
@endif