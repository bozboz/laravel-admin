@extends('admin::overview')

@section('report')
<div class="table-responsive">
	@if ($report->hasRows())
		<ol class="secret-list nested{{ $sortableClass }}" data-model="{{ $identifier }}">
		@foreach ($report->getRows() as $row)
			@if (config('admin.collapsible_rows'))
				@include('admin::partials.collapsible-nested-item')
			@else
				@include('admin::partials.nested-item')
			@endif
		@endforeach
		</ol>
	@else
		<p>Nothing here yet. Why not add something?</p>
	@endif
</div>
@stop
