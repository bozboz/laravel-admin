<div{!! HTML::attributes($dropdownAttributes) !!}>
	<button{!! HTML::attributes($attributes) !!}>
		<i class="fa {{ $icon }}"></i>
		{{ $label }}
		<span class="caret"></span>
		<span class="sr-only">Toggle Dropdown</span>
	</button>
	<ul class="dropdown-menu" role="menu">
		@foreach($actions as $action)
			<li>
				{!! $action->render() !!}
			</li>
		@endforeach
	</ul>
</div>
