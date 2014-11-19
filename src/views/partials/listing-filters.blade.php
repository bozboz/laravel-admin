{{ Form::model($input, ['method' => 'get']) }}
	
	@foreach($filters as $filter)
		{{ $filter }}
	@endforeach

{{ Form::close() }}
