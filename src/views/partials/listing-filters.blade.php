{!! Form::open(['method' => 'get', 'role' => 'form', 'class' => 'form-inline filter-form']) !!}
	@foreach($filters as $filter)
		<div class="form-group">
			{!! $filter !!}
		</div>
	@endforeach
	@if (isset($perPageOptions))
		<div class="form-group" style="float: right;">
			<label for="per-page">Items per page</label>
			{!! Form::select(
				'per-page',
				$perPageOptions,
				Input::get('per-page'),
				[
					'onChange' => 'this.form.submit()',
					'class' => 'form-control select2'
				]
			) !!}
		</div>
	@endif
{!! Form::close() !!}
