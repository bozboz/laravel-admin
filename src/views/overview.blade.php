@extends('admin::layouts.default')

@section('main')
@parent
	@include('admin::partials.new')
	<h1>{{ Str::plural($modelName) }}</h1>
	<div class="table-responsive">
		<ol class="secret-list sortable faux-table">

			<li class="faux-table-row faux-table-heading">
				<div class="faux-cell cell-small"></div>
			@foreach ($report->getHeadings() as $heading)
				<div class="faux-cell">{{ $heading }}</div>
			@endforeach
				<div class="no-wrap faux-cell"></div>
			</li>

		@foreach ($report->getRows() as $row)
			<li class="faux-table-row">
				<div class="faux-cell cell-small">
					<i class="fa fa-sort sorting-handle"></i>
				</div>
			@foreach ($row->getColumns() as $name => $value)
				<div class="faux-cell">{{ $value }}</div>
			@endforeach
				<div class="no-wrap faux-cell">
					<a href="{{ URL::action($controller . '@edit', array($row->getModel()->id)) }}" class="btn btn-info btn-sm" type="submit">
						<i class="fa fa-pencil"></i>
						Edit
					</a>

					{{ Form::model($row->getModel(), array('class' => 'inline-form', 'action' => array($controller . '@destroy', $row->getModel()->id), 'method' => 'DELETE')) }}
						<button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-minus-square"></i> Delete</button>
					{{ Form::close() }}
				</div>
			</li>
		@endforeach
		</ol>
	</div>
	@include('admin::partials.new')
@stop
