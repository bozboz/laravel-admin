{{ Form::open(['method' => 'get', 'role' => 'form', 'class' => 'form-inline filter-form']) }}
	@foreach($filters as $filter)
		<div class="form-group">
			{{ $filter }}
		</div>
	@endforeach
{{ Form::close() }}
