@extends('admin::layouts.default')

@section('main')
@parent
	@section('report_header')
		@include('admin::partials.new')
		<h1>{{ $heading }}</h1>

		@if (Session::has('model'))
			@foreach(Session::get('model') as $msg)
				<div id="js-alert" class="alert alert-success" data-alert="alert">
					{{ $msg }}
				</div>
			@endforeach
		@endif

		@include('admin::partials.sort-alert')

		{{ $report->getHeader() }}
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
			<li class="faux-table-row" data-id="{{ $row->getId() }}">
			@if ($sortableClass)
				<div class="faux-cell cell-small">
					<i class="fa fa-sort sorting-handle"></i>
				</div>
			@endif
			@foreach ($row->getColumns() as $name => $value)
				<div class="faux-cell">{{ $value }}</div>
			@endforeach
				<div class="no-wrap faux-cell">
					@if ($row->check($canEdit))
						<a href="{{ URL::action($editAction, [$row->getId()]) }}" class="btn btn-info btn-sm" type="submit">
							<i class="fa fa-pencil"></i>
							Edit
						</a>
					@endif

					@if ($row->check($canDelete))
						{{ Form::open(['class' => 'inline-form', 'action' => [ $destroyAction, $row->getId() ], 'method' => 'DELETE']) }}
							<button class="btn btn-danger btn-sm" data-warn="true" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
						{{ Form::close() }}
					@endif
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
		{{ $report->getFooter() }}
		@include('admin::partials.new')
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
