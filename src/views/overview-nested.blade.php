@extends('admin::layouts.default')

@section('main')
@parent
	@include('admin::partials.new')
	<h1>{{ Str::plural(class_basename($modelName)) }}</h1>

	@include('admin::partials.sort-alert')

	<div class="table-responsive">
		<ol class="secret-list nested{{ $sortableClass }}" data-model="{{ $fullModelName }}">
		@foreach ($report->getRows() as $row)
			@include('admin::partials.nested-item')
		@endforeach
	</ol>
</div>
	@include('admin::partials.new')
@stop
