@extends('admin::layouts.default')

@section('main')
@parent
	@include('admin::partials.new')
	<h1>{{ $modelName }}</h1>

	@include('admin::partials.sort-alert')

	{{ $report->getHeader() }}
	<div class="table-responsive">
	@if ($report->hasRows())
		<ol class="secret-list faux-table{{ $sortableClass }}" data-model="{{ $fullModelName }}">

			<li class="faux-table-row faux-table-heading">
				<div class="faux-cell cell-small"></div>
			@foreach ($report->getHeadings() as $heading)
				<div class="faux-cell">{{ $heading }}</div>
			@endforeach
				<div class="no-wrap faux-cell"></div>
			</li>

		@foreach ($report->getRows() as $row)
			<li class="faux-table-row" data-id="{{ $row->getId() }}">
				<div class="faux-cell cell-small">
					<i class="fa fa-sort sorting-handle"></i>
				</div>
			@foreach ($row->getColumns() as $name => $value)
				<div class="faux-cell">{{ $value }}</div>
			@endforeach
				<div class="no-wrap faux-cell">
					<a href="{{ URL::action($controller . '@edit', [$row->getId()]) }}" class="btn btn-info btn-sm" type="submit">
						<i class="fa fa-pencil"></i>
						Edit
					</a>

					{{ Form::open(['class' => 'inline-form', 'action' => [ $controller . '@destroy', $row->getId() ], 'method' => 'DELETE']) }}
						<button class="btn btn-danger btn-sm" data-warn="true" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
					{{ Form::close() }}
				</div>
			</li>
		@endforeach
		</ol>
		{{ $report->getFooter() }}
	@else
		<p>Nothing here yet. Why not add something?</p>
	@endif
	</div>
	@include('admin::partials.new')
@stop
