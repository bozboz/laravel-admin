@extends('admin::layouts.default')

@section('main')
@parent
	@include('admin::partials.new')
	<h1>{{ $heading }}</h1>

	@include('admin::partials.sort-alert')

	<div class="table-responsive">
	@if ($report->hasRows())
		<ol class="secret-list nested{{ $sortableClass }}" data-model="{{ $identifier }}">
		@foreach ($report->getRows() as $row)
			@include('admin::partials.nested-item')
		@endforeach
		</ol>
	@else
		<p>Nothing here yet. Why not add something?</p>
	@endif
</div>
	@include('admin::partials.new')
@stop
