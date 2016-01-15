@if ($row->check($permission))
	<form class="inline-form" action="{{ URL::action($action, [$row->getId()]) }}" method="{{ $method }}">
		<button class="{{ $class }} btn btn-sm"
			type="submit"
			@if (!empty($warn))
				data-warn="{{ $warn }}"
			@endif
		>
			<i class="{{ $icon }}"></i> {{ $label }}
		</button>
	</form>
@endif