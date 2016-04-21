@extends('admin::layouts.default')

@section('main')
@parent
	@section('report_header')

		@include($newButtonPartial)

		<h1>{{ $heading }}</h1>

		@if (Session::has('model'))
			@foreach(Session::get('model') as $msg)
				<div id="js-alert" class="alert alert-success" data-alert="alert">
					{{ $msg }}
				</div>
			@endforeach
		@endif

		@include('admin::partials.sort-alert')

		{!! $report->getHeader() !!}
	@show
	@section('report')
	<div class="table-responsive">
	@if ($report->hasRows())
		<ol class="secret-list faux-table{{ $sortableClass }}" data-model="{{ $identifier }}">

			<li class="faux-table-row faux-table-heading">
			@if ($sortableClass)
				<div class="faux-cell cell-small"></div>
			@endif
			@foreach ($report->getHeadings() as $heading)
				<div class="faux-cell">{{ $heading }}</div>
			@endforeach
				<div class="no-wrap faux-cell"></div>
			</li>

		@foreach ($report->getRows() as $row)
			<li class="faux-table-row js-nested-item" data-id="{{ $row->getId() }}">
			@if ($sortableClass)
				<div class="faux-cell cell-small">
					<i class="fa fa-sort sorting-handle js-sorting-handle"></i>
				</div>
			@endif
			@foreach ($row->getColumns() as $value)
				<div class="faux-cell">{!! $value !!}</div>
			@endforeach
				<div class="no-wrap faux-cell">
					@foreach ($row->filterRowActions($report->getRowActions()) as $action)
						@include($action->getView(), $action->getViewData())
					@endforeach
				</div>
			</li>
		@endforeach
		</ol>
	@else
		<p>Nothing here yet. Why not add something?</p>
	@endif
	</div>
	@show

	@section('report_footer')
		{!! $report->getFooter() !!}
		@include($newButtonPartial)
	@show

	@section('scripts')
		@parent
		<script>
			window.setTimeout(function () {
				$("#js-alert").fadeOut();
			}, 3000);
		</script>
	@stop
@stop
