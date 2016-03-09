@if ($compactSingleActionToLink && $actions->count()==1)
	<a class="btn btn-primary {{ $btnClass }} {{ $dropdownClass }}" type="submit" href="{{ $actions->first()->url }}">
		<i class="fa {{ $icon }}"></i>
		{{ $label }}
	</a>
@else
	<div class="btn-group {{ $dropdownClass }}">
		<button href="#" class="dropdown-toggle btn {{ $btnClass }}" data-toggle="dropdown" aria-expanded="false">
			<i class="fa {{ $icon }}"></i>
			{{ $label }}
			<span class="caret"></span>
			<span class="sr-only">Toggle Dropdown</span>
		</button>
		<ul class="dropdown-menu" role="menu">
			@foreach($actions as $action)
				@include ($action->getView(), $action->getViewData())
			@endforeach
		</ul>
	</div>
@endif